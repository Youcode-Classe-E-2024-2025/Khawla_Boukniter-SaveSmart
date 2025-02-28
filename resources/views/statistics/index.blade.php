@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8 bg-gradient-to-r from-emerald-600 to-teal-500 p-8 rounded-xl text-white">
        <h2 class="text-3xl font-bold mb-2">Financial Analytics Dashboard</h2>
        <p class="text-emerald-100">Get insights into your financial patterns and make informed decisions</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="bg-white p-8 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-gray-800">Monthly Overview</h3>
            </div>
            <div class="h-[400px]">
                <canvas id="monthlyChart"></canvas>
            </div>
        </div>

        <div class="bg-white p-8 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-gray-800">Expense Categories</h3>
                <span class="text-sm text-emerald-600 bg-emerald-100 px-3 py-1 rounded-full">Current Month</span>
            </div>
            <div class="h-[400px]">
                <canvas id="categoryChart"></canvas>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
        const categoryCtx = document.getElementById('categoryChart').getContext('2d');
        const categoryLabels = JSON.parse('{{ json_encode($categoryStats->pluck("category")) }}'.replace(/&quot;/g, '"'));

        function generateUniqueColors(count) {
            const colors = [];
            for (let i = 0; i < count; i++) {
                const hue = (i * (360 / count)) % 360;
                const color = `hsla(${hue}, 70%, 50%, 0.7)`;
                colors.push(color);
            }
            return colors;
        }

        new Chart(monthlyCtx, {
            type: 'bar',
            data: {
                labels: JSON.parse('{{ json_encode($monthlyStats->pluck("month")) }}'.replace(/&quot;/g, '"')),
                datasets: [{
                    label: 'Income',
                    data: JSON.parse('{{ json_encode($monthlyStats->where("type", "income")->pluck("total")) }}'.replace(/&quot;/g, '"')),
                    backgroundColor: 'rgba(16, 185, 129, 0.8)',
                    borderRadius: 6
                }, {
                    label: 'Expenses',
                    data: JSON.parse('{{ json_encode($monthlyStats->where("type", "expense")->pluck("total")) }}'.replace(/&quot;/g, '"')),
                    backgroundColor: 'rgba(239, 68, 68, 0.8)',
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            padding: 20,
                            font: {
                                size: 12
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            display: true,
                            drawBorder: false
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        new Chart(categoryCtx, {
            type: 'doughnut',
            data: {
                labels: JSON.parse('{{ json_encode($categoryStats->pluck("category")) }}'.replace(/&quot;/g, '"')),
                datasets: [{
                    data: JSON.parse('{{ json_encode($categoryStats->pluck("total")) }}'.replace(/&quot;/g, '"')),
                    backgroundColor: generateUniqueColors(categoryLabels.length),
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            padding: 20,
                            font: {
                                size: 12
                            }
                        }
                    }
                },
                cutout: '70%'
            }
        });
    });
</script>

@endsection