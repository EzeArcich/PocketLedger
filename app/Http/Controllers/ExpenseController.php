<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExpenseRequest;
use App\Http\Requests\UpdateExpenseRequest;
use App\Models\Expense;
use App\Services\ExpenseService;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function __construct(private readonly ExpenseService $expenseService)
    {
    }

    public function index()
    {
        $this->authorize('viewAny', Expense::class);

        //orden descendente, alternativa a orderBy
        $query = Expense::query()->with('user') ->latest('spent_at');

        // Filtro simple (admin ve todo, user ve lo suyo)
        if (! auth()->user()->hasRole('admin')) {
            $query->where('user_id', auth()->id());
        }

        $expenses = $query->paginate(12);
        // $expenses = $query->get();

        return view('expenses.index', compact('expenses'));
    }

    public function create()
    {
        $this->authorize('create', Expense::class);

        return view('expenses.create');
    }


    public function store(StoreExpenseRequest $request)
    {
        $this->expenseService->createExpense($request->validated(), $request->user());

        return redirect()->route('expenses.index')->with('status', 'Expense created successfully!');;
    }

    public function edit(Expense $expense)
    {
        $this->authorize('update', $expense);

        return view('expenses.edit', compact('expense'));
    }


    public function update(UpdateExpenseRequest $request, Expense $expense)
    {
        $this->expenseService->updateExpense($expense, $request->validated());

        return redirect()->route('expenses.index')->with('status', 'Expense updated successfully!');
    }

    // public function destroy(Expense $expense)
    // {
    //     $this->authorize('delete', $expense);

    //     $this->expenseService->deleteExpense($expense);

    //     return redirect()->route('expenses.index')->with('status', 'Gasto eliminado.');
    // }

    public function destroy(Expense $expense)
    {
        $this->authorize('delete', $expense);

        $this->expenseService->deleteExpense($expense);

        if (request()->expectsJson()) {
            return response()->json(['ok' => true]);
        }

        return redirect()->route('expenses.index')->with('success', 'Eliminado');
    }

}
