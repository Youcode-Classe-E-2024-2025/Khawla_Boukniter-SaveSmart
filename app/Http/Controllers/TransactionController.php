<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

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
        $validated = $request->validate([
            'type' => 'required|in:income,expense',
            'category' => 'required_without:newCategory|string',
            'newCategory' => 'required_without:category|string',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $category = $request->category ?: $request->newCategory;

        $transactions = Transaction::create([
            'user_id' => Auth::id(),
            'family_id' => Auth::user()->family_id,
            'type' => $validated['type'],
            'category' => $category,
            'amount' => $validated['amount'],
            'description' => $validated['description'],
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
            'category' => 'required|string',
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
