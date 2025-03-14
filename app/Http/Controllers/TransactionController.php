<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Category;
use App\Models\Goal;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Response;

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
        $transaction = new Transaction();
        $activeGoals = Goal::getActiveGoals($user->id, $user->family_id);

        return view('transactions.create', ['categories' => $categories, 'transaction' => $transaction, 'activeGoals' => $activeGoals]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $validated = $this->validateTransactionRequest($request);

        if ($request->type === 'expense') {
            $categoryType = $this->getCategoryType($request);
            $budgetCheck = $this->checkBudgetLimits($user, $validated['amount'], $categoryType);

            if ($budgetCheck !== true) {
                return back()->withErrors(['amount' => $budgetCheck])->withInput();
            }
        }

        $categoryId = $this->handleCategory($request);

        $transaction = Transaction::create([
            'user_id' => $user->id,
            'family_id' => $user->family_id,
            'type' => $validated['type'],
            'category_id' => $categoryId,
            'amount' => $validated['amount'],
            'description' => $validated['description'],
            'goal_id' => $request->goal_contribution ? $request->goal_id : null,
            'goal_contribution' => $request->goal_contribution ? true : false,
        ]);

        if ($transaction->goal_contribution) {
            $transaction->goal->updateProgress();
        }

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
        $activeGoals = Goal::getActiveGoals(Auth::id(), Auth::user()->family_id);

        return view('transactions.edit', ['transaction' => $transaction, 'categories' => $categories, 'activeGoals' => $activeGoals]);
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
            'goal_id' => 'nullable|exists:goals,id',
            'goal_contribution' => 'boolean'
        ]);

        $oldGoal = $transaction->goaol;

        $transaction->update($validated);

        if ($oldGoal) {
            $oldGoal->updateProgress();
        }

        if ($transaction->goal) {
            $transaction->goal->updateProgress();
        }

        return redirect()->route('transactions.index')->with('success', 'transaction updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        $goal = $transaction->goal;

        $transaction->delete();

        if ($goal) {
            $goal->updateProgress();
        }

        return redirect()->route('transactions.index')->with('success', ('transaction deleted'));
    }

    private function validateTransactionRequest(Request $request)
    {
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

        return $request->validate($rules);
    }

    private function getCategoryType(Request $request)
    {
        return $request->newCategory ?
            $request->category_type :
            Category::find($request->category_id)->type;
    }

    private function checkBudgetLimits($user, $amount, $categoryType)
    {
        $totalIncome = Transaction::calculateMonthlyIncome($user->id, $user->family_id);
        $basicBudget = Transaction::applyFiftyThirtyTwenty($totalIncome);
        $currentSpending = Transaction::calculateMonthlySpending($user->id, $user->family_id);

        if ($user->budget_method === '50-30-20') {
            $categoryLimit = $basicBudget[$categoryType];
            $currentAmount = $currentSpending[$categoryType];
            $remainingBudget = $categoryLimit - $currentAmount;

            if ($amount > $remainingBudget) {
                return "This transaction would exceed your {$categoryType} budget limit. Available: {$remainingBudget} MAD (Limit: {$categoryLimit} MAD)";
            }
        } else {
            $totalExpenses = array_sum($currentSpending);
            if (($totalExpenses + $amount) > $totalIncome) {
                $remaining = $totalIncome - $totalExpenses;
                return "Transaction exceeds your available income. Maximum available: {$remaining} MAD";
            }
        }

        return true;
    }

    private function handleCategory(Request $request)
    {
        if ($request->newCategory) {
            $category = Category::create([
                'name' => $request->newCategory,
                'type' => $request->type === 'income' ? 'income' : $request->category_type,
                'user_id' => Auth::id(),
                'family_id' => Auth::user()->family_id
            ]);
            return $category->id;
        }

        return $request->category_id;
    }

    public function exportPDF()
    {
        $user = Auth::user();
        $transactions = Transaction::query()
            ->where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->orWhere('family_id', $user->family_id);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $data = [
            'transactions' => $transactions,
            'total_income' => $transactions->where('type', 'income')->sum('amount'),
            'total_expense' => $transactions->where('type', 'expense')->sum('amount')
        ];

        $pdf = PDF::loadView('transactions.transaction-pdf', $data);
        return $pdf->download('transactions.pdf');
    }

    public function exportCSV()
    {
        $user = Auth::user();
        $transactions = Transaction::query()
            ->where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->orWhere('family_id', $user->family_id);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=transactions.csv',
        ];

        $callback = function () use ($transactions) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Date', 'Type', 'Category', 'Amount', 'Description', 'User']);

            foreach ($transactions as $transaction) {
                fputcsv($file, [
                    $transaction->created_at->format('Y-m-d'),
                    $transaction->type,
                    $transaction->category->name,
                    $transaction->amount,
                    $transaction->description,
                    $transaction->user->name
                ]);
            }
            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }
}
