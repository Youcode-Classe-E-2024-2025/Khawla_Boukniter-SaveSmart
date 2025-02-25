@extends('layouts.app')

@section('content')


<div class="container mx-auto px-4 py-12">
    <div class="max-w-5xl mx-auto">
        <div class="bg-white rounded-2xl shadow-sm p-8 mb-8 border border-gray-100">
            <div class="flex items-center space-x-8">
                <div class="h-28 w-28 bg-gradient-to-br from-emerald-400 to-teal-500 rounded-2xl flex items-center justify-center text-white text-3xl font-light shadow-inner">
                    KB
                </div>
                <div>
                    <h1 class="text-3xl font-light text-gray-800 mb-1">Khawla Boukniter</h1>
                    <p class="text-gray-500 font-light">Personal Account</p>
                    <div class="flex items-center mt-2">
                        <svg class="w-4 h-4 text-emerald-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z" />
                        </svg>
                        <span class="text-emerald-600 text-sm">Active since January 2024</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-8">
                <div class="bg-white rounded-2xl shadow-sm p-8 border border-gray-100">
                    <h2 class="text-2xl font-light text-gray-800 mb-6">Personal Information</h2>
                    <form class="space-y-6">
                        <div class="grid grid-cols-2 gap-6">
                            <div class="col-span-2 sm:col-span-1">
                                <label class="text-sm text-gray-600 mb-2 block">First Name</label>
                                <input type="text" value="Khawla"
                                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-100 focus:border-emerald-400 transition duration-200">
                            </div>
                            <div class="col-span-2 sm:col-span-1">
                                <label class="text-sm text-gray-600 mb-2 block">Last Name</label>
                                <input type="text" value="Boukniter"
                                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-100 focus:border-emerald-400 transition duration-200">
                            </div>
                            <div class="col-span-2">
                                <label class="text-sm text-gray-600 mb-2 block">Email Address</label>
                                <input type="email" value="khawla@example.com"
                                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-100 focus:border-emerald-400 transition duration-200">
                            </div>
                        </div>
                    </form>
                </div>

                <div class="bg-white rounded-2xl shadow-sm p-8 border border-gray-100">
                    <h2 class="text-2xl font-light text-gray-800 mb-6">Financial Settings</h2>
                    <div class="space-y-6">
                        <div>
                            <label class="text-sm text-gray-600 mb-2 block">Budget Method</label>
                            <select class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-100 focus:border-emerald-400 transition duration-200">
                                <option>50/30/20 Rule (Besoins 50% / Envies 30% / Épargne 20%)</option>
                                <option>Zero-Based - Every Dirham Has a Job</option>
                                <option>Envelope System - Cash Management</option>
                                <option>Custom Budget Distribution</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600 mb-2 block">Account Type</label>
                            <select class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-100 focus:border-emerald-400 transition duration-200">
                                <option>Personal Account</option>
                                <option>Family Account</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-8">
                <div class="bg-white rounded-2xl shadow-sm p-8 border border-gray-100">
                    <h2 class="text-2xl font-light text-gray-800 mb-6">Overview</h2>
                    <div class="space-y-4">
                        <div class="p-4 bg-gradient-to-br from-emerald-50 to-teal-50 rounded-xl border border-emerald-100">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-emerald-600">Monthly Savings</span>
                                <span class="text-2xl font-semibold text-emerald-700">2,500 MAD</span>
                            </div>
                        </div>
                        <div class="p-4 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl border border-blue-100">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-blue-600">Active Goals</span>
                                <span class="text-2xl font-semibold text-blue-700">3</span>
                            </div>
                        </div>
                        <div class="p-4 bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl border border-purple-100">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-purple-600">Family Members</span>
                                <span class="text-2xl font-semibold text-purple-700">4</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm p-8 border border-gray-100">
                    <button class="w-full bg-gradient-to-r from-emerald-500 to-teal-600 text-white rounded-xl px-6 py-3 font-medium hover:from-emerald-600 hover:to-teal-700 transition duration-200 shadow-sm">
                        Save Changes
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection