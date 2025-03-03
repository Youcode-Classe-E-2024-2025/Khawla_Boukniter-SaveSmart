@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Needs (50%) -->
        <div class="bg-white p-6 rounded-xl shadow">
            <h3 class="text-lg font-medium mb-4">Needs (50%)</h3>
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between text-sm mb-2">
                        <span>Target: {{ number_format($budgetData['targets']['needs'], 2) }} MAD</span>
                        <span>Actual: {{ number_format($budgetData['actual']['needs'], 2) }} MAD</span>
                    </div>
                    <div class="h-2 bg-gray-200 rounded-full">
                        <div class="h-2 bg-emerald-500 rounded-full"
                            style="width: {{ min(($budgetData['actual']['needs'] / $budgetData['targets']['needs']) * 100, 100) }}%">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow">
            <h3 class="text-lg font-medium mb-4">Wants (30%)</h3>
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between text-sm mb-2">
                        <span>Target: {{ number_format($budgetData['targets']['needs'], 2) }} MAD</span>
                        <span>Actual: {{ number_format($budgetData['actual']['needs'], 2) }} MAD</span>
                    </div>
                    <div class="h-2 bg-gray-200 rounded-full">
                        <div class="h-2 bg-emerald-500 rounded-full"
                            style="width: {{ min(($budgetData['actual']['needs'] / $budgetData['targets']['needs']) * 100, 100) }}%">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow">
            <h3 class="text-lg font-medium mb-4">Savings (20%)</h3>
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between text-sm mb-2">
                        <span>Target: {{ number_format($budgetData['targets']['needs'], 2) }} MAD</span>
                        <span>Actual: {{ number_format($budgetData['actual']['needs'], 2) }} MAD</span>
                    </div>
                    <div class="h-2 bg-gray-200 rounded-full">
                        <div class="h-2 bg-emerald-500 rounded-full"
                            style="width: {{ min(($budgetData['actual']['needs'] / $budgetData['targets']['needs']) * 100, 100) }}%">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection