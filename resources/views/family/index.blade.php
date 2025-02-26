@extends('layouts.app')

@section('content')

<div class="min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <div class="mb-8">
            <h1 class="text-3xl font-light text-gray-800">Welcome back, {{ auth()->user()->name }}</h1>
            <p class="text-gray-600">Here's your financial overview</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-600">Total Balance</h3>
                    <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <p class="text-3xl font-semibold text-gray-800">15,000 MAD</p>
                <p class="text-sm text-emerald-600 mt-2">+2.5% from last month</p>
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-600">Monthly Savings</h3>
                    <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                </div>
                <p class="text-3xl font-semibold text-gray-800">2,500 MAD</p>
                <p class="text-sm text-blue-600 mt-2">50% of monthly goal</p>
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-600">Budget Status</h3>
                    <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
                <p class="text-3xl font-semibold text-gray-800">On Track</p>
                <p class="text-sm text-purple-600 mt-2">Following 50/30/20 rule</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                <h3 class="text-xl font-light text-gray-800 mb-6">Budget Distribution</h3>
                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between text-sm mb-2">
                            <span class="text-gray-600">Needs (50%)</span>
                            <span class="text-gray-800">7,500 MAD</span>
                        </div>
                        <div class="h-2 bg-gray-200 rounded-full">
                            <div class="h-2 bg-emerald-500 rounded-full" style="width: 50%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-sm mb-2">
                            <span class="text-gray-600">Wants (30%)</span>
                            <span class="text-gray-800">4,500 MAD</span>
                        </div>
                        <div class="h-2 bg-gray-200 rounded-full">
                            <div class="h-2 bg-blue-500 rounded-full" style="width: 30%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-sm mb-2">
                            <span class="text-gray-600">Savings (20%)</span>
                            <span class="text-gray-800">3,000 MAD</span>
                        </div>
                        <div class="h-2 bg-gray-200 rounded-full">
                            <div class="h-2 bg-purple-500 rounded-full" style="width: 20%"></div>
                        </div>
                    </div>
                </div>
            </div>

            @if(auth()->user()->account_type === 'family')
            <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-light text-gray-800">Family Members</h3>
                </div>
                <div class="space-y-4">

                </div>
            </div>
            @endif
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-light text-gray-800">Recent Transactions</h3>
                <a href="#" class="text-emerald-600 hover:text-emerald-700">View All</a>
            </div>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-4 hover:bg-gray-50 rounded-xl transition duration-150">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center text-red-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-gray-800">Grocery Shopping</p>
                            <p class="text-sm text-gray-500">Today, 14:30</p>
                        </div>
                    </div>
                    <span class="text-red-600">-450 MAD</span>
                </div>

                <div class="flex items-center justify-between p-4 hover:bg-gray-50 rounded-xl transition duration-150">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center text-green-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-gray-800">Salary Deposit</p>
                            <p class="text-sm text-gray-500">Yesterday</p>
                        </div>
                    </div>
                    <span class="text-green-600">+15,000 MAD</span>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection