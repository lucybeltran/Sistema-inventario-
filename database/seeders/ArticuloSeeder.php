<?php

namespace Database\Seeders;

use App\Models\Articulo;
use Illuminate\Database\Seeder;

class ArticuloSeeder extends Seeder
{
    public function run(): void
    {
        $articulos = [
            // === G-1: MATERIAL EXPLOSIVO ===
            ['codigo' => 'G-1/0001', 'nombre' => 'NITRATO', 'unidad' => 'KILOS', 'grupo_id' => 'G-1'],
            ['codigo' => 'G-1/0002', 'nombre' => 'DINAMITA', 'unidad' => 'UNIDAD', 'grupo_id' => 'G-1'],
            ['codigo' => 'G-1/0003', 'nombre' => 'FULMINANTE', 'unidad' => 'UNIDAD', 'grupo_id' => 'G-1'],
            ['codigo' => 'G-1/0004', 'nombre' => 'GUIA', 'unidad' => 'METROS', 'grupo_id' => 'G-1'],
            ['codigo' => 'G-1/0005', 'nombre' => 'BARRA 0,80', 'unidad' => 'UNIDAD', 'grupo_id' => 'G-1'],
            ['codigo' => 'G-1/0006', 'nombre' => 'BARRA 1,20', 'unidad' => 'UNIDAD', 'grupo_id' => 'G-1'],
            ['codigo' => 'G-1/0007', 'nombre' => 'BARRA 1,80', 'unidad' => 'UNIDAD', 'grupo_id' => 'G-1'],
            ['codigo' => 'G-1/0008', 'nombre' => 'BARRENO 0,80', 'unidad' => 'UNIDAD', 'grupo_id' => 'G-1'],
            ['codigo' => 'G-1/0009', 'nombre' => 'BARRENO 1,20', 'unidad' => 'UNIDAD', 'grupo_id' => 'G-1'],
            ['codigo' => 'G-1/0010', 'nombre' => 'BARRENO 1,80', 'unidad' => 'UNIDAD', 'grupo_id' => 'G-1'],
            ['codigo' => 'G-1/0011', 'nombre' => 'BROCA N° 39 mm', 'unidad' => 'UNIDAD', 'grupo_id' => 'G-1'],
            ['codigo' => 'G-1/0012', 'nombre' => 'BROCA N° 41 mm', 'unidad' => 'UNIDAD', 'grupo_id' => 'G-1'],
            ['codigo' => 'G-1/0013', 'nombre' => "GARRA '1' CON ESPIGA 3/4", 'unidad' => 'UNIDAD', 'grupo_id' => 'G-1'],
            ['codigo' => 'G-1/0014', 'nombre' => "GARRA '1' CON ROSCA EXTERIOR 3/4", 'unidad' => 'UNIDAD', 'grupo_id' => 'G-1'],
            ['codigo' => 'G-1/0015', 'nombre' => "GARRA DE '1' CON ROSCA INTERIOR '1'", 'unidad' => 'UNIDAD', 'grupo_id' => 'G-1'],
            ['codigo' => 'G-1/0016', 'nombre' => 'CARGADOR DE ANFO CON SPIGA DE 3/4', 'unidad' => 'UNIDAD', 'grupo_id' => 'G-1'],

            // === G-2: ACCESORIOS ===
            ['codigo' => 'G-2/0001', 'nombre' => "LLAVE DE PASO '1'", 'unidad' => 'UNIDAD', 'grupo_id' => 'G-2'],
            ['codigo' => 'G-2/0002', 'nombre' => "LLAVE DE PASO '1' CORTINA", 'unidad' => 'UNIDAD', 'grupo_id' => 'G-2'],
            ['codigo' => 'G-2/0003', 'nombre' => "LLAVE DE PASO '2'", 'unidad' => 'UNIDAD', 'grupo_id' => 'G-2'],
            ['codigo' => 'G-2/0004', 'nombre' => "LLAVE DE PASO '2' CORTINA", 'unidad' => 'UNIDAD', 'grupo_id' => 'G-2'],
            ['codigo' => 'G-2/0005', 'nombre' => "UNION PATENTE '1'", 'unidad' => 'UNIDAD', 'grupo_id' => 'G-2'],
            ['codigo' => 'G-2/0006', 'nombre' => "UNION PATENTE '2'", 'unidad' => 'UNIDAD', 'grupo_id' => 'G-2'],
            ['codigo' => 'G-2/0007', 'nombre' => "NIPLE DE '1'", 'unidad' => 'UNIDAD', 'grupo_id' => 'G-2'],
            ['codigo' => 'G-2/0008', 'nombre' => "NIPLE DE '2'", 'unidad' => 'UNIDAD', 'grupo_id' => 'G-2'],
            ['codigo' => 'G-2/0009', 'nombre' => "COPLA DE '1'", 'unidad' => 'UNIDAD', 'grupo_id' => 'G-2'],
            ['codigo' => 'G-2/0010', 'nombre' => "COPLA DE '2'", 'unidad' => 'UNIDAD', 'grupo_id' => 'G-2'],
            ['codigo' => 'G-2/0011', 'nombre' => "CODO DE '2'", 'unidad' => 'UNIDAD', 'grupo_id' => 'G-2'],
            ['codigo' => 'G-2/0012', 'nombre' => "T DE '1'", 'unidad' => 'UNIDAD', 'grupo_id' => 'G-2'],
            ['codigo' => 'G-2/0013', 'nombre' => "T DE '2'", 'unidad' => 'UNIDAD', 'grupo_id' => 'G-2'],
            ['codigo' => 'G-2/0014', 'nombre' => "Y DE '1'", 'unidad' => 'UNIDAD', 'grupo_id' => 'G-2'],
            ['codigo' => 'G-2/0015', 'nombre' => "Y DE '2'", 'unidad' => 'UNIDAD', 'grupo_id' => 'G-2'],
            ['codigo' => 'G-2/0016', 'nombre' => "CANOTO CON SPIGA DE '1'", 'unidad' => 'UNIDAD', 'grupo_id' => 'G-2'],
            ['codigo' => 'G-2/0017', 'nombre' => "CANOTO A ROSCA DE '1'", 'unidad' => 'UNIDAD', 'grupo_id' => 'G-2'],
            ['codigo' => 'G-2/0018', 'nombre' => "CANOTO CON SPIGA DE '2'", 'unidad' => 'UNIDAD', 'grupo_id' => 'G-2'],
            ['codigo' => 'G-2/0019', 'nombre' => "CANOTO A ROSCA DE '2'", 'unidad' => 'UNIDAD', 'grupo_id' => 'G-2'],
            ['codigo' => 'G-2/0020', 'nombre' => "REDUCCION DE '2' A '1'", 'unidad' => 'UNIDAD', 'grupo_id' => 'G-2'],
            ['codigo' => 'G-2/0021', 'nombre' => "REDUCCION DE '2' A '1,5'", 'unidad' => 'UNIDAD', 'grupo_id' => 'G-2'],
            ['codigo' => 'G-2/0022', 'nombre' => "REDUCCION CON SPIGA DE '2' A '1,5'", 'unidad' => 'UNIDAD', 'grupo_id' => 'G-2'],
            ['codigo' => 'G-2/0023', 'nombre' => "CLAVOS '7'", 'unidad' => 'BOLSAS', 'grupo_id' => 'G-2'],
            ['codigo' => 'G-2/0024', 'nombre' => "CLAVOS '6'", 'unidad' => 'BOLSAS', 'grupo_id' => 'G-2'],
            ['codigo' => 'G-2/0025', 'nombre' => "CLAVOS '5'", 'unidad' => 'BOLSAS', 'grupo_id' => 'G-2'],
            ['codigo' => 'G-2/0026', 'nombre' => "CLAVOS '4'", 'unidad' => 'BOLSAS', 'grupo_id' => 'G-2'],
            ['codigo' => 'G-2/0027', 'nombre' => 'BARILLA DE 3/8', 'unidad' => 'METROS', 'grupo_id' => 'G-2'],
            ['codigo' => 'G-2/0028', 'nombre' => 'BARILLA DE 1/2', 'unidad' => 'METROS', 'grupo_id' => 'G-2'],
            ['codigo' => 'G-2/0029', 'nombre' => 'VOLANDAS PLANA DE 3/8', 'unidad' => 'UNIDAD', 'grupo_id' => 'G-2'],
            ['codigo' => 'G-2/0030', 'nombre' => 'TUERCA DE 3/8', 'unidad' => 'UNIDAD', 'grupo_id' => 'G-2'],
            ['codigo' => 'G-2/0031', 'nombre' => 'VOLANDAS PLANA DE 1/2', 'unidad' => 'UNIDAD', 'grupo_id' => 'G-2'],
            ['codigo' => 'G-2/0032', 'nombre' => 'TUERCA DE 1/2', 'unidad' => 'UNIDAD', 'grupo_id' => 'G-2'],
            ['codigo' => 'G-2/0033', 'nombre' => "PERNOS DE 3/8 X '2'", 'unidad' => 'UNIDAD', 'grupo_id' => 'G-2'],
            ['codigo' => 'G-2/0034', 'nombre' => 'RODAMIENTO A BOLA /6209-2RS', 'unidad' => 'UNIDAD', 'grupo_id' => 'G-2'],
            ['codigo' => 'G-2/0035', 'nombre' => 'RODAMIENTO A BOLA /63092RSC3', 'unidad' => 'UNIDAD', 'grupo_id' => 'G-2'],
            ['codigo' => 'G-2/0036', 'nombre' => "RADIO 'JANDI'", 'unidad' => 'UNIDAD', 'grupo_id' => 'G-2'],

            // === G-3: HERRAMIENTAS ===
            ['codigo' => 'G-3/0001', 'nombre' => 'PICOTA CON PALA ANCHA', 'unidad' => 'UNIDAD', 'grupo_id' => 'G-3'],
            ['codigo' => 'G-3/0002', 'nombre' => 'PICOTA NORMAL', 'unidad' => 'UNIDAD', 'grupo_id' => 'G-3'],
            ['codigo' => 'G-3/0003', 'nombre' => 'PALA PUNTA HUEVO', 'unidad' => 'UNIDAD', 'grupo_id' => 'G-3'],
            ['codigo' => 'G-3/0004', 'nombre' => 'COMBO DE 2K', 'unidad' => 'UNIDAD', 'grupo_id' => 'G-3'],
            ['codigo' => 'G-3/0005', 'nombre' => 'COMBO DE 12 LB', 'unidad' => 'UNIDAD', 'grupo_id' => 'G-3'],
            ['codigo' => 'G-3/0006', 'nombre' => 'STYLSON # 24', 'unidad' => 'UNIDAD', 'grupo_id' => 'G-3'],
            ['codigo' => 'G-3/0007', 'nombre' => 'STYLSON # 14', 'unidad' => 'UNIDAD', 'grupo_id' => 'G-3'],
            ['codigo' => 'G-3/0008', 'nombre' => "CIERRA MECANICA '12'", 'unidad' => 'UNIDAD', 'grupo_id' => 'G-3'],
            ['codigo' => 'G-3/0009', 'nombre' => "CURVINA '24'", 'unidad' => 'UNIDAD', 'grupo_id' => 'G-3'],
            ['codigo' => 'G-3/0010', 'nombre' => "DISCO DE DESGASTE DE '9'", 'unidad' => 'UNIDAD', 'grupo_id' => 'G-3'],
            ['codigo' => 'G-3/0011', 'nombre' => "DISCO DE DESGASTE DE '4,5'", 'unidad' => 'UNIDAD', 'grupo_id' => 'G-3'],
            ['codigo' => 'G-3/0012', 'nombre' => "DISCO DE CORTE '7'", 'unidad' => 'UNIDAD', 'grupo_id' => 'G-3'],
            ['codigo' => 'G-3/0013', 'nombre' => "DISCO DE CORTE '9'", 'unidad' => 'UNIDAD', 'grupo_id' => 'G-3'],
            ['codigo' => 'G-3/0014', 'nombre' => "DISCO DE CORTE '4,5'", 'unidad' => 'UNIDAD', 'grupo_id' => 'G-3'],
            ['codigo' => 'G-3/0015', 'nombre' => 'ELECTRODO E6013', 'unidad' => 'UNIDAD', 'grupo_id' => 'G-3'],
            ['codigo' => 'G-3/0016', 'nombre' => 'ELECTRODO E7018', 'unidad' => 'UNIDAD', 'grupo_id' => 'G-3'],
            ['codigo' => 'G-3/0017', 'nombre' => 'CABLE DE ACERO 1/2', 'unidad' => 'METROS', 'grupo_id' => 'G-3'],
            ['codigo' => 'G-3/0018', 'nombre' => 'CABLE DE ACERO 3/8', 'unidad' => 'METROS', 'grupo_id' => 'G-3'],
            ['codigo' => 'G-3/0019', 'nombre' => 'SOGA 3/4', 'unidad' => 'METROS', 'grupo_id' => 'G-3'],
            ['codigo' => 'G-3/0020', 'nombre' => 'SOGA 1/2', 'unidad' => 'METROS', 'grupo_id' => 'G-3'],
            ['codigo' => 'G-3/0021', 'nombre' => 'CEPILLO DE ACERO', 'unidad' => 'UNIDAD', 'grupo_id' => 'G-3'],
            ['codigo' => 'G-3/0022', 'nombre' => 'FLEXOMETRO DE 5mtrs', 'unidad' => 'UNIDAD', 'grupo_id' => 'G-3'],

            // === G-4: LUBRICANTES ===
            ['codigo' => 'G-4/0001', 'nombre' => 'ACEITE DE MAQUINA', 'unidad' => 'LITROS', 'grupo_id' => 'G-4'],
            ['codigo' => 'G-4/0002', 'nombre' => 'ACEITE MOTOR 15W40 DIESEL', 'unidad' => 'LITROS', 'grupo_id' => 'G-4'],
            ['codigo' => 'G-4/0003', 'nombre' => 'ACEITE TELLUS 2M / 68', 'unidad' => 'LITROS', 'grupo_id' => 'G-4'],
            ['codigo' => 'G-4/0004', 'nombre' => 'ACEITE HIDRAULICO ISO/68', 'unidad' => 'LITROS', 'grupo_id' => 'G-4'],
            ['codigo' => 'G-4/0005', 'nombre' => 'GASOLINA', 'unidad' => 'LITROS', 'grupo_id' => 'G-4'],
            ['codigo' => 'G-4/0006', 'nombre' => 'DIESEL', 'unidad' => 'LITROS', 'grupo_id' => 'G-4'],
            ['codigo' => 'G-4/0007', 'nombre' => 'GRASA DE RODAMIENTOS', 'unidad' => 'KILOS', 'grupo_id' => 'G-4'],

            // === G-5: FILTROS Y CORREAS ===
            ['codigo' => 'G-5/0001', 'nombre' => 'FILTRO DE AIRE C23610 (compresora)', 'unidad' => 'UNIDAD', 'grupo_id' => 'G-5'],
            ['codigo' => 'G-5/0002', 'nombre' => 'FILTRO DE AIRE C20500 (compresora)', 'unidad' => 'UNIDAD', 'grupo_id' => 'G-5'],
            ['codigo' => 'G-5/0003', 'nombre' => 'FILTRO DE AIRE SFA1107H (gen. Azul)', 'unidad' => 'UNIDAD', 'grupo_id' => 'G-5'],
            ['codigo' => 'G-5/0004', 'nombre' => 'FILTRO DE AIRE SFA1196H (gen. Blanco)', 'unidad' => 'UNIDAD', 'grupo_id' => 'G-5'],
            ['codigo' => 'G-5/0005', 'nombre' => 'FILTRO DE ACEITE PSL962 (compresora)', 'unidad' => 'UNIDAD', 'grupo_id' => 'G-5'],
            ['codigo' => 'G-5/0006', 'nombre' => 'FILTRO DE ACEITE 1R-0739 (pala)', 'unidad' => 'UNIDAD', 'grupo_id' => 'G-5'],
            ['codigo' => 'G-5/0007', 'nombre' => 'FILTRO DE DIESEL P551010', 'unidad' => 'UNIDAD', 'grupo_id' => 'G-5'],
            ['codigo' => 'G-5/0008', 'nombre' => 'CORREA 17x2845 B-112 (winche)', 'unidad' => 'UNIDAD', 'grupo_id' => 'G-5'],
            ['codigo' => 'G-5/0009', 'nombre' => 'CORREA Ax-32 (pala)', 'unidad' => 'UNIDAD', 'grupo_id' => 'G-5'],
            ['codigo' => 'G-5/0010', 'nombre' => 'CORREA A-52 (pala)', 'unidad' => 'UNIDAD', 'grupo_id' => 'G-5'],
            ['codigo' => 'G-5/0011', 'nombre' => 'CORREA A-72 (pala)', 'unidad' => 'UNIDAD', 'grupo_id' => 'G-5'],

            // === G-6: E.P.P. ===
            ['codigo' => 'G-6/0001', 'nombre' => "SACO IMPERMEABLE TALLA 'M'", 'unidad' => 'UNIDAD', 'grupo_id' => 'G-6'],
            ['codigo' => 'G-6/0002', 'nombre' => "SACO IMPERMEABLE TALLA 'L'", 'unidad' => 'UNIDAD', 'grupo_id' => 'G-6'],
            ['codigo' => 'G-6/0003', 'nombre' => "PANTALON IMPERMEABLE TALLA 'M'", 'unidad' => 'UNIDAD', 'grupo_id' => 'G-6'],
            ['codigo' => 'G-6/0004', 'nombre' => "PANTALON IMPERMEABLE TALLA 'L'", 'unidad' => 'UNIDAD', 'grupo_id' => 'G-6'],
            ['codigo' => 'G-6/0005', 'nombre' => "OVEROLES TALLA 'M'", 'unidad' => 'UNIDAD', 'grupo_id' => 'G-6'],
            ['codigo' => 'G-6/0006', 'nombre' => "OVEROLES TALLA 'L'", 'unidad' => 'UNIDAD', 'grupo_id' => 'G-6'],
            ['codigo' => 'G-6/0007', 'nombre' => "OVEROLES TALLA 'XL'", 'unidad' => 'UNIDAD', 'grupo_id' => 'G-6'],
            ['codigo' => 'G-6/0008', 'nombre' => 'CASCO MINERO BLANCO', 'unidad' => 'UNIDAD', 'grupo_id' => 'G-6'],
            ['codigo' => 'G-6/0009', 'nombre' => 'CASCO MINERO CAFÉ', 'unidad' => 'UNIDAD', 'grupo_id' => 'G-6'],
            ['codigo' => 'G-6/0010', 'nombre' => "BOTAS DE GOMA '38'", 'unidad' => 'UNIDAD', 'grupo_id' => 'G-6'],
            ['codigo' => 'G-6/0011', 'nombre' => "BOTAS DE GOMA '39'", 'unidad' => 'UNIDAD', 'grupo_id' => 'G-6'],
            ['codigo' => 'G-6/0012', 'nombre' => "BOTAS DE GOMA '40'", 'unidad' => 'UNIDAD', 'grupo_id' => 'G-6'],
            ['codigo' => 'G-6/0013', 'nombre' => "BOTAS DE GOMA '41'", 'unidad' => 'UNIDAD', 'grupo_id' => 'G-6'],
            ['codigo' => 'G-6/0014', 'nombre' => 'ARNES DE SEGURIDAD', 'unidad' => 'UNIDAD', 'grupo_id' => 'G-6'],
            ['codigo' => 'G-6/0015', 'nombre' => 'GUANTES CON GOMA', 'unidad' => 'UNIDAD', 'grupo_id' => 'G-6'],
            ['codigo' => 'G-6/0016', 'nombre' => 'LAMPARAS', 'unidad' => 'UNIDAD', 'grupo_id' => 'G-6'],

            // === G-7: HERRAMIENTAS DE MECANICA ===
            ['codigo' => 'G-7/0001', 'nombre' => 'ARCO DE SOLDAR CROWN', 'unidad' => 'UNIDAD', 'grupo_id' => 'G-7'],
            ['codigo' => 'G-7/0002', 'nombre' => 'CARGADOR DE BATERIAS CD-530', 'unidad' => 'UNIDAD', 'grupo_id' => 'G-7'],
            ['codigo' => 'G-7/0003', 'nombre' => 'TUBO DE OXIGENO MEDIANO', 'unidad' => 'UNIDAD', 'grupo_id' => 'G-7'],
            ['codigo' => 'G-7/0004', 'nombre' => 'WINCHE TAMAÑO PEQUEÑO', 'unidad' => 'UNIDAD', 'grupo_id' => 'G-7'],
            ['codigo' => 'G-7/0005', 'nombre' => 'WINCHE TAMAÑO GRANDE', 'unidad' => 'UNIDAD', 'grupo_id' => 'G-7'],
            ['codigo' => 'G-7/0006', 'nombre' => 'AMOLADORA TAMAÑO GRANDE', 'unidad' => 'UNIDAD', 'grupo_id' => 'G-7'],
            ['codigo' => 'G-7/0007', 'nombre' => 'AMOLADORA TAMAÑO PEQUEÑO', 'unidad' => 'UNIDAD', 'grupo_id' => 'G-7'],
            ['codigo' => 'G-7/0008', 'nombre' => 'MOTO CIERRA (ineco)', 'unidad' => 'UNIDAD', 'grupo_id' => 'G-7'],
            ['codigo' => 'G-7/0009', 'nombre' => 'SOPLETE MANUAL', 'unidad' => 'UNIDAD', 'grupo_id' => 'G-7'],
            ['codigo' => 'G-7/0010', 'nombre' => 'SOPLETE DE PINTURA', 'unidad' => 'UNIDAD', 'grupo_id' => 'G-7'],

            // === G-8: PINTURAS Y ANTICONGELANTES ===
            ['codigo' => 'G-8/0001', 'nombre' => 'ANTICONGELANTE', 'unidad' => 'LITROS', 'grupo_id' => 'G-8'],
            ['codigo' => 'G-8/0002', 'nombre' => 'THINNER 900cc', 'unidad' => 'LITROS', 'grupo_id' => 'G-8'],
            ['codigo' => 'G-8/0003', 'nombre' => 'DESENGRASANTE DE MOTOR', 'unidad' => 'LITROS', 'grupo_id' => 'G-8'],
            ['codigo' => 'G-8/0004', 'nombre' => 'MONOPOL NEGRO', 'unidad' => 'LITROS', 'grupo_id' => 'G-8'],
            ['codigo' => 'G-8/0005', 'nombre' => 'MONOPOL AMARILLO', 'unidad' => 'LITROS', 'grupo_id' => 'G-8'],
            ['codigo' => 'G-8/0006', 'nombre' => 'MONOPOL AZUL', 'unidad' => 'LITROS', 'grupo_id' => 'G-8'],
            ['codigo' => 'G-8/0007', 'nombre' => 'AEROSOL VERDE', 'unidad' => 'UNIDAD', 'grupo_id' => 'G-8'],
            ['codigo' => 'G-8/0008', 'nombre' => 'AEROSOL AZUL', 'unidad' => 'UNIDAD', 'grupo_id' => 'G-8'],
            ['codigo' => 'G-8/0009', 'nombre' => 'AEROSOL ROJO', 'unidad' => 'UNIDAD', 'grupo_id' => 'G-8'],
            ['codigo' => 'G-8/0010', 'nombre' => 'AEROSOL AMARILLO', 'unidad' => 'UNIDAD', 'grupo_id' => 'G-8'],
            ['codigo' => 'G-8/0011', 'nombre' => 'LIMPIA CONTACTO', 'unidad' => 'UNIDAD', 'grupo_id' => 'G-8'],
            ['codigo' => 'G-8/0012', 'nombre' => 'PEGATANKE', 'unidad' => 'UNIDAD', 'grupo_id' => 'G-8'],

            // === G-9: BOTIQUIN ===
            ['codigo' => 'G-9/0001', 'nombre' => 'BOTIQUIN MEDICO', 'unidad' => 'UNIDAD', 'grupo_id' => 'G-9'],
        ];

        foreach ($articulos as $art) {
            Articulo::updateOrCreate(
                ['codigo' => $art['codigo']],
                array_merge($art, ['cantidad' => 0])
            );
        }
    }
}