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
    <!-- Add DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
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

    <!-- Tab Navigation -->
    {{-- <div class="bg-gray-100 px-6 py-4">
        <div class="flex space-x-4">
            <button id="responsesTabButton" class="tab-button text-purple-900 font-semibold border-b-2 border-purple-900 focus:outline-none">
                Responses
            </button>
            <button id="statisticsTabButton" class="tab-button text-gray-600 font-semibold focus:outline-none">
                Statistics
            </button>
        </div>
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
                <table id="responsesTable" class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
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
        <div id="statistics_tab" class="tab-content hidden">
            <h2 class="text-xl font-semibold mb-6">Statistics</h2>

            @foreach ($statistics as $questionId => $stat)
                @if (in_array($stat['type'], ['multiple_choice', 'checkbox', 'dropdown']))
                <div class="mb-8">
                    <h3 class="text-lg font-semibold mb-4">{{ $stat['question_text'] }}</h3>
                    <div style="width: 100%; max-width: 600px; margin: auto;">
                        <canvas id="chart-{{ $questionId }}" width="400" height="200"></canvas>
                    </div>
                </div>
                @endif
            @endforeach
        </div>
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

        // Add this script to render charts
        document.addEventListener('DOMContentLoaded', function () {
            @foreach ($statistics as $questionId => $stat)
                @if (in_array($stat['type'], ['multiple_choice', 'checkbox', 'dropdown']))
                console.log('Rendering chart for question:', {!! json_encode($stat) !!});
                var ctx = document.getElementById('chart-{{ $questionId }}');
                if (!ctx) {
                    console.error('Canvas element not found for question ID:', {{ $questionId }});
                    return;
                }
                var chartType = '{{ in_array($stat['type'], ['multiple_choice', 'dropdown']) ? 'pie' : 'bar' }}';
                var labels = {!! json_encode(array_keys($stat['responses'])) !!};
                var data = {!! json_encode(array_values($stat['responses'])) !!};
                console.log('Chart data:', { labels, data });
                var chartData = {
                    labels: labels,
                    datasets: [{
                        data: data,
                        backgroundColor: chartType === 'pie'
                            ? ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40', '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40']
                            : '#36A2EB'
                    }]
                };
                try {
                    new Chart(ctx, {
                        type: chartType,
                        data: chartData,
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: chartType === 'bar' ? {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        stepSize: 1
                                    }
                                }
                            } : {},
                            plugins: {
                                legend: {
                                    display: chartType === 'pie'
                                }
                            }
                        }
                    });
                    console.log('Chart created successfully for question ID:', {{ $questionId }});
                } catch (error) {
                    console.error('Error creating chart:', error);
                }
                @endif
            @endforeach
        });

        // Tab functionality
        document.getElementById('responsesTabButton').addEventListener('click', function() {
            document.getElementById('responses_tab').classList.remove('hidden');
            document.getElementById('statistics_tab').classList.add('hidden');
            this.classList.add('text-purple-900', 'border-purple-900');
            this.classList.remove('text-gray-600');
            document.getElementById('statisticsTabButton').classList.remove('text-purple-900', 'border-purple-900');
            document.getElementById('statisticsTabButton').classList.add('text-gray-600');
        });

        document.getElementById('statisticsTabButton').addEventListener('click', function() {
            document.getElementById('statistics_tab').classList.remove('hidden');
            document.getElementById('responses_tab').classList.add('hidden');
            this.classList.add('text-purple-900', 'border-purple-900');
            this.classList.remove('text-gray-600');
            document.getElementById('responsesTabButton').classList.remove('text-purple-900', 'border-purple-900');
            document.getElementById('responsesTabButton').classList.add('text-gray-600');
        });
    </script>

    <!-- Custom Scripts -->
    <script src="{{ asset('js/script.js') }}"></script>
    <!-- Add DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#responsesTable').DataTable({ // Initialize DataTables with search and sort functionality
                "paging": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true
            });
        });
    </script>
</body>

</html>
