@extends('layouts.app')

@section('content')

<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h2 class="text-2xl font-light text-gray-800">Financial Analytics</h2>
        <p class="text-gray-600">Track your spending patterns and financial trends</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Monthly Income vs Expenses Chart -->
        <div class="bg-white p-6 rounded-xl shadow-sm">
            <h3 class="text-lg font-medium mb-4">Monthly Overview</h3>
            <canvas id="monthlyChart"></canvas>
        </div>

        <!-- Category Distribution Chart -->
        <div class="bg-white p-6 rounded-xl shadow-sm">
            <h3 class="text-lg font-medium mb-4">Expense Categories</h3>
            <canvas id="categoryChart"></canvas>
        </div>
    </div>
</div>

@endsection

@section('scripts')

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
        const categoryCtx = document.getElementById('categoryChart').getContext('2d');

        new Chart(monthlyCtx, {
            type: 'bar',
            data: {
                labels: {
                    !!json_encode($monthlyStats - > pluck('month')) !!
                },
                datasets: [{
                    label: 'Income',
                    data: {
                        !!json_encode($monthlyStats - > where('type', 'income') - > pluck('total')) !!
                    },
                    backgroundColor: '#10B981'
                }, {
                    label: 'Expenses',
                    data: {
                        !!json_encode($monthlyStats - > where('type', 'expense') - > pluck('total')) !!
                    },
                    backgroundColor: '#EF4444'
                }]
            }
        });

        new Chart(categoryCtx, {
            type: 'doughnut',
            data: {
                labels: {
                    !!json_encode($categoryStats - > pluck('category')) !!
                },
                datasets: [{
                    data: {
                        !!json_encode($categoryStats - > pluck('total')) !!
                    },
                    backgroundColor: ['#10B981', '#3B82F6', '#8B5CF6', '#EC4899', '#F59E0B']
                }]
            }
        });
    });
</script>



@endsection