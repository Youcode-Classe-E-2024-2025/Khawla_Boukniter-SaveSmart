<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use Illuminate\Support\Facades\Log;

class GoalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        $goals = Goal::query();

        if ($user->account_type === 'family') {
            $goals->where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->orWhere('family_id', $user->family_id);
            });
        } else {
            $goals->where('user_id', $user->id);
        }

        $goals = $goals->orderBy('created_at', 'desc')->get();

        return view('goals.index', compact('goals'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();

        $categories = app(CategoryController::class)->getCategories();

        return view('goals.create', ['categories' => $categories]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Log::info('Goal creation attempt', [
            'request' => $request->all(),
            'category' => $request->category
        ]);

        $validated = $request->validate([
            'name' => 'required|string',
            'target_amount' => 'required|numeric|min:0',
            'category' => 'required|string',
            'target_date' => 'required|date|after:today',
            'description' => 'nullable|string',
        ]);

        Log::info('Validated goal data', ['validated' => $validated]);

        $goal = Goal::create([
            ...$validated,
            'user_id' => Auth::id(),
            'family_id' => Auth::user()->family_id,
            'current_amount' => 0
        ]);

        Log::info('Goal created', ['goal' => $goal->toArray()]);

        return redirect()->route('goals.index')->with('success', 'Goal created');
    }

    /**
     * Display the specified resource.
     */
    public function show(Goal $goal)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Goal $goal)
    {
        $categories = app(CategoryController::class)->getCategories();

        return view('goals.edit', ['goal' => $goal]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Goal $goal)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'target_amount' => 'required|numeric|min:0',
            'current_amount' => 'required|numeric|min:0',
            'category' => 'required|string',
            'target_date' => 'required|date|after:today',
            'description' => 'nullable|string',
        ]);

        $goal->update($validated);

        return redirect()->route('goals.index')->with('success', 'goal updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Goal $goal)
    {
        $goal->delete();

        return redirect()->route('goals.index')->with('success', 'goal deleted');
    }
}
