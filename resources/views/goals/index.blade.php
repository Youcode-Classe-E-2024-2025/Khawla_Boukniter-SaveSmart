@extends('layouts.app')

@section('content')

<div class="container mx-auto px-4 py-8">
    <div class="mb-8 bg-gradient-to-r from-emerald-600 to-teal-500 p-8 rounded-xl text-white">
        <h2 class="text-3xl font-bold mb-2">Financial Goals</h2>
        <p class="text-emerald-100">Track your savings targets and progress</p>
    </div>

    <div class="flex items-center mb-8">

        <a href="{{ route('goals.create') }}"
            class="inline-flex ml-auto items-center px-4 py-2 bg-emerald-500 text-white rounded-lg hover:bg-emerald-600 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Add New Goal
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($goals as $goal)
        <div class="bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition-shadow">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h3 class="text-lg font-medium text-gray-900">{{ $goal->name }}</h3>
                    <p class="text-sm text-gray-500">{{ $goal->description }}</p>
                </div>
                <span class="px-3 py-1 rounded-full text-sm font-medium
                    {{ $goal->category === 'Savings' ? 'bg-blue-100 text-blue-700' : 
                      ($goal->category === 'Investment' ? 'bg-purple-100 text-purple-700' : 
                      'bg-amber-100 text-amber-700') }}">
                    {{ $goal->category }}
                </span>
            </div>

            <div class="space-y-4 mb-6">
                <div>
                    <div class="flex justify-between text-sm mb-2">
                        <span class="text-gray-600">Progress</span>
                        <span class="font-medium">{{ number_format(($goal->current_amount / $goal->target_amount) * 100) }}%</span>
                    </div>
                    <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                        <div class="h-full bg-emerald-500 rounded-full"
                            style="width: {{ ($goal->current_amount / $goal->target_amount) * 100 }}%">
                        </div>
                        <span>{{ number_format(($goal->current_amount / $goal->target_amount) * 100, 1) }}% Complete</span>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-4 text-sm">
                    <div>
                        <span class="text-gray-600">Current</span>
                        <p class="font-medium text-gray-900">{{ number_format($goal->current_amount, 2) }} MAD</p>
                    </div>
                    <div>
                        <span class="text-gray-600">Remaining</span>
                        <p class="font-medium text-gray-900">{{ number_format($goal->target_amount - $goal->current_amount, 2) }} MAD</p>
                    </div>
                    <div>
                        <span class="text-gray-600">Target</span>
                        <p class="font-medium text-gray-900">{{ number_format($goal->target_amount, 2) }} MAD</p>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                <div class="text-sm text-gray-600">
                    <p>Due: {{ $goal->target_date->format('M d, Y') }}</p>
                </div>
                <span class="text-sm text-emerald-600">by {{ $goal->user->name }}</span>

                <div class="flex space-x-2">
                    <a href="{{ route('goals.edit', $goal) }}"
                        class="p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                    </a>
                    <form action="{{ route('goals.destroy', $goal) }}" method="POST" class="delete-form">
                        @csrf
                        @method('DELETE')
                        <button type="button"
                            class="p-2 text-red-600 hover:text-red-900 hover:bg-red-50 rounded-lg transition-colors delete-btn">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

@endsection

@section('scripts')

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteForms = document.querySelectorAll('.delete-form');

        deleteForms.forEach(form => {
            form.querySelector('.delete-btn').addEventListener('click', function(e) {
                if (confirm('Are you sure you want to delete this goal')) {
                    form.submit();
                }
            })
        })
    })
</script>

@endsection