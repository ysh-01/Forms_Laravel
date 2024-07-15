<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LaraForms - Home</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        /* Add any additional custom styles here */
    </style>
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">

<!-- Navigation -->
<!-- Navigation -->
<!-- Navbar -->
<nav class="flex items-center justify-between flex-wrap p-6" style="background-color: rgb(103,58,183)">
    <div class="flex items-center flex-shrink-0 text-white mr-6">
        <span class="font-semibold text-xl tracking-tight">LaraForms</span>
    </div>
    <div class="block lg:hidden">
        <button id="nav-toggle" class="flex items-center px-3 py-2 border rounded text-gray-500 border-gray-600 hover:text-white hover:border-white">
            <svg class="fill-current h-3 w-3" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><title>Menu</title><path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z"/></svg>
        </button>
    </div>
    <div class="w-full flex-grow lg:flex lg:items-center lg:w-auto hidden lg:block mt-2 lg:mt-0 bg-gray-900 lg:bg-transparent text-white p-4 lg:p-0 z-20" id="nav-content">
        <ul class="list-reset lg:flex justify-end flex-1 items-center">
            <li class="mr-3">
                @guest
                    <a href="{{ route('login') }}" class="inline-block text-sm px-4 py-2 leading-none border rounded text-white border-white hover:border-transparent hover:text-gray-900 hover:bg-white">Login</a>
                @else
                    <a href="{{ route('forms.index') }}" class="inline-block text-sm px-4 py-2 leading-none border rounded text-white border-white hover:border-transparent hover:text-gray-900 hover:bg-white">Go to Forms</a>
                @endguest
            </li>
        </ul>
    </div>
</nav>



<!-- Hero Section -->
<section class="pt-24 bg-gray-100">
    <div class="container mx-auto px-6 text-center">
        <h1 class="text-5xl font-bold leading-tight text-gray-900">Welcome to LaraForms</h1>
        <p class="mt-4 text-xl text-gray-700">Create and share forms effortlessly.</p>
        <div class="flex justify-center mt-8">
            @auth
                <a href="{{ route('forms.index') }}" class="inline-block bg-white text-black px-5 py-3 rounded-lg shadow-lg uppercase tracking-wide font-semibold text-sm hover:bg-black hover:text-white">Go to Forms</a>
            @else
                <a href="{{ route('register') }}" class="inline-block text-sm px-4 py-2 leading-none border rounded text-gray-900 border-gray-900 hover:border-transparent hover:text-gray-700 hover:bg-purple-200 mt-4 lg:mt-0">Get Started</a>
            @endauth
        </div>
    </div>
</section>


<!-- Features Section -->
<section class="py-16">
    <div class="container mx-auto px-6">
        <h2 class="text-3xl font-bold text-gray-800 text-center mb-8">Key Features</h2>
        <div class="flex flex-wrap -mx-4">
            <div class="w-full sm:w-1/2 lg:w-1/3 px-4 mb-8">
                <div class="bg-white rounded-lg shadow-lg p-6 hover:bg-purple-200">
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Easy Form Creation</h3>
                    <p class="text-gray-700 leading-relaxed">Quickly build forms with various question types.</p>
                </div>
            </div>
            <div class="w-full sm:w-1/2 lg:w-1/3 px-4 mb-8">
                <div class="bg-white rounded-lg shadow-lg p-6 hover:bg-purple-200">
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Share and Collect Responses</h3>
                    <p class="text-gray-700 leading-relaxed">Share forms and gather responses seamlessly.</p>
                </div>
            </div>
            <div class="w-full sm:w-1/2 lg:w-1/3 px-4 mb-8">
                <div class="bg-white rounded-lg shadow-lg p-6 hover:bg-purple-200">
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Customizable Options</h3>
                    <p class="text-gray-700 leading-relaxed">Customize forms with themes and options.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Footer Section -->
