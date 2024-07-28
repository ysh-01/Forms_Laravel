<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'LaraForms') }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body style="background-color: #f0ebf8">
    <div id="app">
        <nav class="bg-purple-700 shadow-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="flex-shrink-0 flex items-center">
                            <a class="text-white text-lg font-semibold" href="{{ url('/') }}">
                                {{ config('app.name', 'LaraForms') }}
                            </a>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <div class="hidden md:block">
                            <div class="ml-10 flex items-baseline space-x-4">
                                @guest
                                    @if (Route::has('login'))
                                        <a href="{{ route('login') }}" class="text-white hover:bg-purple-600 hover:text-white px-3 py-2 rounded-md text-sm font-medium">{{ __('Login') }}</a>
                                    @endif

                                    @if (Route::has('register'))
                                        <a href="{{ route('register') }}" class="text-white hover:bg-purple-600 hover:text-white px-3 py-2 rounded-md text-sm font-medium">{{ __('Register') }}</a>
                                    @endif
                                @else
                                    <div class="ml-3 relative">
                                        <div>
                                            <button type="button" class="text-white hover:bg-purple-600 hover:text-white px-3 py-2 rounded-md text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-purple-800 focus:ring-white" id="user-menu" aria-expanded="false" aria-haspopup="true">
                                                {{ Auth::user()->name }}
                                            </button>
                                        </div>
                                        <div class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none hidden" role="menu" aria-orientation="vertical" aria-labelledby="user-menu">
                                            <a href="{{ route('logout') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>
                                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                                @csrf
                                            </form>
                                        </div>
                                    </div>
                                @endguest
                            </div>
                        </div>
                    </div>
                    <div class="-mr-2 flex md:hidden">
                        <button type="button" class="bg-purple-800 inline-flex items-center justify-center p-2 rounded-md text-purple-400 hover:text-white hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-purple-800 focus:ring-white" aria-controls="mobile-menu" aria-expanded="false">
                            <span class="sr-only">Open main menu</span>
                            <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                            <svg class="hidden h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <div class="md:hidden" id="mobile-menu">
                <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                    @guest
                        @if (Route::has('login'))
                            <a href="{{ route('login') }}" class="text-white hover:bg-purple-600 hover:text-white block px-3 py-2 rounded-md text-base font-medium">{{ __('Login') }}</a>
                        @endif

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="text-white hover:bg-purple-600 hover:text-white block px-3 py-2 rounded-md text-base font-medium">{{ __('Register') }}</a>
                        @endif
                    @else
                        <div class="pt-4 pb-3 border-t border-purple-800">
                            <div class="flex items-center px-5">
                                <div class="ml-3">
                                    <div class="text-base font-medium leading-none text-white">{{ Auth::user()->name }}</div>
                                </div>
                            </div>
                            <div class="mt-3 px-2 space-y-1">
                                <a href="{{ route('logout') }}" class="block px-3 py-2 rounded-md text-base font-medium text-white hover:bg-purple-600 hover:text-white" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>
                            </div>
                        </div>
                    @endguest
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>

    <script>
        // Toggle mobile menu
        document.querySelector('button[aria-controls="mobile-menu"]').addEventListener('click', function() {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        });

        // Toggle user dropdown
        document.getElementById('user-menu')?.addEventListener('click', function() {
            this.nextElementSibling.classList.toggle('hidden');
        });
    </script>
</body>
</html>
