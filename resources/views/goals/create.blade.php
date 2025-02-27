@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <h2 class="text-2xl font-light text-gray-800 mb-6">Create New Goal</h2>

        <form action="{{ route('goals.store') }}" method="POST" class="bg-white rounded-xl shadow-sm p-6">
            @csrf
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Goal Name</label>
                    <input type="text" name="name" required
                        class="w-full rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Target Amount</label>
                        <input type="number" name="target_amount" step="0.01" required
                            class="w-full rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                        <select name="category" required
                            class="w-full rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                            <option value="Savings">Savings</option>
                            <option value="Investment">Investment</option>
                            <option value="Debt Reduction">Debt Reduction</option>
                            <option value="Emergency Fund">Emergency Fund</option>
                            <option value="Major Purchase">Major Purchase</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Target Date</label>
                    <input type="date" name="target_date" required
                        class="w-full rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="description" rows="3"
                        class="w-full rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500"></textarea>
                </div>

                <button type="submit"
                    class="w-full bg-emerald-500 text-white py-2 px-4 rounded-lg hover:bg-emerald-600">
                    Create Goal
                </button>
            </div>
        </form>
    </div>
</div>
@endsection