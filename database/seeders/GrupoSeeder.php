<?php

namespace Database\Seeders;

use App\Models\Grupo;
use Illuminate\Database\Seeder;

class GrupoSeeder extends Seeder
{
    public function run(): void
    {
        $grupos = [
            ['id' => 'G-1', 'nombre' => 'MATERIAL EXPLOSIVO'],
            ['id' => 'G-2', 'nombre' => 'ACCESORIOS'],
            ['id' => 'G-3', 'nombre' => 'HERRAMIENTAS'],
            ['id' => 'G-4', 'nombre' => 'LUBRICANTES'],
            ['id' => 'G-5', 'nombre' => 'FILTROS Y CORREAS'],
            ['id' => 'G-6', 'nombre' => 'E.P.P.'],
            ['id' => 'G-7', 'nombre' => 'HERRAMIENTAS DE MECANICA'],
            ['id' => 'G-8', 'nombre' => 'PINTURAS Y ANTICONGELANTES'],
            ['id' => 'G-9', 'nombre' => 'BOTIQUIN'],
        ];

        foreach ($grupos as $g) {
            Grupo::updateOrCreate(['id' => $g['id']], $g);
        }
    }
}