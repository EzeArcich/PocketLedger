<?php

namespace App\Services;

use App\Models\Expense;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ExpenseService
{
    public function createExpense(array $data, User $user): Expense
    {
        return DB::transaction(function () use ($data, $user) {
            // No confiamos en user_id del request
            $data['user_id'] = $user->id;

            $expense = Expense::create($data);

            return $expense;
        });
    }

    public function updateExpense(Expense $expense, array $data): Expense
    {
        return DB::transaction(function () use ($expense, $data) {
            // Seguridad: nunca permitir cambiar el owner por request
            unset($data['user_id']);

            $expense->fill($data);
            $expense->save();

            return $expense->refresh();
        });
    }

    public function deleteExpense(Expense $expense): void
    {
        DB::transaction(function () use ($expense) {
            $expense->delete();
        });
    }

    public function monthlySummary(User $user, Carbon $month): array
    {

        $start = $month->copy()->startOfMonth()->toDateString();
        $end   = $month->copy()->endOfMonth()->toDateString();

        $baseQuery = Expense::query()
            ->whereBetween('spent_at', [$start, $end]);

        // Admin ve todo, usuario ve lo suyo
        if (! $user->hasRole('admin')) {
            $baseQuery->where('user_id', $user->id);
        }

        $total = (clone $baseQuery)->sum('amount');
        $count = (clone $baseQuery)->count();

        // Si la tabla tiene category_id, esto arma el breakdown
        // $byCategory = (clone $baseQuery)
        //     ->selectRaw('category_id, SUM(amount) as total')
        //     ->groupBy('category_id')
        //     ->orderByDesc('total')
        //     ->get()
        //     ->map(fn ($row) => [
        //         'category_id' => $row->category_id,
        //         'total' => (float) $row->total,
        //     ])
        //     ->all();

        return [
            'month' => $month->format('Y-m'),
            'range' => ['start' => $start, 'end' => $end],
            'total' => (float) $total,
            'count' => (int) $count,
            'baseQuery' => $baseQuery,
            // 'by_category' => $byCategory,
        ];
    }
}
