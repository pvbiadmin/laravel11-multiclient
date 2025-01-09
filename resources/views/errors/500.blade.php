@extends('errors.layout')

@section('title', '500 Server Error')

@section('content')
    <div class="text-6xl font-bold text-gray-800 mb-4">500</div>
    <h1 class="text-2xl font-semibold text-gray-700 mb-4">Server Error</h1>
    <p class="text-gray-600 mb-8">
        Oops! Something went wrong on our servers. We are aware of the problem and are working to fix it.
        Please try again later.
    </p>
    @if (app()->environment('local'))
        <div class="mt-4 p-4 bg-gray-100 rounded-lg text-left">
            <p class="text-sm font-mono text-gray-600">{{ $exception->getMessage() }}</p>
        </div>
    @endif
@endsection
