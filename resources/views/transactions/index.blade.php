@extends('layouts.app')

@section('content')

<div class="container mx-auto px-4 py-8">
    <div class="mb-8 bg-gradient-to-r from-emerald-600 to-teal-500 p-8 rounded-xl text-white">
        <h2 class="text-3xl font-bold mb-2">Transactions</h2>
        <p class="text-emerald-100">Manage your financial activities</p>
    </div>

    <div class="flex justify-between items-center mb-8">

        <a href="{{ route('transactions.create') }}"
            class="inline-flex ml-auto items-center px-4 py-2 bg-emerald-500 text-white rounded-lg hover:bg-emerald-600 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Add Transaction
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm divide-y divide-gray-100 mx-10">
        @foreach($transactions as $transaction)
        <div class="p-6 hover:bg-gray-50 transition-colors">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center {{ $transaction->type === 'income' ? 'bg-emerald-100 text-emerald-600' : 'bg-red-100 text-red-600' }}">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            @if($transaction->type === 'income')
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12m3-2.818l-3 3-3-3" />
                            @else
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4V20M12 4L8 8M12 4L16 8" />
                            @endif
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-medium text-gray-900">{{ $transaction->category->name }}</h3>
                        <p class="text-sm text-gray-500">Type: {{ ucfirst($transaction->category->type) }}</p>
                        <p class="text-sm text-gray-500">{{ $transaction->description }}</p>
                    </div>
                </div>

                <div class="flex items-center space-x-8">
                    <div class="text-right">
                        <p class="text-lg font-semibold {{ $transaction->type === 'income' ? 'text-emerald-600' : 'text-red-600' }}">
                            {{ $transaction->type === 'income' ? '+' : '-' }}{{ number_format($transaction->amount, 2) }} MAD
                        </p>
                        <p class="text-sm text-gray-500">{{ $transaction->created_at->format('M d, Y') }}</p>
                        <span class="text-sm text-emerald-600">by {{ $transaction->user->name }}</span>
                    </div>

                    <div class="flex items-center space-x-3">
                        <a href="{{ route('transactions.edit', $transaction) }}"
                            class="p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                            </svg>
                        </a>
                        <form action="{{ route('transactions.destroy', $transaction) }}" method="POST" class="delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="p-2 text-red-600 hover:text-red-900 hover:bg-red-50 rounded-lg transition-colors delete-btn">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </form>
                    </div>
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
        const deleteBtns = document.querySelectorAll('.delete-btn');

        if (deleteBtns.length > 0) {
            deleteBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    if (confirm('Are you sure you want to delete this transaction?')) {
                        this.closest('form').submit();
                    }
                });
            });
        }
    });
</script>
@endsection