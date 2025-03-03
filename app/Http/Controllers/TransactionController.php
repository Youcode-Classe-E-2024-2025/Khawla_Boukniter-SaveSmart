<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        $transactions = Transaction::query();

        if ($user->account_type === 'family') {
            $transactions->where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->orWhere('family_id', $user->family_id);
            });
        } else {
            $transactions->where('user_id', $user->id);
        }

        $transactions = $transactions->orderBy('created_at', 'desc')->get();

        return view('transactions.index', compact('transactions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();

        $categories = app(CategoryController::class)->getCategories();

        return view('transactions.create', ['categories' => $categories]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Log::info('Transaction Request Data:', [
            'all' => $request->all(),
            'category_id' => $request->category_id,
            'newCategory' => $request->newCategory,
            'type' => $request->type,
            'category_type' => $request->category_type
        ]);

        $rules = [
            'type' => 'required|in:income,expense',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'newCategory' => $request->category_id ? 'nullable' : 'required|string',
            'category_id' => $request->newCategory ? 'nullable' : 'required|exists:categories,id',
        ];

        if ($request->type === 'expense') {
            $rules['category_type'] = $request->newCategory ? 'required|in:needs,wants,savings' : 'nullable';
        }

        $validated = $request->validate($rules);

        if ($request->type === 'expense') {
            $user = Auth::user();

            if ($user->budget_method === '50/30/20') {
                $budgetData = Transaction::getBudgetAnalysis($user->id, $user->family_id);
                $categoryType = $request->newCategory ? $request->category_type : Category::find($request->category_id)->type;

                $remainingBudget = [
                    'needs' => $budgetData['targets']['needs'] - $budgetData['actual']['needs'],
                    'wants' => $budgetData['targets']['wants'] - $budgetData['actual']['wants'],
                    'savings' => $budgetData['targets']['savings'] - $budgetData['actual']['savings']
                ];

                if ($validated['amount'] > $remainingBudget[$categoryType]) {
                    return back()->withErrors([
                        'amount' => "Transaction exceeds your {$categoryType} budget. Maximum available: {$remainingBudget[$categoryType]} MAD"
                    ])->withInput();
                }
            } else {
                $totalIncome = Transaction::where(function ($query) use ($user) {
                    $query->where('user_id', $user->id)
                        ->orWhere('family_id', $user->family_id);
                })
                    ->where('type', 'income')
                    ->whereMonth('created_at', now()->month)
                    ->sum('amount');

                $totalExpenses = Transaction::where(function ($query) use ($user) {
                    $query->where('user_id', $user->id)
                        ->orWhere('family_id', $user->family_id);
                })
                    ->where('type', 'expense')
                    ->whereMonth('created_at', now()->month)
                    ->sum('amount');

                if (($totalExpenses + $validated['amount']) > $totalIncome) {
                    $remaining = $totalIncome - $totalExpenses;
                    return back()->withErrors([
                        'amount' => "Transaction exceeds your available income. Maximum available: {$remaining} MAD"
                    ])->withInput();
                }
            }
        }

        if ($request->newCategory) {
            $category = Category::create([
                'name' => $request->newCategory,
                'type' => $request->type === 'income' ? 'income' : $request->category_type,
                'user_id' => Auth::id(),
                'family_id' => Auth::user()->family_id
            ]);
            $categoryId = $category->id;
        } else {
            $categoryId = $request->category_id;
        }

        $transaction = Transaction::create([
            'user_id' => Auth::id(),
            'family_id' => Auth::user()->family_id,
            'type' => $validated['type'],
            'category_id' => $categoryId,
            'amount' => $validated['amount'],
            'description' => $validated['description']
        ]);

        return redirect()->route('transactions.index')->with('success', 'Transaction added');
    }





    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        $categories = app(CategoryController::class)->getCategories();

        return view('transactions.edit', ['transaction' => $transaction, 'categories' => $categories]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaction $transaction)
    {
        $validated = $request->validate([
            'type' => 'required|in:income,expense',
            'category_id' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $transaction->update($validated);

        return redirect()->route('transactions.index')->with('success', 'transaction updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        $transaction->delete();

        return redirect()->route('transactions.index')->with('success', ('transaction deleted'));
    }
}
