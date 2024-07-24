<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Form Responses</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="font-roboto text-gray-800 bg-white">


    <!-- Header -->
    <div class="bg-white shadow-md px-6 py-4 flex justify-between items-center">
        <div class="flex items-center">
            <h1 class="text-2xl font-semibold text-purple-900">{{ $form->title }} - Responses</h1>
        </div>
        <div class="relative dropdown">
            <button id="profileMenuButton" class="flex items-center focus:outline-none">
                <img src="{{ asset('images/user.png') }}" alt="Profile"
                    class="w-10 h-10 rounded-full border-2 border-white">
            </button>
            <div id="profileMenu"
                class="dropdown-menu hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-2">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block px-4 py-2 text-gray-700 hover:bg-gray-200 w-full text-left">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
    {{-- <div class="text-sm font-medium text-center text-purple-700 border-b border-black-700 dark:text-gray-400 dark:border-gray-700 hover: border-b-2">
        <ul class="flex flex-wrap -mb-px">
            <li class="mr-2">
                <a href="javascript:void(0);" onclick="showTab('responses_tab')" class="tab-link text-black-600 font-bold inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-purple-700 ">Responses</a>
            </li>
            <li class="mr-2">
                <a href="javascript:void(0);" onclick="showTab('statistics_tab')" class="tab-link text-black-600 font-bold inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-purple-700 ">Statistics</a>
            </li>
        </ul>
    </div> --}}

    <!-- Main Content -->
    <div class="mx-auto max-w-7xl px-6 py-8">

        <!-- Responses Tab -->
        <div id="responses_tab" class="tab-content">

            <!-- Share Link -->
            <div class="flex items-center mb-6">

                <div class="flex items-center">
                    <input type="text" value="{{ route('responses.showForm', $form) }}" id="shareLink"
                        class="bg-white border border-gray-300 px-3 py-1 rounded-l sm:w-auto focus:outline-none"
                        readonly>
                    &nbsp;
                    <button onclick="copyLink()"
                        class="bg-purple-600 text-white px-4 py-1.5 rounded-r hover:bg-purple-700 focus:outline-none ml-2 sm:ml-0 mt-2 sm:mt-0">
                        Copy Link
                    </button>
                    <!-- Copy Link Notification -->
                    <div id="copyNotification"
                        class="hidden bg-green-100 border border-green-500 text-green-700 px-2 py-1 rounded ml-2">
                        Link copied!
                    </div>
                </div>
            </div>
            <br>
            <h2 class="text-xl font-semibold mr-4">Responses</h2>
            <br>
            <!-- Responses Table -->
            @if ($responses->isEmpty())
            <p class="text-gray-600">No responses available.</p>
            @else
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
                    <thead class="bg-gray-200 text-black text-sm leading-normal">
                        <tr>
                            <th class="py-3 px-6 text-left">User</th>
                            <th class="py-3 px-6 text-left">Email ID</th>
                            <th class="py-3 px-6 text-left">Submitted</th>
                            <th class="py-3 px-6 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-black text-sm font-light">
                        @foreach ($responses as $responseGroup)
                        <tr class="border-b border-gray-200 text-gray-700 hover:bg-gray-100">
                            <td class="py-3 px-6 text-left">
                                {{ $responseGroup->first()->user->name ?? 'Anonymous' }}
                            </td>
                            <td class="py-3 px-6 text-left">
                                {{ $responseGroup->first()->user->email ?? 'NA'}}
                            </td>
                            <td class="py-3 px-6 text-left">
                                {{ $responseGroup->first()->created_at->diffForHumans()}}
                            </td>
                            <td class="py-3 px-6 text-left">
                                <a href="{{ route('responses.viewResponse', ['form' => $form, 'responseId' => $responseGroup->first()->response_id]) }}"
                                    target="_blank"
                                    class="text-blue-600 hover:text-blue-700 hover:underline focus:outline-none">View Response</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>

        <!-- Statistics Tab -->
        {{-- <div id="statistics_tab" class="tab-content hidden w-3/6">
            <h2 class="text-xl font-semibold mb-6">Statistics</h2>

            @foreach ($statistics as $questionId => $stat)
            <div class="mb-8">
                <h3 class="text-lg font-semibold mb-4">{{ $stat['question_text'] }}</h3>
                <canvas id="chart-{{ $questionId }}"></canvas>
            </div>
            @endforeach
        </div> --}}
    </div>

    <!-- Script for Copy Link Functionality -->
    <script>
        function copyLink() {
            var copyText = document.getElementById("shareLink");
            copyText.select();
            copyText.setSelectionRange(0, 99999); /* For mobile devices */
            document.execCommand("copy");

            // Show copy notification next to the copy button
            var copyNotification = document.getElementById("copyNotification");
            copyNotification.classList.remove("hidden");
            setTimeout(function () {
                copyNotification.classList.add("hidden");
            }, 2000);
        }

    </script>

    <!-- Custom Scripts -->
    <script src="{{ asset('js/script.js') }}"></script>
</body>

</html>
