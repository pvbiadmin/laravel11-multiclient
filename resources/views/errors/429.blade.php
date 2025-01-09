@extends('errors.layout')

@section('title', 'Too Many Requests')

@section('content')
    <div class="text-6xl font-bold text-gray-800 mb-4">429</div>
    <h1 class="text-2xl font-semibold text-gray-700 mb-4">Too Many Requests</h1>
    <p class="text-gray-600 mb-8">
        You have made too many requests recently. Please wait before trying again.
        @if (isset($retryAfter))
            <br>
            <span class="text-sm">You can try again in {{ $retryAfter }} seconds.</span>
        @endif
    </p>
@endsection
