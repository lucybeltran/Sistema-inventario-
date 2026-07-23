<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Models\AuditoriaLog;

class BackupController extends Controller
{
    public function index()
    {
        if (!Auth::user()->esAdmin()) {
            abort(403);
        }

        $backupDir = storage_path('app/backups');
        if (!File::exists($backupDir)) {
            File::makeDirectory($backupDir, 0755, true);
        }

        $files = File::files($backupDir);
        $backups = [];

        foreach ($files as $file) {
            if ($file->getExtension() === 'sql' || $file->getExtension() === 'zip') {
                $backups[] = [
                    'filename' => $file->getFilename(),
                    'size' => $this->formatBytes($file->getSize()),
                    'raw_size' => $file->getSize(),
                    'created_at' => \Carbon\Carbon::createFromTimestamp($file->getMTime()),
                ];
            }
        }

        // Ordenar del más reciente al más antiguo
        usort($backups, function ($a, $b) {
            return $b['created_at']->timestamp <=> $a['created_at']->timestamp;
        });

        return view('backups.index', compact('backups'));
    }

    public function create()
    {
        if (!Auth::user()->esAdmin()) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        try {
            $backupDir = storage_path('app/backups');
            if (!File::exists($backupDir)) {
                File::makeDirectory($backupDir, 0755, true);
            }

            // Generar archivo ZIP que contendrá base de datos y archivos de storage
            $filename = 'backup-' . date('Y-m-d_H-i-s') . '.zip';
            $zipPath = $backupDir . '/' . $filename;

            // Generar volcado SQL
            $sqlContent = "-- Copia de seguridad del Sistema de Inventario\n";
            $sqlContent .= "-- Fecha de generación: " . date('Y-m-d H:i:s') . "\n";
            $sqlContent .= "-- Creado por: " . Auth::user()->name . "\n";
            $sqlContent .= "SET FOREIGN_KEY_CHECKS = 0;\n\n";

            // Obtener listado de tablas
            $tables = DB::select('SHOW TABLES');
            $dbNameKey = 'Tables_in_' . env('DB_DATABASE');

            foreach ($tables as $table) {
                $tableName = $table->$dbNameKey;

                // Estructura de la tabla
                $sqlContent .= "-- -----------------------------------------------------\n";
                $sqlContent .= "-- Estructura de tabla para la tabla `$tableName`\n";
                $sqlContent .= "-- -----------------------------------------------------\n";
                $sqlContent .= "DROP TABLE IF EXISTS `$tableName`;\n";
                
                $showCreate = DB::select("SHOW CREATE TABLE `$tableName`")[0];
                $createTableKey = 'Create Table';
                $sqlContent .= $showCreate->$createTableKey . ";\n\n";

                // Datos de la tabla
                $rows = DB::table($tableName)->get();
                if ($rows->count() > 0) {
                    $sqlContent .= "-- Datos para la tabla `$tableName`\n";
                    foreach ($rows as $row) {
                        $rowArray = (array) $row;
                        $keys = array_keys($rowArray);
                        $escapedValues = array_map(function ($value) {
                            if (is_null($value)) {
                                return 'NULL';
                            }
                            // Escapar comillas y caracteres especiales
                            return "'" . str_replace(["\\", "'", "\r", "\n"], ["\\\\", "\\'", "\\r", "\\n"], $value) . "'";
                        }, array_values($rowArray));

                        $sqlContent .= "INSERT INTO `$tableName` (`" . implode("`, `", $keys) . "`) VALUES (" . implode(", ", $escapedValues) . ");\n";
                    }
                    $sqlContent .= "\n";
                }
            }

            $sqlContent .= "SET FOREIGN_KEY_CHECKS = 1;\n";

            // Crear el archivo ZIP y empaquetar SQL + Directorio public storage
            $zip = new \ZipArchive();
            if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === true) {
                // Agregar base de datos SQL
                $zip->addFromString('database.sql', $sqlContent);

                // Agregar archivos de storage/app/public de forma recursiva
                $storageDir = storage_path('app/public');
                if (File::exists($storageDir)) {
                    $files = new \RecursiveIteratorIterator(
                        new \RecursiveDirectoryIterator($storageDir),
                        \RecursiveIteratorIterator::LEAVES_ONLY
                    );

                    foreach ($files as $name => $file) {
                        if (!$file->isDir()) {
                            $filePath = $file->getRealPath();
                            $relativePath = 'storage/' . substr($filePath, strlen($storageDir) + 1);
                            $relativePath = str_replace('\\', '/', $relativePath); // Normalizar barras diagonales
                            $zip->addFile($filePath, $relativePath);
                        }
                    }
                }

                $zip->close();
            } else {
                throw new \Exception("No se pudo iniciar la creación del archivo ZIP.");
            }

            AuditoriaLog::registrar(
                'CREACION_RESPALDO',
                "El Administrador " . Auth::user()->name . " generó una copia de seguridad ZIP completa: {$filename}."
            );

            return response()->json([
                'success' => true,
                'message' => 'Copia de seguridad ZIP creada correctamente.',
                'filename' => $filename
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Error al generar la copia de seguridad: ' . $e->getMessage()
            ], 500);
        }
    }

    public function download($filename)
    {
        if (!Auth::user()->esAdmin()) {
            abort(403);
        }

        $filePath = storage_path('app/backups/' . $filename);

        if (!File::exists($filePath)) {
            abort(404, 'El archivo de copia de seguridad no existe.');
        }

        return response()->download($filePath);
    }

    public function destroy($filename)
    {
        if (!Auth::user()->esAdmin()) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $filePath = storage_path('app/backups/' . $filename);

        if (!File::exists($filePath)) {
            return response()->json(['error' => 'El archivo de copia de seguridad no existe.'], 404);
        }

        File::delete($filePath);

        AuditoriaLog::registrar(
            'ELIMINACION_RESPALDO',
            "El Administrador " . Auth::user()->name . " eliminó el archivo de copia de seguridad: {$filename}."
        );

        return response()->json([
            'success' => true,
            'message' => 'Copia de seguridad eliminada correctamente.'
        ]);
    }

    public function restore($filename)
    {
        if (!Auth::user()->esAdmin()) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $filePath = storage_path('app/backups/' . $filename);

        if (!File::exists($filePath)) {
            return response()->json(['error' => 'El archivo de copia de seguridad no existe.'], 404);
        }

        try {
            // Verificar extensión de archivo
            if (File::extension($filePath) === 'zip') {
                $tempExtractDir = storage_path('app/temp_restore_' . time());
                if (!File::exists($tempExtractDir)) {
                    File::makeDirectory($tempExtractDir, 0755, true);
                }

                // Extraer ZIP
                $zip = new \ZipArchive();
                if ($zip->open($filePath) === true) {
                    $zip->extractTo($tempExtractDir);
                    $zip->close();
                } else {
                    throw new \Exception("No se pudo abrir el archivo ZIP de respaldo.");
                }

                $sqlPath = $tempExtractDir . '/database.sql';
                if (!File::exists($sqlPath)) {
                    File::deleteDirectory($tempExtractDir);
                    throw new \Exception("El respaldo ZIP no contiene el archivo de base de datos 'database.sql'.");
                }

                // Restaurar base de datos
                $sql = File::get($sqlPath);
                DB::connection()->getPdo()->exec("SET FOREIGN_KEY_CHECKS = 0;");
                DB::unprepared($sql);
                DB::connection()->getPdo()->exec("SET FOREIGN_KEY_CHECKS = 1;");

                // Restaurar storage (copiar directorio recursivamente e integrar imágenes sin duplicados)
                $zipStorageDir = $tempExtractDir . '/storage';
                if (File::exists($zipStorageDir)) {
                    $targetStorageDir = storage_path('app/public');
                    File::copyDirectory($zipStorageDir, $targetStorageDir);
                }

                // Limpiar directorio temporal
                File::deleteDirectory($tempExtractDir);
            } else {
                // Restauración clásica de archivo .sql solo
                $sql = File::get($filePath);
                DB::connection()->getPdo()->exec("SET FOREIGN_KEY_CHECKS = 0;");
                DB::unprepared($sql);
                DB::connection()->getPdo()->exec("SET FOREIGN_KEY_CHECKS = 1;");
            }

            AuditoriaLog::registrar(
                'RESTAURACION_BASE_DATOS',
                "El Administrador " . Auth::user()->name . " restauró el sistema al respaldo: {$filename}."
            );

            return response()->json([
                'success' => true,
                'message' => 'Respaldo restaurado correctamente.'
            ]);

        } catch (\Exception $e) {
            DB::connection()->getPdo()->exec("SET FOREIGN_KEY_CHECKS = 1;");
            return response()->json([
                'success' => false,
                'error' => 'Error al restaurar el respaldo: ' . $e->getMessage()
            ], 500);
        }
    }

    public function uploadRestore(Request $request)
    {
        if (!Auth::user()->esAdmin()) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $request->validate([
            'backup_file' => 'required|file|max:102400', // Máx 100MB
        ]);

        try {
            $file = $request->file('backup_file');
            $originalExtension = strtolower($file->getClientOriginalExtension());

            if (!in_array($originalExtension, ['zip', 'sql'])) {
                return response()->json(['success' => false, 'error' => 'Solo se permiten archivos .zip o .sql.'], 400);
            }

            $filename = 'uploaded-backup-' . time() . '.' . $originalExtension;
            
            $backupDir = storage_path('app/backups');
            if (!File::exists($backupDir)) {
                File::makeDirectory($backupDir, 0755, true);
            }
            
            $file->move($backupDir, $filename);
            
            // Llamar al método restore para procesar la instalación
            $response = $this->restore($filename);
            $responseData = json_decode($response->getContent(), true);

            // Eliminar el archivo subido de forma temporal
            $filePath = $backupDir . '/' . $filename;
            if (File::exists($filePath)) {
                File::delete($filePath);
            }

            if (isset($responseData['success']) && $responseData['success'] === true) {
                return response()->json([
                    'success' => true,
                    'message' => 'Respaldo subido y restaurado con éxito.'
                ]);
            } else {
                throw new \Exception($responseData['error'] ?? 'Error desconocido al aplicar el respaldo.');
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Error al subir y restaurar: ' . $e->getMessage()
            ], 500);
        }
    }

    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
