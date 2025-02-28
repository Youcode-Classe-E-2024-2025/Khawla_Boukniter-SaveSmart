@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="max-w-5xl mx-auto">
        <div class="bg-white rounded-2xl shadow-sm p-8 mb-8 border border-gray-100">
            <div class="flex items-center space-x-8">
                <div class="h-28 w-28 bg-gradient-to-br from-emerald-400 to-teal-500 rounded-2xl flex items-center justify-center text-white text-3xl font-light shadow-inner">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
                <div>
                    <h1 class="text-3xl font-light text-gray-800 mb-1">{{ auth()->user()->name }}</h1>
                    <p class="text-gray-500 font-light">{{ auth()->user()->account_type === 'personal' ? 'Personal Account' : 'Family Account' }}</p>
                    <div class="flex items-center mt-2">
                        <svg class="w-4 h-4 text-emerald-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z" />
                        </svg>
                        <span class="text-emerald-600 text-sm">Active since {{ auth()->user()->created_at->format('F Y') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <form action="{{ route('profile.update') }}" method="POST" class="space-y-8">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 space-y-8">
                    <div class="bg-white rounded-2xl shadow-sm p-8 border border-gray-100">
                        <h2 class="text-2xl font-light text-gray-800 mb-6">Personal Information</h2>
                        <form action="{{ route('profile.update') }}" method="POST" class="space-y-6">
                            @csrf
                            @method('PUT')
                            <div class="grid grid-cols-2 gap-6">
                                <div class="col-span-2 sm:col-span-1">
                                    <label class="text-sm text-gray-600 mb-2 block">Full Name</label>
                                    <input type="text" name="name" value="{{ auth()->user()->name }}"
                                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-100 focus:border-emerald-400 transition duration-200">
                                </div>
                                <div class="col-span-2 sm:col-span-1">
                                    <label class="text-sm text-gray-600 mb-2 block">Email</label>
                                    <input type="email" value="{{ auth()->user()->email }}" disabled
                                        class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50">
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="bg-white rounded-2xl shadow-sm p-8 border border-gray-100">
                        <h2 class="text-2xl font-light text-gray-800 mb-6">Financial Settings</h2>
                        <div class="space-y-6">
                            <div>
                                <label class="text-sm text-gray-600 mb-2 block">Budget Method</label>
                                <select name="budget_method" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-100 focus:border-emerald-400 transition duration-200">
                                    <option {{ auth()->user()->budget_method === '50-30-20' ? 'selected' : '' }}>50/30/20 Rule</option>
                                    <option {{ auth()->user()->budget_method === 'zero-based' ? 'selected' : '' }}>Zero-Based</option>
                                    <option {{ auth()->user()->budget_method === 'envelope' ? 'selected' : '' }}>Envelope System</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-8">
                    <div class="bg-white rounded-2xl shadow-sm p-8 border border-gray-100">
                        <h2 class="text-2xl font-light text-gray-800 mb-6">Overview</h2>
                        <div class="space-y-4">
                            <div class="p-4 bg-gradient-to-br from-emerald-50 to-teal-50 rounded-xl border border-emerald-100">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-emerald-600">Total Transactions</span>
                                    <span class="text-2xl font-semibold text-emerald-700">{{ $totalTransactions }}</span>
                                </div>
                            </div>
                            <div class="p-4 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl border border-blue-100">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-blue-600">Active Goals</span>
                                    <span class="text-2xl font-semibold text-blue-700">{{ $activeGoals }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-sm p-8 border border-gray-100">
                        <button type="submit" class="w-full bg-gradient-to-r from-emerald-500 to-teal-600 text-white rounded-xl px-6 py-3 font-medium hover:from-emerald-600 hover:to-teal-700 transition duration-200 shadow-sm">
                            Save Changes
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection