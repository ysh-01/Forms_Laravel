{{-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forms</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
    <style>
        .shadow-custom {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
    </style>
</head>

<body class="bg-gray-100">
    <nav class="bg-white p-4 shadow-lg">
        <div class="container mx-auto flex justify-between items-center">
            <a href="{{ url('/') }}" class="text-3xl font-bold text-purple-700">LaraForms</a>
            <div class="relative">
                <button id="profileMenuButton" class="flex items-center focus:outline-none">
                    <img src="{{ asset('images/user.png') }}" alt="Profile" class="w-10 h-10 rounded-full border-2 border-white">
                </button>
                <div id="profileMenu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-2">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block px-4 py-2 text-gray-700 hover:bg-gray-200 w-full text-left">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>
    <div class="container mx-auto mt-10">
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded relative mt-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif
        @if (session('delete'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded relative mt-4" role="alert">
                <span class="block sm:inline">{{ session('delete') }}</span>
            </div>
        @endif
        <div class="flex flex-wrap justify-between mb-6 items-center space-x-4">
            <a href="{{ route('forms.create') }}" class="flex flex-col items-center justify-center px-6 py-3 text-white font-semibold rounded-md shadow bg-purple-700 hover:bg-purple-900 transition duration-200">
                <img src="{{ asset('images/add.png') }}" alt="Create New Form" class="w-10 h-10 mb-2">
                <span>Create New Form</span>
            </a>
            <div class="flex flex-wrap justify-between flex-1 space-x-4">
                <div class="bg-white border border-gray-200 rounded-lg shadow p-5 flex-1">
                    <h5 class="mb-2 text-2xl font-bold text-gray-800">Total Forms Created</h5>
                    <p class="text-gray-700">{{ $totalForms }}</p>
                </div>
                <div class="bg-white border border-gray-200 rounded-lg shadow p-5 flex-1">
                    <h5 class="mb-2 text-2xl font-bold text-gray-800">Total Forms Published</h5>
                    <p class="text-gray-700">{{ $publishedForms }}</p>
                </div>
                <div class="bg-white border border-gray-200 rounded-lg shadow p-5 flex-1">
                    <h5 class="mb-2 text-2xl font-bold text-gray-800">Total Responses Received</h5>
                    <p class="text-gray-700">{{ $totalResponses }}</p>
                </div>
            </div>
        </div>
        <hr class="my-6">
        <h2 class="text-3xl font-semibold text-gray-800">Recent Forms</h2>
        <div class="shadow-custom rounded-lg p-6 bg-white mt-6">
            @if ($forms->isEmpty())
                <p class="text-gray-600 text-center">No forms available.</p>
            @else
                <div class="overflow-x-auto">
                    <table id="formsTable" class="min-w-full bg-white rounded-md">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="py-4 px-6 border-b border-gray-200 text-left text-sm font-semibold text-gray-600">Form Title</th>
                                <th class="py-4 px-6 border-b border-gray-200 text-left text-sm font-semibold text-gray-600">Created At</th>
                                <th class="py-4 px-6 border-b border-gray-200 text-left text-sm font-semibold text-gray-600">Responses</th>
                                <th class="py-4 px-6 border-b border-gray-200 text-left text-sm font-semibold text-gray-600">Status</th>
                                <th class="py-4 px-6 border-b border-gray-200 text-left text-sm font-semibold text-gray-600"></th>
                                <th class="py-4 px-6 border-b border-gray-200 text-left text-sm font-semibold text-gray-600"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($forms as $form)
                                <tr class="hover:bg-gray-50 transition duration-150">
                                    <td class="py-4 px-6 border-b border-gray-200">
                                        <a href="{{ route('forms.show', $form) }}" class="text-blue-600 font-semibold hover:underline">{{ $form->title }}</a>
                                        <p class="text-gray-600">{{ $form->description }}</p>
                                    </td>
                                    <td class="py-4 px-6 border-b border-gray-200">{{ $form->created_at->diffForHumans() }}</td>
                                    <td class="py-4 px-6 border-b border-gray-200">
                                        <a href="{{ route('responses.viewResponses', $form) }}" class="text-blue-500 hover:underline">View Responses</a>
                                    </td>
                                    <td class="py-4 px-6 border-b border-gray-200">
                                        @if ($form->is_published)
                                            Published
                                        @else
                                            Unpublished
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 border-b border-gray-200">
                                        @if (!$form->is_published)
                                            <a href="{{ route('forms.edit', $form) }}" class="text-green-500 hover:underline">Edit</a>
                                        @else
                                            <a href="#" class="text-gray-500" onclick="handle()">Edit</a>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 border-b border-gray-200">
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
                </div>
            @endif
        </div>
    </div>

    <script>
        document.getElementById('profileMenuButton').addEventListener('click', function() {
            var menu = document.getElementById('profileMenu');
            menu.classList.toggle('hidden');
        });

        function handle() {
            Swal.fire({
                title: 'You cannot edit a published form',
                icon: 'info',
                confirmButtonText: 'OK'
            });
        }
        setTimeout(function() {
            var successMessage = document.getElementById('successMessage');
            if (successMessage) {
                successMessage.remove();
            }
        }, 3000);
    </script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#formsTable').DataTable(); // Initialize DataTables
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
    <link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        .shadow-custom {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
    </style>
</head>

<body class="bg-gray-100">
    <nav class="bg-white p-4 shadow-lg">
        <div class="container mx-auto flex justify-between items-center">
            <a href="{{ url('/') }}" class="text-3xl font-bold font-sans" style="color: rgb(103,58,183)">LaraForms</a>
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

    <div class="container mx-auto mt-10 px-4">
        @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded relative mt-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
        @endif
        @if (session('delete'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded relative mt-4" role="alert">
            <span class="block sm:inline">{{ session('delete') }}</span>
        </div>
        @endif

        <div class="flex flex-wrap justify-between mb-6 items-center space-y-4 lg:space-y-0 lg:space-x-4">
            <a href="{{ route('forms.create') }}"
                class="inline-block px-6 py-3 text-white font-semibold rounded-md shadow bg-purple-700 hover:bg-purple-900 transition duration-200">
                Start a new form
            </a>
            <a class="block max-w-md w-full lg:w-1/4 p-5 bg-white border border-gray-200 rounded-lg shadow">
                <h5 class="mb-2 text-gray-800 text-xl font-bold">Total Forms Created</h5>
                <p class="font-normal text-gray-700">{{ $totalForms }}</p>
            </a>
            <a class="block max-w-md w-full lg:w-1/4 p-5 bg-white border border-gray-200 rounded-lg shadow">
                <h5 class="mb-2 text-gray-800 text-xl font-bold">Total Forms Published</h5>
                <p class="font-normal text-gray-700">{{ $publishedForms }}</p>
            </a>
            <a class="block max-w-md w-full lg:w-1/4 p-5 bg-white border border-gray-200 rounded-lg shadow">
                <h5 class="mb-2 text-gray-800 text-xl font-bold">Total Responses Received</h5>
                <p class="font-normal text-gray-700">{{ $totalResponses }}</p>
            </a>
        </div>

        <hr>
        <div class="mt-6">
            <h2 class="text-3xl font-semibold text-gray-800 font-sans">Recent Forms</h2>
            <div class="shadow-custom rounded-lg p-6 bg-gray-100 mt-4">
                @if ($forms->isEmpty())
                <p class="text-gray-600 text-center">No forms available.</p>
                @else
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white rounded-md">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="py-4 px-6 border-b border-gray-200 text-left text-sm font-semibold text-gray-600">Form Title</th>
                                <th class="py-4 px-6 border-b border-gray-200 text-left text-sm font-semibold text-gray-600">Created At</th>
                                <th class="py-4 px-6 border-b border-gray-200 text-left text-sm font-semibold text-gray-600">Responses</th>
                                <th class="py-4 px-6 border-b border-gray-200 text-left text-sm font-semibold text-gray-600">Status</th>
                                <th class="py-4 px-6 border-b border-gray-200"></th>
                                <th class="py-4 px-6 border-b border-gray-200"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($forms as $form)
                            <tr class="hover:bg-gray-50 transition duration-150">
                                <td class="py-4 px-6 border-b border-gray-200">
                                    <a href="{{ route('forms.show', $form) }}" class="text-blue-600 font-semibold hover:underline">{{ $form->title }}</a>
                                    <p class="text-gray-600">{{ $form->description }}</p>
                                </td>
                                <td class="py-4 px-6 border-b border-gray-200">{{ $form->created_at->format('M d, Y') }}</td>
                                <td class="py-4 px-6 border-b border-gray-200">
                                    <a href="{{ route('responses.viewResponses', $form) }}" class="text-blue-500 hover:underline">View Responses</a>
                                </td>
                                <td class="py-4 px-6 border-b border-gray-200">
                                    @if ($form->is_published)
                                    Published
                                    @else
                                    Unpublished
                                    @endif
                                </td>
                                <td class="py-8 px-6 border-b border-gray-200 flex items-center space-x-4">
                                    @if (!$form->is_published)
                                    <a href="{{ route('forms.edit', $form) }}" class="text-green-500 hover:underline">Edit</a>
                                    @else
                                    <a href="#" class="text-gray-500" id="formd" onclick="handle()">Edit</a>
                                    @endif
                                </td>
                                <td class="border-b border-gray-200">
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
                </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        document.getElementById('profileMenuButton').addEventListener('click', function () {
            var menu = document.getElementById('profileMenu');
            menu.classList.toggle('hidden');
        });

        function handle() {
            Swal.fire({
                title: 'You cannot edit a published form',
                icon: 'info',
                confirmButtonText: 'OK'
            });
        }
    </script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
</body>

</html>
