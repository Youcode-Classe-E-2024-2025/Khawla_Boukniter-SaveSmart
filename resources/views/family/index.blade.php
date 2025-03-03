@extends('layouts.app')

@section('content')
<div class="min-h-screen">

    <div class="container mx-auto px-4 py-8">
        <div class="mb-8 bg-gradient-to-r from-emerald-600 to-teal-500 p-8 rounded-xl text-white">
            <h1 class="text-3xl font-bold mb-2">Welcome back, {{ auth()->user()->name }}</h1>
            <p class="text-emerald-100">Here's your family financial overview</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-light text-gray-800">Budget Optimization Methods</h3>
                <span class="text-sm bg-emerald-50 text-emerald-600 px-3 py-1 rounded-full">
                    Current: {{ auth()->user()->budget_method ?? 'None' }}
                </span>
            </div>

            <form action="{{ route('family.updateBudgetMethod') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- 50/30/20 Rule -->
                    <div class="relative">
                        <input type="radio" name="budget_method" value="50-30-20" id="method-503020" class="peer hidden">
                        <label for="method-503020" class="block h-full p-6 bg-white border rounded-xl cursor-pointer transition-all peer-checked:border-emerald-500 peer-checked:ring-2 peer-checked:ring-emerald-500 hover:border-emerald-200">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="text-lg font-medium text-gray-800">50/30/20 Rule</h4>
                                <div class="w-8 h-8 rounded-full bg-emerald-50 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="space-y-3">
                                <div class="relative pt-1">
                                    <div class="flex mb-2 justify-between text-xs">
                                        <span class="text-gray-600">Needs</span>
                                        <span class="text-emerald-600 font-medium">50%</span>
                                    </div>
                                    <div class="h-2 bg-gray-100 rounded-full">
                                        <div class="h-2 bg-emerald-500 rounded-full" style="width: 50%"></div>
                                    </div>
                                </div>
                                <div class="relative pt-1">
                                    <div class="flex mb-2 justify-between text-xs">
                                        <span class="text-gray-600">Wants</span>
                                        <span class="text-blue-600 font-medium">30%</span>
                                    </div>
                                    <div class="h-2 bg-gray-100 rounded-full">
                                        <div class="h-2 bg-blue-500 rounded-full" style="width: 30%"></div>
                                    </div>
                                </div>
                                <div class="relative pt-1">
                                    <div class="flex mb-2 justify-between text-xs">
                                        <span class="text-gray-600">Savings</span>
                                        <span class="text-purple-600 font-medium">20%</span>
                                    </div>
                                    <div class="h-2 bg-gray-100 rounded-full">
                                        <div class="h-2 bg-purple-500 rounded-full" style="width: 20%"></div>
                                    </div>
                                </div>
                            </div>
                        </label>
                    </div>

                    <!-- Envelope Method -->
                    <div class="relative">
                        <input type="radio" name="budget_method" value="envelope" id="method-envelope" class="peer hidden">
                        <label for="method-envelope" class="block h-full p-6 bg-white border rounded-xl cursor-pointer transition-all peer-checked:border-blue-500 peer-checked:ring-2 peer-checked:ring-blue-500 hover:border-blue-200">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="text-lg font-medium text-gray-800">Envelope System</h4>
                                <div class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                    </svg>
                                </div>
                            </div>
                            <p class="text-sm text-gray-500 mb-3">Allocate cash to specific spending categories</p>
                            <ul class="text-sm space-y-2">
                                <li class="flex items-center text-gray-600">
                                    <svg class="w-4 h-4 mr-2 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" />
                                    </svg>
                                    Category-based budgeting
                                </li>
                                <li class="flex items-center text-gray-600">
                                    <svg class="w-4 h-4 mr-2 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" />
                                    </svg>
                                    Zero-based approach
                                </li>
                            </ul>
                        </label>
                    </div>

                    <!-- Zero-Based Method -->
                    <div class="relative">
                        <input type="radio" name="budget_method" value="zero-based" id="method-zero" class="peer hidden">
                        <label for="method-zero" class="block h-full p-6 bg-white border rounded-xl cursor-pointer transition-all peer-checked:border-purple-500 peer-checked:ring-2 peer-checked:ring-purple-500 hover:border-purple-200">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="text-lg font-medium text-gray-800">Zero-Based</h4>
                                <div class="w-8 h-8 rounded-full bg-purple-50 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            </div>
                            <p class="text-sm text-gray-500 mb-3">Every dollar has a specific purpose</p>
                            <ul class="text-sm space-y-2">
                                <li class="flex items-center text-gray-600">
                                    <svg class="w-4 h-4 mr-2 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" />
                                    </svg>
                                    Income minus expenses equals zero
                                </li>
                                <li class="flex items-center text-gray-600">
                                    <svg class="w-4 h-4 mr-2 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" />
                                    </svg>
                                    Detailed expense tracking
                                </li>
                            </ul>
                        </label>
                    </div>
                </div>

                <div class="mt-6">
                    <button type="submit" class="w-full bg-gradient-to-r from-emerald-500 to-teal-600 text-white py-3 px-4 rounded-xl font-medium hover:from-emerald-600 hover:to-teal-700 transition duration-200 shadow-sm">
                        Apply Budget Method
                    </button>
                </div>
            </form>
        </div>


        @if (Auth::user()->budget_method === '50-30-20')
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-light text-gray-800">Budget Status</h3>
                <div class="bg-emerald-50 text-emerald-700 px-3 py-1 rounded-lg text-sm">
                    {{ number_format($budgetData['totalIncome'], 2) }} MAD
                </div>
            </div>

            <div class="space-y-4">
                @foreach(['needs' => '50%', 'wants' => '30%', 'savings' => '20%'] as $category => $percentage)
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-2">
                        <div>
                            <span class="capitalize text-gray-800">{{ $category }}</span>
                            <span class="text-xs text-gray-500 ml-1">({{ $percentage }})</span>
                        </div>
                        <span class="text-sm font-medium {{ ($budgetData['actual'][$category] / $budgetData['targets'][$category]) * 100 > 100 ? 'text-red-600' : 'text-emerald-600' }}">
                            {{ number_format(($budgetData['actual'][$category] / $budgetData['targets'][$category]) * 100, 1) }}%
                        </span>
                    </div>
                    <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                        <div class="h-full transition-all duration-300 rounded-full {{ ($budgetData['actual'][$category] / $budgetData['targets'][$category]) * 100 > 100 ? 'bg-red-500' : 'bg-emerald-500' }}"
                            style="width: {{ min(($budgetData['actual'][$category] / $budgetData['targets'][$category]) * 100, 100) }}%">
                        </div>
                    </div>
                    <div class="flex justify-between mt-1 text-xs text-gray-500">
                        <span>{{ number_format($budgetData['actual'][$category], 2) }}</span>
                        <span>{{ number_format($budgetData['targets'][$category], 2) }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif






        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">

            <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-light text-gray-800">Recent Transactions</h3>
                    <a href="{{ route('transactions.index') }}" class="text-emerald-600 hover:text-emerald-700">View All</a>
                </div>

                @foreach($recentTransactions as $transaction)
                <div class="flex items-center justify-between p-4 hover:bg-gray-50 rounded-xl transition duration-150">
                    <div class="flex items-center">
                        <div class="w-10 h-10 {{ $transaction->type === 'income' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }} rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                @if($transaction->type === 'income')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12m3-2.818l-3 3-3-3" />
                                @else
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4V20M12 4L8 8M12 4L16 8" />
                                @endif
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="font-medium text-gray-900">{{ $transaction->category->name }}</h3>
                            <p class="text-gray-800">{{ $transaction->description }}</p>
                            <p class="text-sm text-gray-500">{{ $transaction->created_at->format('d M, H:i') }}</p>
                            <span class="text-sm text-emerald-600">by {{ $transaction->user->name }}</span>
                        </div>
                    </div>
                    <span class="{{ $transaction->type === 'income' ? 'text-green-600' : 'text-red-600' }}">
                        {{ $transaction->type === 'income' ? '+' : '-' }}{{ number_format($transaction->amount, 2) }} MAD
                    </span>
                </div>
                @endforeach
            </div>

            @if(auth()->user()->account_type === 'family')
            <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-light text-gray-800">Family Members</h3>
                    <span class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-full text-sm">
                        {{ $familyMembers->count() }} Members
                    </span>
                </div>

                <div class="mb-2">
                    @foreach($familyMembers as $member)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-teal-500 rounded-full flex items-center justify-center text-white font-medium">
                                {{ strtoupper(substr($member->name, 0, 2)) }}
                            </div>
                            <div class="ml-4">
                                <p class="text-gray-800 font-medium">{{ $member->name }}</p>
                                <p class="text-sm text-gray-500">{{ $member->email }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>


        @endif
    </div>
</div>
@endsection