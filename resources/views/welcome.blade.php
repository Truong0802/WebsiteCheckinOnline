@extends('layouts.guest')

@section('content')
    <div class="text-center">
        <h1 class="mb-4">{{ __('common.welcome', ['app' => config('app.name')]) }}</h1>
        @auth
            <a href="{{ route('dashboard') }}" class="btn btn-primary">{{ __('common.dashboard') }}</a>
        @else
            <a href="{{ route('login') }}" class="btn btn-primary">{{ __('common.login') }}</a>
        @endauth
    </div>
@endsection
