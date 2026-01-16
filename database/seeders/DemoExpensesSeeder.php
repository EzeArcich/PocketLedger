<?php

namespace Database\Seeders;

use App\Models\Expense;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DemoExpensesSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::whereIn('email', [
            'admin@pocketledger.test',
            'user@pocketledger.test',
        ])->get();

        if ($users->isEmpty()) {
            $this->command?->warn('No se encontraron usuarios demo. Corré UsersTableSeeder primero.');
            return;
        }

        $startMonth = Carbon::create(2025, 8, 1)->startOfMonth(); // Agosto 2025
        $endMonth   = Carbon::create(2026, 1, 1)->endOfMonth();   // Enero 2026

        // Idempotente: borrar gastos demo del rango para estos usuarios
        Expense::whereIn('user_id', $users->pluck('id'))
            ->whereBetween('spent_at', [$startMonth->toDateString(), $endMonth->toDateString()])
            ->delete();

        $templates = [
            'Supermercado',
            'Combustible',
            'Comida',
            'Café',
            'Farmacia',
            'Transporte',
            'Streaming',
            'Servicios',
            'Ropa',
            'Mantenimiento',
            'Regalo',
            'Otros',
        ];

        foreach ($users as $user) {
            // Recorremos meses: 2025-08 .. 2026-01
            $cursor = $startMonth->copy();

            while ($cursor->lte($endMonth)) {
                $monthStart = $cursor->copy()->startOfMonth();
                $monthEnd   = $cursor->copy()->endOfMonth();

                $count = random_int(10, 20);

                for ($i = 0; $i < $count; $i++) {
                    $spentAt = Carbon::createFromTimestamp(
                        random_int($monthStart->timestamp, $monthEnd->timestamp)
                    )->toDateString();

                    $label = $templates[array_rand($templates)];

                    // Monto con variedad, tirando a valores “reales”
                    $amount = match ($label) {
                        'Combustible' => random_int(8000, 40000),
                        'Supermercado' => random_int(5000, 35000),
                        'Servicios' => random_int(6000, 30000),
                        default => random_int(800, 25000),
                    };

                    Expense::create([
                        'user_id' => $user->id,
                        'amount' => number_format($amount / 100, 2, '.', ''), // ej 123.45
                        'spent_at' => $spentAt,
                        'description' => $label . ' #' . random_int(1, 999),
                    ]);
                }

                $cursor->addMonthNoOverflow();
            }
        }

        $this->command?->info('DemoExpensesSeeder: gastos generados para Agosto 2025 a Enero 2026.');
    }
}
