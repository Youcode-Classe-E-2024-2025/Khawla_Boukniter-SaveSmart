<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SaveSmart - Personal Finance Management</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="flex flex-col min-h-screen">
    <nav class="bg-gradient-to-r from-emerald-600 to-teal-500 border-gray-200 dark:bg-gray-900">
        <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
            <a href="#" class="flex items-center space-x-3 rtl:space-x-reverse">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="self-center text-2xl font-semibold whitespace-nowrap text-white">SaveSmart</span>
            </a>

            <div class="hidden w-full md:block md:w-auto">
                <ul class="font-medium flex flex-col p-4 md:p-0 mt-4 md:flex-row md:space-x-8 rtl:space-x-reverse">
                    @auth
                    <li><a href="{{ Auth::user()->account_type === 'family' ? route('family.index') : route('auth.profile') }}" class="text-white hover:text-emerald-200">Dashboard</a></li>
                    @else
                    <a href="{{ route('login') }}" class="text-white hover:text-emerald-200">
                        Dashboard
                    </a>
                    @endauth
                    <li><a href="{{ route('transactions.index') }}" class="text-white hover:text-emerald-200">Transactions</a></li>
                    <li><a href="{{ route('goals.index') }}" class="text-white hover:text-emerald-200">Budget Goals</a></li>
                    <li><a href="{{ route('statistics.index') }}" class="text-white hover:text-emerald-200">Reports</a></li>
                </ul>
            </div>

            <div class="flex items-center space-x-4">

                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-4 py-2 text-center">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    @if(session('success'))
    <div class="bg-emerald-100 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg mb-4 mx-15 mt-4">
        {{ session('success') }}
        @if(session('invitation_code'))
        <div class="mt-2 bg-white p-3 rounded-md border border-emerald-200">
            <p class="text-sm text-emerald-600">Family Invitation Code:</p>
            <p class="font-mono text-lg">{{ session('invitation_code') }}</p>
        </div>
        @endif
    </div>
    @endif

    @if(session('error') || $errors->any())
    <div class="bg-red-100 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4 mx-4 mt-4">
        {{ session('error') }}
        @foreach($errors->all() as $error)
        <div>{{ $error }}</div>
        @endforeach
    </div>
    @endif

    <main class="flex-grow">
        @yield('content')
    </main>

    <footer class="bg-gradient-to-r from-emerald-600 to-teal-500">
        <div class="mx-auto w-full max-w-screen-xl p-4 py-6 lg:py-8">
            <div class="md:flex md:justify-between">
                <div class="mb-6 md:mb-0">
                    <a href="#" class="flex items-center space-x-3">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="self-center text-2xl font-semibold whitespace-nowrap text-white">SaveSmart</span>
                    </a>
                </div>
                <div class="grid grid-cols-2 gap-8 sm:gap-6 sm:grid-cols-3">
                    <div>
                        <h2 class="mb-6 text-sm font-semibold uppercase text-white">Resources</h2>
                        <ul class="text-gray-100">
                            <li class="mb-4"><a href="#" class="hover:text-emerald-200">Financial Tips</a></li>
                            <li><a href="#" class="hover:text-emerald-200">Budget Templates</a></li>
                        </ul>
                    </div>
                    <div>
                        <h2 class="mb-6 text-sm font-semibold uppercase text-white">Legal</h2>
                        <ul class="text-gray-100">
                            <li class="mb-4"><a href="#" class="hover:text-emerald-200">Privacy Policy</a></li>
                            <li><a href="#" class="hover:text-emerald-200">Terms &amp; Conditions</a></li>
                        </ul>
                    </div>
                    <div>
                        <h2 class="mb-6 text-sm font-semibold uppercase text-white">Contact</h2>
                        <ul class="text-gray-100">
                            <li class="mb-4"><a href="#" class="hover:text-emerald-200">Support</a></li>
                            <li><a href="#" class="hover:text-emerald-200">Contact Us</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <hr class="my-6 border-gray-200 sm:mx-auto lg:my-8" />
            <div class="sm:flex sm:items-center sm:justify-between">
                <span class="text-sm text-gray-100 sm:text-center">© 2024 SaveSmart™. All Rights Reserved.</span>
                <div class="flex mt-4 space-x-5 sm:justify-center sm:mt-0">
                    <a href="#" class="text-gray-100 hover:text-emerald-200">
                        <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 8 19">
                            <path d="M6.135 3H8V0H6.135a4.147 4.147 0 0 0-4.142 4.142V6H0v3h2v9.938h3V9h2.021l.592-3H5V3.591A.6.6 0 0 1 5.592 3h.543Z" />
                        </svg>
                    </a>
                    <a href="#" class="text-gray-100 hover:text-emerald-200">
                        <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 17">
                            <path d="M20 1.892a8.178 8.178 0 0 1-2.355.635 4.074 4.074 0 0 0 1.8-2.235 8.344 8.344 0 0 1-2.605.98A4.13 4.13 0 0 0 13.85 0a4.068 4.068 0 0 0-4.1 4.038 4 4 0 0 0 .105.919A11.705 11.705 0 0 1 1.4.734a4.006 4.006 0 0 0 1.268 5.392 4.165 4.165 0 0 1-1.859-.5v.05A4.057 4.057 0 0 0 4.1 9.635a4.19 4.19 0 0 1-1.856.07 4.108 4.108 0 0 0 3.831 2.807A8.36 8.36 0 0 1 0 14.184 11.732 11.732 0 0 0 6.291 16 11.502 11.502 0 0 0 17.964 4.5c0-.177 0-.35-.012-.523A8.143 8.143 0 0 0 20 1.892Z" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.js"></script>
    @yield('scripts')
</body>

</html>