<footer class="text-white mt-24" style="background-color: rgb(103,58,183)">
    <div class="container mx-auto py-12 px-6">
        <div class="flex flex-wrap">
            <div class="w-full lg:w-1/3 mb-6 lg:mb-0">
                <h4 class="text-lg font-bold mb-4">About LaraForms</h4>
                <p class="leading-relaxed">LaraForms is a platform to create and manage forms with ease using Laravel.</p>
            </div>
            <div class="w-full lg:w-1/3 mb-6 lg:mb-0">
                <h4 class="text-lg font-bold mb-4">Contact Us</h4>
                <p class="leading-relaxed">Email: contact@laraforms.com</p>
            </div>
            <div class="w-full lg:w-1/3 mb-6 lg:mb-0">
                <h4 class="text-lg font-bold mb-4">Follow Us</h4>
                <div class="flex">
                    <a href="#" class="mr-4 hover:text-gray-400"><svg class="h-6 w-6 fill-current" viewBox="0 0 512 512"><path d="M476.6 134.4c-10-14-22.7-25-36.8-33.2 -14-7.6-29.1-12-45.6-12.8 -18.3-0.8-36.3 2.4-53.6 9.2 -16.8 6.7-31.8 16.6-45.2 29.6 -1.1 1.1-2.2 2.2-3.3 3.3l-13.7-13.7c-9.2-9.2-22.7-11.9-34.5-6.9 -8.5 3.7-15.6 10.8-19.3 19.3 -5.1 12.7-2.4 27.7 6.9 36.8l15.5 15.5c-2.5 4.5-4.9 9-7.6 13.3 -7.6 11.9-20.3 18.9-33.8 18.9 -5.6 0-11.2-1.1-16.7-3.3 -13.7-5.1-24.9-15.5-30.6-28.7 -5.1-11.9-5.1-24.9 0-36.8 6.7-13.7 17.9-23.1 31.6-28.7 5.1-2.2 10.2-3.3 15.8-3.3 5.1 0 10.2 1.1 15.3 3.3 12.7 5.1 23.9 15.5 29.6 28.7 2.5 5.1 4.9 10.2 7.6 15.3l13.7 13.7c-0.8 1.1-1.7 2.2-2.5 3.3 -12.3 16.8-17.5 36.3-14.6 56.1 2.8 22.7 14.6 42.2 33.8 54.9 18.3 12.7 39.6 15.5 62.3 8.5 10.8-3.3 21.7-8.5 31.8-15.5 12.7-9.2 24.3-20.3 34.5-33.8 10.2-12.7 17.5-26.3 22.3-41.6 6.9-22.7 6.1-45.6-1.7-68.3z"></path></svg></a>
                    <a href="#" class="mr-4 hover:text-gray-400"><svg class="h-6 w-6 fill-current" viewBox="0 0 512 512"><path d="M477.1 184.5c-10.3-14.3-23.9-25.4-39.2-32.1 -15-6.6-31.4-9.9-48-9.9 -27.5 0-50.8 11.6-69.7 34.7 -18.9 23.1-28.4 51.4-28.4 85 0 15.8 1.7 31.4 5.1 46.8 3.4 15.4 8.6 29.5 15.4 42.4 6.8 12.9 15.4 24 25.8 33.3 10.4 9.2 22.5 16.6 36.2 22.3 13.7 5.7 28.5 9.5 44.4 11.2 15.9 1.7 31.7 2.6 47.4 2.6 31.7 0 59.7-5.5 83.9-16.6 24.2-11.1 44.2-26.2 60-45.3l-75.3-58.5c-8.6-6.8-18.6-11.1-30-13 -11.4-1.9-22.3-2.8-32.5-2.8 -13.7 0-26.2 2.5-37.5 7.4 -11.4 4.9-21.3 11.4-29.8 19.5 -8.5 8.1-15.2 17.8-20.1 28.9 -4.9 11.1-7.4 23.1-7.4 36.2 0 23.6 7.9 42.9 23.6 57.9 15.8 15.1 36.5 22.6 61.9 22.6 14.4 0 28-2.2 40.9-6.5 12.9-4.3 24.4-10.3 34.4-17.8l71 55.3c14.5-11.5 26.8-25.1 36.7-40.8 9.9-15.7 17.4-32.5 22.3-50.3 4.9-17.8 7.4-36.2 7.4-55.3 0-2.8 0-5.7-0.1-8.5 0-1.4-0.1-2.7-0.2-4.1 0 0-0.1-0.1-0.1-0.1l0 0zM352.3 385.6c-5.8 5.8-12.6 10.6-20.3 14.3 -7.7 3.7-16.1 6.5-25.2 8.6 -9.1 2.1-18.6 3.2-28.5 3.2 -16.5 0-31.6-2.6-45.3-7.7 -13.7-5.1-25.3-12.6-35-22.3 -9.7-9.7-17.2-21.3-22.3-35 -5.1-13.7-7.7-28.8-7.7-45.3 0-7.9 0.8-15.4 2.4-22.5 1.6-7.1 3.9-13.7 6.9-19.8l76.3 59.2c12.7 9.8 27.2 16.2 43.6 19.1 16.4 2.9 32.9 1.7 49.4-3.3 16.5-5 31.6-14.5 45.4-28.5 13.8-14 24.4-31.2 31.9-51.5l-68.7-53.3c-11 14.2-25.3 25.5-42.7 33.9zM400 80c44.1 0 80 35.9 80 80s-35.9 80-80 80c-44.1 0-80-35.9-80-80s35.9-80 80-80zM400 128c-22.1 0-40 17.9-40 40s17.9 40 40 40c22.1 0 40-17.9 40-40s-17.9-40-40-40z"></path></svg></a>
                </div>
            </div>
        </div>
    </div>
</footer>

</body>
</html>
