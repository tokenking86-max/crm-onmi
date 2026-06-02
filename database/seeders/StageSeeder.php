<?php

namespace Database\Seeders;

use App\Models\Stage;
use Illuminate\Database\Seeder;

class StageSeeder extends Seeder
{
    public function run(): void
    {
        $stages = [
            ['name' => 'Prospecto', 'order' => 1, 'color' => '#94a3b8'],
            ['name' => 'Contactado', 'order' => 2, 'color' => '#60a5fa'],
            ['name' => 'Calificado', 'order' => 3, 'color' => '#a78bfa'],
            ['name' => 'Propuesta', 'order' => 4, 'color' => '#fbbf24'],
            ['name' => 'Negociación', 'order' => 5, 'color' => '#fb923c'],
            ['name' => 'Ganado', 'order' => 6, 'color' => '#34d399', 'is_won' => true],
            ['name' => 'Perdido', 'order' => 7, 'color' => '#f87171', 'is_lost' => true],
        ];

        foreach ($stages as $stage) {
            Stage::updateOrCreate(
                ['order' => $stage['order']],
                $stage
            );
        }
    }
}
