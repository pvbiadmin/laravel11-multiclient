@extends('errors.layout')

@section('title', 'Page Expired')

@section('content')
    <div class="text-6xl font-bold text-gray-800 mb-4">419</div>
    <h1 class="text-2xl font-semibold text-gray-700 mb-4">Page Expired</h1>
    <p class="text-gray-600 mb-8">
        Your session has expired. Please refresh the page and try again.
    </p>
    <form action="{{ route('login') }}" method="GET" class="mt-4">
        <button type="submit"
            class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
            Go to Login
        </button>
    </form>
@endsection
