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
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-light text-gray-800">Budget Method</h3>
                <span class="text-sm bg-emerald-50 text-emerald-600 px-3 py-1 rounded-full">
                    Current: {{ auth()->user()->account_type === 'family' ? $family->getBudgetMethod() : auth()->user()->budget_method ?? 'Not Set' }}
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
                    'intelligent-allocation' => [
                    'title' => 'Intelligent Allocation',
                    'description' => 'Allocate cash dynamically',
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
                            {{ (auth()->user()->account_type === 'family' ? $family->budget_method : auth()->user()->budget_method) === $method ? 'checked' : '' }}>
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

        <div class="bg-white rounded-2xl shadow-sm p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-light text-gray-800">Budget Progress</h3>
                <span class="text-sm bg-emerald-50 text-emerald-600 px-3 py-1 rounded-full">
                    Monthly Overview
                </span>
            </div>

            <!-- Budget Distribution -->
            <div class="space-y-6">
                @foreach(['needs', 'wants', 'savings'] as $category)
                <div class="space-y-2">
                    <div class="flex justify-between items-center">
                        <span class="capitalize text-gray-700">{{ $category }}</span>
                        <div class="text-right">
                            <span class="text-gray-900 font-medium">
                                {{ number_format($optimizedBudget['actual'][$category], 2) }} / {{ number_format($optimizedBudget['targets'][$category], 2) }} MAD
                            </span>
                            @if(isset($optimizedBudget['alerts'][$category]))
                            <div class="text-sm text-red-500 mt-1">
                                {{ $optimizedBudget['alerts'][$category] }}
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full {{ $optimizedBudget['actual'][$category] > $optimizedBudget['targets'][$category] ? 'bg-red-500' : 'bg-emerald-500' }} rounded-full"
                            style="width: {{ min(($optimizedBudget['actual'][$category] / $optimizedBudget['targets'][$category]) * 100, 100) }}%">
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Adjustments Summary -->
            @if(!empty($optimizedBudget['alerts']))
            <div class="mt-6 p-4 bg-yellow-50 rounded-lg">
                <h4 class="font-medium text-yellow-800 mb-2">Budget Adjustments Required</h4>
                <ul class="list-disc list-inside text-sm text-yellow-700">
                    @foreach($optimizedBudget['alerts'] as $alert)
                    <li>{{ $alert }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>


        <div class="grid grid-cols-1 md:grid-cols-1 gap-6 mb-8">
            @if (Auth::user()->budget_method === '50-30-20')
            <div class="bg-white rounded-2xl shadow-sm p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-light text-gray-800">Basic 50/30/20 Split</h3>
                    <span class="text-sm bg-emerald-50 text-emerald-600 px-3 py-1 rounded-full">
                        Standard Method
                    </span>
                </div>

                <div class="space-y-4">
                    @foreach(['needs', 'wants', 'savings'] as $category)
                    <div class="bg-gray-50 rounded-xl p-4">
                        <div class="flex justify-between items-center mb-2">
                            <span class="capitalize font-medium text-gray-700">{{ $category }}</span>
                            <span class="text-emerald-600 font-medium">
                                {{ number_format($basicBudget[$category], 2) }} MAD
                            </span>
                        </div>
                        <div class="relative pt-1">
                            @php
                            $currentSpending = $optimizedBudget['actual'][$category];
                            $limit = $basicBudget[$category];
                            $percentage = $limit > 0 ? min(($currentSpending / $limit) * 100, 100) : 0;
                            @endphp
                            <div class="flex mb-2 items-center justify-between">
                                <span class="text-sm font-medium {{ $percentage > 100 ? 'text-red-600' : 'text-emerald-600' }}">
                                    {{ number_format($percentage, 1) }}%
                                </span>
                                <span class="text-sm text-gray-600">
                                    {{ number_format($currentSpending, 2) }} / {{ number_format($limit, 2) }} MAD
                                </span>
                            </div>
                            <div class="overflow-hidden h-2 text-xs flex rounded bg-emerald-100">
                                <div class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center {{ $percentage > 100 ? 'bg-red-500' : 'bg-emerald-500' }}"
                                    style="width: {{ $percentage }}%">
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @elseif (Auth::user()->budget_method === 'intelligent-allocation')
            <div class="bg-white rounded-2xl shadow-sm p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-light text-gray-800">Smart Optimized Budget</h3>
                    <span class="text-sm bg-emerald-50 text-emerald-600 px-3 py-1 rounded-full">
                        Intelligent Allocation
                    </span>
                </div>

                <div class="space-y-4 px-4">
                    @foreach(['needs', 'wants', 'savings'] as $category)
                    <div class="bg-gray-50 rounded-xl p-3 px-6">
                        <div class="flex justify-between items-center">
                            <span class="capitalize font-medium text-gray-700">{{ $category }}</span>
                            <span class="text-emerald-600 font-medium">
                                {{ number_format($optimizedBudget['actual'][$category], 2) }} MAD
                            </span>
                        </div>
                        <div class="relative pt-1">
                            <div class="flex mb-2 items-center justify-between">
                                <div>
                                    <span class="text-sm font-medium {{ ($optimizedBudget['targets'][$category] > 0 ? ($optimizedBudget['actual'][$category] / $optimizedBudget['targets'][$category]) * 100 : 0) > 100 ? 'text-red-600' : 'text-emerald-600' }}">
                                        {{ number_format($optimizedBudget['targets'][$category] > 0 ? ($optimizedBudget['actual'][$category] / $optimizedBudget['targets'][$category]) * 100 : 0, 1) }}%
                                    </span>
                                </div>
                            </div>
                            <div class="overflow-hidden h-2 text-xs flex rounded bg-emerald-100">
                                <div class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-emerald-500"
                                    style="width: {{ $optimizedBudget['targets'][$category] > 0 ? min(($optimizedBudget['actual'][$category] / $optimizedBudget['targets'][$category]) * 100, 100) : 0 }}%">
                                </div>
                            </div>
                            <div class="mt-2 text-sm text-gray-500">
                                Target: {{ number_format($optimizedBudget['targets'][$category], 2) }} MAD
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif



        </div>




        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            @if(auth()->user()->account_type === 'family')
            <div class="bg-white rounded-2xl shadow-sm p-6">
                <div class="flex flex-col space-y-4">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-xl font-light text-gray-800">Family Members</h3>
                            <p class="text-sm text-gray-500">Manage your family account</p>
                        </div>
                        <span class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-full text-sm">
                            {{ $familyMembers->count() }} Members
                        </span>
                    </div>

                    @if($family && auth()->user()->id === $family->owner_id)
                    <div class="bg-gradient-to-r from-emerald-50 to-teal-50 rounded-xl p-4">
                        <div class="flex justify-between items-center">
                            <div>
                                <h4 class="font-medium text-gray-700">Family Invitation Code</h4>
                                <p class="text-sm text-gray-500">Share this code with family members</p>
                            </div>
                            <div class="flex items-center space-x-2">
                                <code class="px-4 py-2 bg-white rounded-lg border shadow-sm font-mono text-emerald-600" id="inviteCode">
                                    {{ $family->invitation_code }}
                                </code>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="space-y-3 mt-4">
                        @foreach($familyMembers as $member)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-all transform hover:scale-[1.01]">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-teal-500 rounded-full flex items-center justify-center text-white font-medium shadow-sm">
                                    {{ strtoupper(substr($member->name, 0, 2)) }}
                                </div>
                                <div>
                                    <p class="text-gray-800 font-medium">{{ $member->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $member->email }}</p>
                                </div>
                            </div>
                            @if($member->id === $family->owner_id)
                            <span class="px-3 py-1 bg-emerald-100 text-emerald-600 rounded-full text-xs font-medium">
                                Owner
                            </span>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif


            <div class="bg-white rounded-2xl shadow-sm p-6">
                <div class="flex justify-between items-center mb-2">
                    <h3 class="text-xl font-light text-gray-800">Financial Insights</h3>
                    <span class="text-sm bg-emerald-50 text-emerald-600 px-3 py-1 rounded-full">
                        Monthly Overview
                    </span>
                </div>

                <div class="p-4 rounded-lg mb-3">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-gray-600">Monthly Savings Rate</span>
                        <span class="text-lg font-medium {{ $insights['savings_rate'] >= 20 ? 'text-emerald-600' : 'text-orange-500' }}">
                            {{ number_format($insights['savings_rate'], 1) }}%
                        </span>
                    </div>
                    <div class="h-2 bg-gray-100 rounded-full">
                        <div class="h-2 bg-emerald-500 rounded-full" style="width: {{ min($insights['savings_rate'], 100) }}%"></div>
                    </div>
                </div>

                @if($insights['goals_progress']->isNotEmpty())
                <div class="mt-6">
                    <h4 class="text-xl font-light text-gray-800">Goals Progress</h4>
                    @foreach($insights['goals_progress'] as $goal)
                    <div class="p-4 rounded-lg mb-3">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-gray-600">{{ $goal->name }}</span>
                            <span class="text-sm text-gray-500">
                                {{ number_format(($goal->current_amount / $goal->target_amount) * 100, 1) }}%
                            </span>
                        </div>
                        <div class="h-2 bg-gray-200 rounded-full">
                            <div class="h-2 bg-emerald-500 rounded-full"
                                style="width: {{ ($goal->current_amount / $goal->target_amount) * 100 }}%">
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif

            </div>


        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div class="bg-white rounded-2xl shadow-sm p-6">

                <div class="space-y-4">
                    <h4 class="text-xl font-light text-gray-800 mb-6">Spending by Category</h4>
                    @foreach($insights['category_breakdown'] as $category)
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                        <span class="capitalize">{{ $category->name }}</span>
                        <span class="font-medium">{{ number_format($category->total, 2) }} MAD</span>
                    </div>
                    @endforeach
                </div>

            </div>

            <div class="bg-white rounded-2xl shadow-sm p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-light text-gray-800">Spending Trends</h3>
                    <span class="text-sm bg-emerald-50 text-emerald-600 px-3 py-1 rounded-full">
                        Month-to-Month Analysis
                    </span>
                </div>

                <div class="space-y-6">
                    @foreach(['needs', 'wants', 'savings'] as $category)
                    <div class="bg-gray-50 rounded-xl p-4">
                        <div class="flex justify-between items-center mb-4">
                            <span class="capitalize font-medium text-gray-700">{{ $category }}</span>
                            <span class="text-sm {{ $spendingTrends['changes'][$category] > 0 ? 'text-red-600' : 'text-emerald-600' }}">
                                {{ $spendingTrends['changes'][$category] > 0 ? '↑' : '↓' }}
                                {{ number_format(abs($spendingTrends['changes'][$category]), 2) }} MAD
                            </span>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-white rounded-lg p-3">
                                <div class="text-sm text-gray-500">Last Month</div>
                                <div class="text-lg font-medium">
                                    {{ number_format($spendingTrends['last_month'][$category], 2) }} MAD
                                </div>
                            </div>
                            <div class="bg-white rounded-lg p-3">
                                <div class="text-sm text-gray-500">Current Month</div>
                                <div class="text-lg font-medium">
                                    {{ number_format($spendingTrends['current_month'][$category], 2) }} MAD
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>


            </div>

        </div>


    </div>
</div>
@endsection