@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
            <h2 class="text-2xl font-light text-gray-800 mb-6">Set Up Family Account</h2>

            @if(session('invitation_code'))
            <div class="mb-6 p-4 bg-emerald-50 rounded-xl border border-emerald-200">
                <h3 class="text-emerald-800 font-medium mb-2">Family Invitation Code</h3>
                <div class="bg-white p-3 rounded-lg border border-emerald-100 text-center">
                    <span class="text-2xl font-mono text-emerald-600">{{ session('invitation_code') }}</span>
                </div>
                <p class="mt-2 text-sm text-emerald-600">Share this code with family members to join your account</p>
            </div>
            @endif

            <form action="{{ route('family.store') }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label class="text-sm text-gray-600 mb-2 block">Family Name</label>
                    <input type="text" name="family_name" required
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-100 focus:border-emerald-400">
                </div>

                <button type="submit"
                    class="w-full bg-gradient-to-r from-emerald-500 to-teal-600 text-white rounded-xl px-6 py-3">
                    Create Family Account
                </button>
            </form>
        </div>
    </div>
</div>


@endsection