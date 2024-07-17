<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Response Detail - {{ $form->title }}</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    {{-- <script src="https://cdn.tailwindcss.com"></script> --}}
    <style>
        .box {
    background-color: #fff;
    overflow: hidden;
}

.tab-list {
    margin: 0;
    padding: 0;
    list-style: none;
    display: flex;
    position: relative;
}

.tab-list::before {
    content: "";
    display: block;
    height: 2px;
    width: 50%;
    position: absolute;
    bottom: 0;
    background-color: #aaa;
    transition: 0.3s;
}

.tab-item {
    flex: 1;
    text-align: center;
    transition: 0.3s;
    opacity: 1;
}

.tab-toggle {
    display: none;
}

.tab-content {
    display: none;
}

.tab-toggle:nth-child(1):checked ~ .tab-list .tab-item:nth-child(1),
.tab-toggle:nth-child(2):checked ~ .tab-list .tab-item:nth-child(2){
    opacity: 5;
    color: #731273;
}

.tab-toggle:nth-child(2):checked ~ .tab-list::before {
    transform: translateX(100%);
}

.tab-toggle:nth-child(1):checked ~ .tab-container .tab-content:nth-child(1),
.tab-toggle:nth-child(2):checked ~ .tab-container .tab-content:nth-child(2) {
    display: block;
}
.tab-trigger {
    display: block;
    padding: 0;
    color: #731273;
}

.tab-trigger:hover {
    cursor: pointer;
}

.tab-container {
    padding: 15px 30px;
}


    </style>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
</head>

<body class="font-open-sans">
    <header class="bg-white shadow-md py-1">
        <div class="container d-flex justify-content-start align-items-center">
            <a href="/forms" class="d-flex align-items-center">
                <img src="{{ asset('images/google-form.png') }}" alt="Google Form Icon" height="40px" width="40px" />
            </a>
            <h2 style="color: rgb(103,58,183)" class="ml-3">{{ $form->title }} - Response Detail</h2>
        </div>
        <div class="container">
            <div class="box">
                <input type="radio" class="tab-toggle" name="tab-toggle" id="tab1" checked />
                <input type="radio" class="tab-toggle" name="tab-toggle" id="tab2" />

                <ul class="tab-list">
                    <li class="tab-item">
                        <label class="tab-trigger" for="tab1"><b>Responses</b></label>
                    </li>
                    <li class="tab-item">
                        <label class="tab-trigger" for="tab2"><b>Statistics</b></label>
                    </li>
                </ul>
            </div>
        </div>
    </header>

    <div  mt-4">
        <!-- Responses Section -->
        <div class="question_form bg-light p-4 rounded shadow-sm" id="responses_section">
            <div class="section">
                <div class="question_title_section mb-4">
                    <div class="question_form_top">
                        <input type="text" id="form-title" name="title" class="form-control form-control-lg mb-2" style="color: black" placeholder="Untitled Form" value="{{ $form->title }}" readonly />
                        <input type="text" name="description" id="form-description" class="form-control form-control-sm" style="color: black" value="{{$form->description}}" readonly/>
                    </div>
                </div>
            </div>
            <div class="section" id="questions_section">

                @foreach ($responses as $response)
                    @php
                        $question = $questions[$response->question_id] ?? null;
                        $decodedAnswers = json_decode($response->answers, true);
                    @endphp

                    @if ($question)
                        <div class="question mb-4 p-3 border rounded bg-white">
                            <h3 class="text-lg font-medium mb-2">{{ $question->question_text }}</h3>
                            @if ($question->type == 'dropdown')
                                <select disabled class="form-control">
                                    @foreach (json_decode($question->options) as $option)
                                        <option {{ ($option == $decodedAnswers) ? 'selected' : '' }}>
                                            {{ $option }}
                                        </option>
                                    @endforeach
                                </select>
                            @elseif (in_array($question->type, ['multiple_choice', 'checkbox']))
                                <div class="options-container mb-3">
                                    @foreach (json_decode($question->options) as $option)
                                        <div class="option d-flex align-items-center mb-2">
                                            <input type="{{ $question->type == 'checkbox' ? 'checkbox' : 'radio' }}" disabled {{ in_array($option, (array)$decodedAnswers) ? 'checked' : '' }} class="mr-2">
                                            {{ $option }}
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="mt-2 p-3 bg-light rounded">{{ is_array($decodedAnswers) ? implode(', ', $decodedAnswers) : $decodedAnswers }}</p>
                            @endif
                        </div>
                    @else
                        <p class="text-danger">Question not found for ID: {{ $response->question_id }}</p>
                    @endif
                @endforeach
            </div>
        </div>
        <!-- Statistics Section -->
        <div style="max-width: 800px" class="container statistics bg-light p-4 rounded shadow-sm" id="statistics_section" style="display: none;">
            <h3>Statistics</h3>
            <!-- Pie Chart -->
            <div style="max-width: 300px" class="mb-6 container">
                <canvas id="pieChart"></canvas>
            </div>
            <!-- Bar Graph -->
            <div style="max-width: 300px" class="container">
                <canvas id="barGraph"></canvas>
            </div>
        </div>
    </div>

    <div class="btnsub text-center mt-4">
        <span>
            <a href="{{ route('forms.index') }}" class="btn btn-secondary">Return to Forms</a>
        </span>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const tab1 = document.getElementById('tab1');
            const tab2 = document.getElementById('tab2');
            const responsesSection = document.getElementById('responses_section');
            const statisticsSection = document.getElementById('statistics_section');

            tab1.addEventListener('change', function() {
                if (tab1.checked) {
                    responsesSection.style.display = 'block';
                    statisticsSection.style.display = 'none';
                }
            });

            tab2.addEventListener('change', function() {
                if (tab2.checked) {
                    responsesSection.style.display = 'none';
                    statisticsSection.style.display = 'block';
                    renderCharts();  // Render charts when switching to statistics tab
                }
            });

            // Set the initial state
            if (tab1.checked) {
                responsesSection.style.display = 'block';
                statisticsSection.style.display = 'none';
            } else if (tab2.checked) {
                responsesSection.style.display = 'none';
                statisticsSection.style.display = 'block';
                renderCharts();  // Render charts when switching to statistics tab
            }
        });

        function renderCharts() {
            // Example data
            const pieData = {
                labels: ['Option 1', 'Option 2', 'Option 3'],
                datasets: [{
                    label: 'Responses',
                    data: [10, 20, 30],
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56'],
                }]
            };

            const barData = {
                labels: ['Question 1', 'Question 2', 'Question 3'],
                datasets: [{
                    label: 'Number of Responses',
                    data: [15, 25, 10],
                    backgroundColor: '#36A2EB',
                }]
            };

            // Pie Chart
            const pieCtx = document.getElementById('pieChart').getContext('2d');
            new Chart(pieCtx, {
                type: 'pie',
                data: pieData,
            });

            // Bar Graph
            const barCtx = document.getElementById('barGraph').getContext('2d');
            new Chart(barCtx, {
                type: 'bar',
                data: barData,
            });
        }
    </script>

    <script src="{{ asset('js/script.js') }}"></script>
</body>




</html>
