@extends('layouts.app')

@section('content')

<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-light text-gray-800">Transactions</h2>
        <a href="{{ route('transactions.create') }}"
            class="bg-emerald-500 text-white px-4 py-2 rounded-lg hover:bg-emerald-600">
            Add Transaction
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm">
        @foreach($transactions as $transaction)
        <div class="flex items-center justify-between p-4 border-b">
            <div class="flex items-center">
                <div class="w-10 h-10 rounded-full flex items-center justify-center {{ $transaction->type === 'income' ? 'bg-emerald-100 text-emerald-600' : 'bg-red-100 text-red-600' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        @if($transaction->type === 'income')
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12m3-2.818l-3 3-3-3" />
                        @else
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12m3 2.818l-3-3-3 3" />
                        @endif
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="font-medium">{{ $transaction->category }}</p>
                    <p class="text-sm text-gray-500">{{ $transaction->description }}</p>
                </div>
            </div>
            <div class="text-right">
                <p class="font-medium {{ $transaction->type === 'income' ? 'text-emerald-600' : 'text-red-600' }}">
                    {{ $transaction->type === 'income' ? '+' : '-' }}{{ number_format($transaction->amount, 2) }}
                </p>
                <p class="text-sm text-gray-500">{{ $transaction->created_at->format('M d, Y') }}</p>
            </div>
        </div>
        @endforeach
    </div>
</div>

@endsection