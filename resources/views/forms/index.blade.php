{{-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Forms</title>
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    <!-- Include Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="form_header flex justify-between items-center py-4 px-6 bg-white shadow-md">
        <div class="form_header_left flex items-center">
            <a href="/forms">
                <img src="{{ asset('images/google-form.png') }}" class="form_header_icon" height="45px" width="40px">
            </a>
            <h1 class="form_name text-xl font-semibold text-gray-800 ml-2">LaraForms</h1>
        </div>
        <div class="form_header_right flex items-center">
            <img src="{{ asset('images/menu.png') }}" alt="menu" class="mr-4" height="30px" width="30px">
            <img src="{{ asset('images/user.png') }}" alt="" class="mr-2" height="30px" width="30px">
        </div>
    </div>

    <div class="container mx-auto py-8 px-4">
        <div class="start_form mb-8">
            <a href="{{ route('forms.create') }}" class="block bg-purple-200 hover:bg-purple-300 text-white font-semibold py-2 px-4 rounded">
                <h2 class="text-lg">Start a new form</h2>
            </a>
        </div>

        <div class="recent_forms">
            <h2 class="text-2xl font-semibold mb-4">Recent Forms</h2>
            @if ($forms->isEmpty())
                <p class="text-gray-600">No forms available.</p>
            @else
                <ul class="divide-y divide-gray-300">
                    @foreach ($forms as $form)
                        <li class="py-4 flex justify-between items-center">
                            <div>
                                <a href="{{ route('forms.show', $form) }}" class="text-xl font-semibold text-blue-600 hover:underline">{{ $form->title }}</a>
                                <p class="text-gray-600">{{ $form->description }}</p>
                            </div>
                            <div class="flex items-center">
                                <span class="mr-4 text-gray-600">{{ $form->is_published ? 'Published' : 'Not Published' }}</span>
                                @if ($form->is_published)
                                    <a href="{{ route('responses.viewResponses', $form) }}" class="btn btn-secondary mr-2">Responses</a>
                                @endif
                                <a href="{{ route('forms.edit', $form) }}" class="btn btn-primary mr-2">Edit</a>
                                <form action="{{ route('forms.destroy', $form) }}" method="POST" class="mr-2">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>

    <script src="{{ asset('js/script.js') }}"></script>
</body>
</html> --}}



{{-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forms</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <nav class="bg-white p-4 shadow-lg">
        <div class="container mx-auto flex justify-between items-center">
            <a href="{{ url('/') }}" class="text-black text-2xl font-bold">LaraForms</a>
            <div class="relative">
                <button id="profileMenuButton" class="flex items-center focus:outline-none">
                    <img src="{{ Auth::user()->profile_photo_url }}" alt="Profile" class="w-10 h-10 rounded-full border-2 border-white">
                </button>
                <div id="profileMenu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-2">
                    <a href="{{ route('logout') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mx-auto mt-10">
        <div class="flex justify-start mb-6">
            <a href="{{ route('forms.create') }}" class="inline-block px-6 py-3 bg-purple-600 text-white font-semibold rounded-md shadow hover:bg-purple-700 transition duration-200">Start a new form</a>
        </div>

        <div class="bg-white shadow-lg rounded-lg p-6">
            <h2 class="text-2xl font-semibold mb-4">Recent Forms</h2>
            @if ($forms->isEmpty())
                <p class="text-gray-600">No forms available.</p>
            @else
                <table class="min-w-full bg-white">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="py-4 px-6 border-b border-gray-200 text-left text-sm font-semibold text-gray-600">Form Title</th>
                            <th class="py-4 px-6 border-b border-gray-200 text-left text-sm font-semibold text-gray-600">Created At</th>
                            <th class="py-4 px-6 border-b border-gray-200 text-left text-sm font-semibold text-gray-600">Responses</th>
                            <th class="py-4 px-6 border-b border-gray-200 text-left text-sm font-semibold text-gray-600">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($forms as $form)
                            <tr class="hover:bg-gray-50 transition duration-150">
                                <td class="py-4 px-6 border-b border-gray-200">
                                    <a href="{{ route('forms.show', $form) }}" class="text-blue-600 font-semibold hover:underline">{{ $form->title }}</a>
                                    <p class="text-gray-600">{{ $form->description }}</p>
                                </td>
                                <td class="py-4 px-6 border-b border-gray-200">{{ $form->created_at->format('M d, Y') }}</td>
                                <td class="py-4 px-6 border-b border-gray-200">
                                    @if ($form->is_published)
                                        <a href="{{ route('responses.viewResponses', $form) }}" class="btn btn-secondary text-blue-500 hover:underline">View Responses</a>
                                    @else
                                        <span class="text-gray-600">Not Published</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 border-b border-gray-200 flex items-center">
                                    <a href="{{ route('forms.edit', $form) }}" class="text-green-500 hover:underline">Edit</a>
                                    <form action="{{ route('forms.destroy', $form) }}" method="POST" class="inline-block ml-4">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:underline">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    <script>
        document.getElementById('profileMenuButton').addEventListener('click', function() {
            document.getElementById('profileMenu').classList.toggle('hidden');
        });
    </script>
</body>
</html> --}}








