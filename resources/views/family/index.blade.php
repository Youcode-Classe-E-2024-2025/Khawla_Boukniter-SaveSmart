@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="container mx-auto px-4 py-8">
        <div class="mb-8 bg-gradient-to-r from-emerald-600 to-teal-500 p-8 rounded-xl text-white">
            <h1 class="text-3xl font-bold mb-2">Welcome back, {{ auth()->user()->name }}</h1>
            <p class="text-emerald-100">Here's your family financial overview</p>

            <div class="flex space-x-4 mt-6">
                <a href="{{ route('transactions.create') }}" class="flex items-center px-4 py-2 bg-white text-emerald-600 rounded-lg hover:bg-emerald-50 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Add Transaction
                </a>
                <a href="{{ route('goals.create') }}" class="flex items-center px-4 py-2 bg-white text-emerald-600 rounded-lg hover:bg-emerald-50 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    Set New Goal
                </a>
            </div>
        </div>

        <div class="mb-8 bg-white rounded-2xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-light text-gray-800">Smart Budget Insights</h3>
                <span class="text-sm bg-emerald-50 text-emerald-600 px-3 py-1 rounded-full">
                    Monthly Overview
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach(['needs', 'wants', 'savings'] as $category)
                <div class="bg-gray-50 rounded-xl p-4">
                    <div class="flex justify-between items-center mb-2">
                        <span class="capitalize font-medium text-gray-700">{{ $category }}</span>
                        <span class="text-emerald-600 font-medium">
                            {{ number_format($budgetData['actual'][$category], 2) }} MAD
                        </span>
                    </div>
                    <div class="relative pt-1">
                        <div class="flex mb-2 items-center justify-between">
                            <div>
                                <span class="text-sm font-medium {{ ($budgetData['targets'][$category] > 0 ? ($budgetData['actual'][$category] / $budgetData['targets'][$category]) * 100 : 0) > 100 ? 'text-red-600' : 'text-emerald-600' }}">
                                    {{ number_format($budgetData['targets'][$category] > 0 ? ($budgetData['actual'][$category] / $budgetData['targets'][$category]) * 100 : 0, 1) }}%
                                </span>
                            </div>
                        </div>
                        <div class="overflow-hidden h-2 text-xs flex rounded bg-emerald-100">
                            <div class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-emerald-500"
                                style="width: {{ $budgetData['targets'][$category] > 0 ? min(($budgetData['actual'][$category] / $budgetData['targets'][$category]) * 100, 100) : 0 }}%">
                            </div>

                        </div>
                    </div>
                    <div class="mt-2 text-sm text-gray-500">
                        Target: {{ number_format($budgetData['targets'][$category], 2) }} MAD
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="mb-8 bg-white rounded-2xl shadow-sm p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-light text-gray-800">Budget Method</h3>
                <span class="text-sm bg-emerald-50 text-emerald-600 px-3 py-1 rounded-full">
                    Current: {{ auth()->user()->budget_method ?? 'Not Set' }}
                </span>
            </div>

            <form action="{{ route('family.updateBudgetMethod') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach([
                    '50-30-20' => [
                    'title' => '50/30/20 Rule',
                    'description' => 'Balance needs, wants, and savings',
                    'color' => 'emerald'
                    ],
                    'envelope' => [
                    'title' => 'Envelope System',
                    'description' => 'Allocate cash to specific categories',
                    'color' => 'blue'
                    ],
                    'zero-based' => [
                    'title' => 'Zero-Based',
                    'description' => 'Every dollar has a purpose',
                    'color' => 'purple'
                    ]
                    ] as $method => $details)
                    <div class="relative">
                        <input type="radio" name="budget_method" value="{{ $method }}"
                            id="method-{{ $method }}" class="peer hidden"
                            {{ auth()->user()->budget_method === $method ? 'checked' : '' }}>
                        <label for="method-{{ $method }}"
                            class="block h-full p-6 bg-white border rounded-xl cursor-pointer
                                          transition-all peer-checked:border-{{ $details['color'] }}-500 
                                          peer-checked:ring-2 peer-checked:ring-{{ $details['color'] }}-500 
                                          hover:border-{{ $details['color'] }}-200">
                            <h4 class="text-lg font-medium text-gray-800">{{ $details['title'] }}</h4>
                            <p class="text-sm text-gray-500 mt-2">{{ $details['description'] }}</p>
                        </label>
                    </div>
                    @endforeach
                </div>

                <div class="mt-6">
                    <button type="submit"
                        class="w-full bg-gradient-to-r from-emerald-500 to-teal-600 text-white 
                                   py-3 px-4 rounded-xl font-medium hover:from-emerald-600 
                                   hover:to-teal-700 transition duration-200 shadow-sm">
                        Apply Budget Method
                    </button>
                </div>
            </form>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white rounded-2xl shadow-sm p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-light text-gray-800">Recent Transactions</h3>
                    <a href="{{ route('transactions.index') }}"
                        class="text-emerald-600 hover:text-emerald-700">View All</a>
                </div>

                <div class="space-y-4">
                    @foreach($recentTransactions as $transaction)
                    <div class="group hover:bg-gray-50 p-4 rounded-xl transition-all">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="w-10 h-10 {{ $transaction->type === 'income' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }} 
                                                rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        @if($transaction->type === 'income')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6v12m3-2.818l-3 3-3-3" />
                                        @else
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m0-16l-4 4m4-4l4 4" />
                                        @endif
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $transaction->category->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $transaction->description }}</p>
                                    <div class="flex items-center space-x-2 text-xs text-gray-400">
                                        <span>{{ $transaction->created_at->format('d M, H:i') }}</span>
                                        <span>•</span>
                                        <span>{{ $transaction->user->name }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex flex-col items-end">
                                <span class="{{ $transaction->type === 'income' ? 'text-green-600' : 'text-red-600' }} font-medium">
                                    {{ $transaction->type === 'income' ? '+' : '-' }}{{ number_format($transaction->amount, 2) }} MAD
                                </span>
                                <div class="opacity-0 group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('transactions.edit', $transaction) }}"
                                        class="text-sm text-blue-500">Edit</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            @if(auth()->user()->account_type === 'family')
            <div class="bg-white rounded-2xl shadow-sm p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-light text-gray-800">Family Members</h3>
                    <span class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-full text-sm">
                        {{ $familyMembers->count() }} Members
                    </span>
                </div>

                <div class="space-y-4">
                    @foreach($familyMembers as $member)
                    <div class="flex items-center justify-between p-4 bg-gray-50 
                                      rounded-xl hover:bg-gray-100 transition-colors">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-teal-500 
                                                rounded-full flex items-center justify-center text-white font-medium">
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
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Add any additional JavaScript for charts or interactivity
</script>
@endsection