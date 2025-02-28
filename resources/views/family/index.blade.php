@extends('layouts.app')

@section('content')
<div class="min-h-screen">

    <div class="container mx-auto px-4 py-8">
        <div class="mb-8 bg-gradient-to-r from-emerald-600 to-teal-500 p-8 rounded-xl text-white">
            <h1 class="text-3xl font-bold mb-2">Welcome back, {{ auth()->user()->name }}</h1>
            <p class="text-emerald-100">Here's your family financial overview</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">

            <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-light text-gray-800">Recent Transactions</h3>
                    <a href="{{ route('transactions.index') }}" class="text-emerald-600 hover:text-emerald-700">View All</a>
                </div>

                @foreach($recentTransactions as $transaction)
                <div class="flex items-center justify-between p-4 hover:bg-gray-50 rounded-xl transition duration-150">
                    <div class="flex items-center">
                        <div class="w-10 h-10 {{ $transaction->type === 'income' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }} rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                @if($transaction->type === 'income')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12m3-2.818l-3 3-3-3" />
                                @else
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4V20M12 4L8 8M12 4L16 8" />
                                @endif
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="font-medium text-gray-900">{{ $transaction->category }}</h3>
                            <p class="text-gray-800">{{ $transaction->description }}</p>
                            <p class="text-sm text-gray-500">{{ $transaction->created_at->format('d M, H:i') }}</p>
                            <span class="text-sm text-emerald-600">by {{ $transaction->user->name }}</span>
                        </div>
                    </div>
                    <span class="{{ $transaction->type === 'income' ? 'text-green-600' : 'text-red-600' }}">
                        {{ $transaction->type === 'income' ? '+' : '-' }}{{ number_format($transaction->amount, 2) }} MAD
                    </span>
                </div>
                @endforeach
            </div>

            @if(auth()->user()->account_type === 'family')
            <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-light text-gray-800">Family Members</h3>
                    <span class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-full text-sm">
                        {{ $familyMembers->count() }} Members
                    </span>
                </div>

                <div class="mb-2">
                    @foreach($familyMembers as $member)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-teal-500 rounded-full flex items-center justify-center text-white font-medium">
                                {{ strtoupper(substr($member->name, 0, 2)) }}
                            </div>
                            <div class="ml-4">
                                <p class="text-gray-800 font-medium">{{ $member->name }}</p>
                                <p class="text-sm text-gray-500">{{ $member->email }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>


        @endif
    </div>
</div>
@endsection