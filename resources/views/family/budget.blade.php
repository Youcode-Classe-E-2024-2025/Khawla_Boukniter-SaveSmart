@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
            <h2 class="text-2xl font-light text-gray-800 mb-6">50/30/20 Budget Analysis</h2>

            <div class="mb-8">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-gray-600">Total Monthly Income</span>
                    <span class="text-2xl font-semibold text-gray-800">{{ number_format($income, 2) }} MAD</span>
                </div>
            </div>

            <!-- Needs Section -->
            <div class="mb-6">
                <div class="flex justify-between items-center mb-2">
                    <h3 class="text-lg font-medium">Needs (50%)</h3>
                    <div class="text-right">
                        <div class="text-sm text-gray-500">Target: {{ number_format($budget['needs'], 2) }} MAD</div>
                        <div class="text-sm {{ $spending['needs'] <= $budget['needs'] ? 'text-emerald-600' : 'text-red-600' }}">
                            Actual: {{ number_format($spending['needs'], 2) }} MAD
                        </div>
                    </div>
                </div>
                <div class="h-2 bg-gray-200 rounded-full">
                </div>
            </div>

            <!-- Wants Section -->
            <div class="mb-6">
                <div class="flex justify-between items-center mb-2">
                    <h3 class="text-lg font-medium">Wants (30%)</h3>
                    <div class="text-right">
                        <div class="text-sm text-gray-500">Target: {{ number_format($budget['wants'], 2) }} MAD</div>
                        <div class="text-sm {{ $spending['wants'] <= $budget['wants'] ? 'text-emerald-600' : 'text-red-600' }}">
                            Actual: {{ number_format($spending['wants'], 2) }} MAD
                        </div>
                    </div>
                </div>
                <div class="h-2 bg-gray-200 rounded-full">
                </div>
            </div>

            <!-- Savings Section -->
            <div class="mb-6">
                <div class="flex justify-between items-center mb-2">
                    <h3 class="text-lg font-medium">Savings (20%)</h3>
                    <div class="text-right">
                        <div class="text-sm text-gray-500">Target: {{ number_format($budget['savings'], 2) }} MAD</div>
                        <div class="text-sm {{ $spending['savings'] <= $budget['savings'] ? 'text-emerald-600' : 'text-red-600' }}">
                            Actual: {{ number_format($spending['savings'], 2) }} MAD
                        </div>
                    </div>
                </div>
                <div class="h-2 bg-gray-200 rounded-full">
                </div>
            </div>
        </div>
    </div>
</div>
@endsection