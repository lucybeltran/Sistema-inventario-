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

            $filename = 'backup-' . date('Y-m-d_H-i-s') . '.sql';
            $filePath = $backupDir . '/' . $filename;

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

            // Guardar archivo SQL
            File::put($filePath, $sqlContent);

            AuditoriaLog::registrar(
                'CREACION_RESPALDO',
                "El Administrador " . Auth::user()->name . " generó una copia de seguridad: {$filename}."
            );

            return response()->json([
                'success' => true,
                'message' => 'Copia de seguridad creada correctamente.',
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
            $sql = File::get($filePath);

            // Ejecución segura deshabilitando llaves foráneas temporalmente
            DB::connection()->getPdo()->exec("SET FOREIGN_KEY_CHECKS = 0;");
            
            // Laravel DB::unprepared nos permite correr el script SQL entero en una sola llamada PDO
            DB::unprepared($sql);

            DB::connection()->getPdo()->exec("SET FOREIGN_KEY_CHECKS = 1;");

            AuditoriaLog::registrar(
                'RESTAURACION_BASE_DATOS',
                "El Administrador " . Auth::user()->name . " restauró el sistema al respaldo: {$filename}."
            );

            return response()->json([
                'success' => true,
                'message' => 'Base de datos restaurada correctamente a partir de ' . $filename
            ]);

        } catch (\Exception $e) {
            DB::connection()->getPdo()->exec("SET FOREIGN_KEY_CHECKS = 1;");
            return response()->json([
                'success' => false,
                'error' => 'Error al restaurar la base de datos: ' . $e->getMessage()
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
