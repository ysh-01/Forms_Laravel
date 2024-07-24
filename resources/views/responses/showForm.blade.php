@extends('layouts.app')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@section('content')
    <div class="container mx-auto py-8 px-4 md:px-0">
        <div class="bg-white shadow-md rounded-lg p-6">
            <h1 class="text-3xl font-semibold text-gray-900">{{ $form->title }}</h1>
            <p class="text-gray-600 mt-2">{{ $form->description }}</p>

            <form id="responseForm" action="{{ route('responses.submitForm', $form) }}" method="POST" class="mt-8">
                @csrf
                @foreach ($questions as $question)
                    <div class="mt-6">
                        <label class="block font-medium text-base text-gray-800 mb-2">{{ $question->question_text }}</label>
                        @if ($question->type == 'multiple_choice')
                            @foreach (json_decode($question->options) as $option)
                                <label class="flex items-center mt-2">
                                    <input class="form-radio text-base text-purple-600 h-4 w-4" type="radio" name="answers[{{ $question->id }}]" value="{{ $option }}">
                                    <span class="ml-2 text-gray-700">{{ $option }}</span>
                                </label>
                            @endforeach
                        @elseif($question->type == 'checkbox')
                            @foreach (json_decode($question->options) as $option)
                                <label class="flex items-center mt-2">
                                    <input class="form-checkbox text-purple-600 h-4 w-4" type="checkbox" name="answers[{{ $question->id }}][]" value="{{ $option }}">
                                    <span class="ml-2 text-gray-700">{{ $option }}</span>
                                </label>
                            @endforeach
                        @elseif($question->type == 'dropdown')
                            <select class="form-select mt-2 block w-full p-2 border border-gray-300 rounded-md bg-white shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm" name="answers[{{ $question->id }}]">
                                @foreach (json_decode($question->options) as $option)
                                    <option value="{{ $option }}">{{ $option }}</option>
                                @endforeach
                            </select>
                        @elseif($question->type == 'text')
                            <textarea name="answers[{{ $question->id }}]" class="form-textarea mt-2 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm"></textarea>
                        @endif
                    </div>
                @endforeach

                <button type="submit" class="mt-8 w-full md:w-auto inline-flex justify-center items-center px-6 py-3 bg-purple-700 border border-transparent rounded-md font-semibold text-white text-lg uppercase tracking-widest hover:bg-purple-800 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                    Submit
                </button>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('responseForm').addEventListener('submit', function(event) {
            event.preventDefault();

            const form = event.target;
            const formData = new FormData(form);

            fetch(form.action, {
                method: form.method,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Form submitted successfully.',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '{{ route('responses.success', $form) }}';
                        }
                    });
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Error submitting. Answer all required questions',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '{{ route('responses.success', $form) }}';
                        }
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'Success!',
                    text: 'Form submitted successfully.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '{{ route('responses.success', $form) }}';
                        }
                    });
            });
        });
    </script>
@endsection