<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forms</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .dropdown:hover .dropdown-menu {
            display: block;
        }
        .shadow-custom {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
    </style>
</head>
<body class="bg-gray-50">
    <nav class="bg-white p-4 shadow-lg">
        <div class="container mx-auto flex justify-between items-center">
            <a href="{{ url('/') }}" class="text-purple-600 text-3xl font-bold">LaraForms</a>
            <div class="relative dropdown">
                <button id="profileMenuButton" class="flex items-center focus:outline-none">
                    <img src="{{ asset('images/user.png') }}" alt="Profile" class="w-10 h-10 rounded-full border-2 border-white">
                </button>
                <div id="profileMenu" class="dropdown-menu hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-2">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block px-4 py-2 text-gray-700 hover:bg-gray-200 w-full text-left">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mx-auto mt-10">
        <div class="flex justify-between mb-6 items-center">
            <a href="{{ route('forms.create') }}" class="inline-block px-6 py-3 bg-purple-600 text-white font-semibold rounded-md shadow hover:bg-purple-700 transition duration-200">Start a new form</a>

        </div>
        <h2 class="text-3xl font-semibold text-gray-800">Recent Forms</h2>
        <br>
        <div class="bg-white shadow-custom rounded-lg p-6">
            @if ($forms->isEmpty())
                <p class="text-gray-600 text-center">No forms available.</p>
            @else
                <table class="min-w-full bg-white rounded-md overflow-hidden">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="py-4 px-6 border-b border-gray-200 text-left text-sm font-semibold text-gray-600">Form Title</th>
                            <th class="py-4 px-6 border-b border-gray-200 text-left text-sm font-semibold text-gray-600">Created At</th>
                            <th class="py-4 px-6 border-b border-gray-200 text-left text-sm font-semibold text-gray-600">Responses</th>
                            <th class="py-4 px-6 border-b border-gray-200 text-left text-sm font-semibold text-gray-600">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($forms as $form)
                            <tr class="hover:bg-gray-50 transition duration-150">
                                <td class="py-4 px-6 border-b border-gray-200">
                                    <a href="{{ route('forms.show', $form) }}" class="text-blue-600 font-semibold hover:underline">{{ $form->title }}</a>
                                    <p class="text-gray-600">{{ $form->description }}</p>
                                </td>
                                <td class="py-4 px-6 border-b border-gray-200">{{ $form->created_at->format('M d, Y') }}</td>
                                <td class="py-4 px-6 border-b border-gray-200">
                                    @if ($form->is_published)
                                        <a href="{{ route('responses.viewResponses', $form) }}" class="text-blue-500 hover:underline">View Responses</a>
                                    @else
                                        <span class="text-gray-600">Not Published</span>
                                    @endif
                                </td>
                                <td class="py-8 px-6 border-b border-gray-200 flex items-center space-x-4">
                                    <a href="{{ route('forms.edit', $form) }}" class="text-green-500 hover:underline">Edit</a>
                                    <form action="{{ route('forms.destroy', $form) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:underline">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    <script>
        document.getElementById('profileMenuButton').addEventListener('click', function() {
            document.getElementById('profileMenu').classList.toggle('hidden');
        });
    </script>
</body>
</html>







