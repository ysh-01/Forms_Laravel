@extends('layouts.app')

@section('content')
    <div class="container mx-auto py-8 px-4 md:px-0">
        <div class="bg-white shadow-md rounded-lg p-6 text-center">
            <h1 class="text-3xl font-semibold text-gray-900">Response Submitted Successfully</h1>
            <p class="text-gray-600 mt-2">Thank you for your response.</p>
            <a href="{{ route('responses.showForm', $form) }}" class="mt-8 inline-flex justify-center items-center px-6 py-3 bg-purple-700 border border-transparent rounded-md font-semibold text-white text-lg uppercase tracking-widest hover:bg-purple-800 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                Fill Another Response
            </a>
        </div>
    </div>
@endsection
