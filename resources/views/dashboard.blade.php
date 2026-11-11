@extends('layouts.app')

@section('title', __('common.dashboard'))

@section('content')
    <h3>{{ __('common.dashboard') }}</h3>
    <p class="text-muted">{{ __('common.logged_in') }}</p>
@endsection
