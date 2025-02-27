@extends('layouts.app')

@section('content')

<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <h2 class="text-2xl font-light text-gray-800 mb-6">Edit Goal</h2>

        <form action="{{ route('goals.update', $goal) }}" method="POST" class="bg-white rounded-xl shadow-sm p-6">
            @csrf
            @method('PUT')
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Goal Name</label>
                    <input type="text" name="name" value="{{ $goal->name }}" required
                        class="w-full rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Target Amount</label>
                        <input type="number" name="target_amount" value="{{ $goal->target_amount }}" step="0.01" required
                            class="w-full rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Current Amount</label>
                        <input type="number" name="current_amount" value="{{ $goal->current_amount }}" step="0.01" required
                            class="w-full rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                    <select name="category" required
                        class="w-full rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                        <option value="Savings" {{ $goal->category === 'Savings' ? 'selected' : '' }}>Savings</option>
                        <option value="Investment" {{ $goal->category === 'Investment' ? 'selected' : '' }}>Investment</option>
                        <option value="Debt Reduction" {{ $goal->category === 'Debt Reduction' ? 'selected' : '' }}>Debt Reduction</option>
                        <option value="Emergency Fund" {{ $goal->category === 'Emergency Fund' ? 'selected' : '' }}>Emergency Fund</option>
                        <option value="Major Purchase" {{ $goal->category === 'Major Purchase' ? 'selected' : '' }}>Major Purchase</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Target Date</label>
                    <input type="date" name="target_date" value="{{ $goal->target_date->format('Y-m-d') }}" required
                        class="w-full rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="description" rows="3"
                        class="w-full rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">{{ $goal->description }}</textarea>
                </div>

                <div class="flex justify-end space-x-4">
                    <a href="{{ route('goals.index') }}"
                        class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">
                        Cancel
                    </a>
                    <button type="submit"
                        class="px-4 py-2 bg-emerald-500 text-white rounded-lg hover:bg-emerald-600">
                        Update Goal
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection