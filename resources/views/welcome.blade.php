<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>New Bie Management System</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Alpine.js for Modal -->
    <script src="//unpkg.com/alpinejs" defer></script>

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <link href="{{ asset('assets/css/home.css') }}" rel="stylesheet" />
    @endif
</head>

<body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] flex p-6 lg:p-8 items-center lg:justify-center min-h-screen flex-col">

    <div class="flex items-center justify-center w-full transition-opacity opacity-100 duration-750 lg:grow starting:opacity-0">
        <main class="flex max-w-[335px] w-full flex-col-reverse lg:max-w-4xl lg:flex-row">

            <!-- Text Section -->
            <div class="text-[13px] leading-[20px] flex-1 p-6 pb-12 lg:p-20 bg-white dark:bg-[#161615] dark:text-[#EDEDEC] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-bl-lg rounded-br-lg lg:rounded-tl-lg lg:rounded-br-none">
                <h1 class="mb-1 font-medium">New Bie Management System</h1>
                <p class="mb-2 text-[#706f6c] dark:text-[#A1A09A]">
                    This is a web-based application designed to streamline and enhance visitor registration, tracking, and security.
                </p>

                <!-- Auth Navigation -->
                <ul class="flex gap-3 text-sm leading-normal">
                    <li>
                        @if (Route::has('login'))
                            <nav class="flex items-center justify-start gap-4 flex-wrap">
                                @auth
                                    @if (Auth::user()->role === 'admin')
                                        <a href="{{ route('admin.dashboard') }}"
                                            class="mt-4 inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal">
                                            Admin Dashboard
                                        </a>
                                    @elseif (Auth::user()->role === 'reception')
                                        <a href="{{ route('reception.visitor.index') }}"
                                            class="mt-4 inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal">
                                            Receptionist Dashboard
                                        </a>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}"
                                        class="mt-4 inline-block px-5 py-1.5 dark:text-[#EDEDEC] text-[#1b1b18] border border-transparent hover:border-[#19140035] dark:hover:border-[#3E3E3A] rounded-sm text-sm leading-normal">
                                        Log in
                                    </a>
                                    @if (Route::has('register'))
                                        <a href="{{ route('register') }}"
                                            class="mt-4 inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal">
                                            Register
                                        </a>
                                    @endif
                                @endauth
                            </nav>
                        @endif
                    </li>
                </ul>

                <!-- Visitor Modal Trigger -->
                <div class="mt-6" x-data="{ open: false }">
                    <button @click="open = true"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                        Show Visitor QR
                    </button>

                    <!-- Modal -->
                    <div x-show="open" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
                        <div @click.away="open = false"
                            class="bg-white dark:bg-[#161615] rounded-lg w-[90%] max-w-md p-6 shadow-lg relative">
                            <button @click="open = false" class="absolute top-2 right-2 text-gray-500 hover:text-red-600 text-xl">&times;</button>

                            <h2 class="text-lg font-semibold mb-4 text-center">Visitor QR Code</h2>

                            <!-- QR Code -->
                            <div class="flex justify-center mb-4">
                                <img src="{{ asset('images/sample-qr.png') }}" alt="QR Code" class="w-40 h-40 border p-2 rounded" />
                            </div>

                            <!-- Check-in and Check-out Buttons -->
                            <div class="flex justify-between gap-4">
                                <form action="{{ route('visitor.checkin', ['visitor_number' => $visitor_number]) }}" method="POST" class="w-full">
                                    @csrf
                                    <button type="submit"
                                        class="w-full bg-green-600 hover:bg-green-700 text-white py-2 rounded">
                                        Check In
                                    </button>
                                </form>
                                <form action="{{ route('visitor.checkout', ['visitor_number' => $visitor_number]) }}" method="POST" class="w-full">
                                    @csrf
                                    <button type="submit"
                                        class="w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded">
                                        Check Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Image Section -->
            <div class="bg-[#fff2f2] dark:bg-[#1D0002] relative lg:-ml-px -mb-px lg:mb-0 rounded-t-lg lg:rounded-t-none lg:rounded-r-lg aspect-[335/376] lg:aspect-auto w-full lg:w-[438px] shrink-0 overflow-hidden" style="background: url('{{ asset('images/banner.jpg') }}'); background-size: cover;">
                <div class="absolute inset-0 rounded-t-lg lg:rounded-t-none lg:rounded-r-lg shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d]"></div>
            </div>
        </main>
    </div>

    @if (Route::has('login'))
        <div class="h-14.5 hidden lg:block"></div>
    @endif
</body>

</html>
