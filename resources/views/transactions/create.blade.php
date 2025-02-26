@extends('layouts.app')

@section('content')

<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <h2 class="text-2xl font-light text-gray-800 mb-6">Add Transaction</h2>

        <form action="{{ route('transactions.store') }}" method="POST" class="bg-white rounded-xl shadow-sm p-6">
            @csrf
            <div class="space-y-6">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                        <select name="type" class="w-full rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                            <option value="income">Income</option>
                            <option value="expense">Expense</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                        <select name="category" class="w-full rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                            <option value="salary">Salary</option>
                            <option value="investment">Investment</option>
                            <option value="rent">Rent</option>
                            <option value="utilities">Utilities</option>
                            <option value="groceries">Groceries</option>
                            <option value="transport">Transport</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Amount</label>
                    <input type="number" name="amount" step="0.01" class="w-full rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="description" rows="3" class="w-full rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date</label>
                    <input type="date" name="date" class="w-full rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                </div>

                <button type="submit" class="w-full bg-emerald-500 text-white py-2 px-4 rounded-lg hover:bg-emerald-600">
                    Add Transaction
                </button>
            </div>
        </form>
    </div>
</div>

@endsection