{{-- Form đăng nhập quản trị: POST /login (LoginController@store), lỗi hiển thị dưới từng field. --}}
@extends('layouts.guest')

@section('title', __('common.login'))

@section('content')
    <div class="card shadow-sm" style="width: 380px">
        <div class="card-body p-4">
            <h4 class="card-title mb-4 text-center">{{ __('common.login') }}</h4>
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-3">
                    <label for="username" class="form-label">{{ __('common.username') }}</label>
                    <input id="username" type="text" name="username" value="{{ old('username') }}" required autofocus
                           autocomplete="username" maxlength="100"
                           class="form-control @error('username') is-invalid @enderror">
                    @error('username')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">{{ __('common.password') }}</label>
                    <input id="password" type="password" name="password" required
                           class="form-control @error('password') is-invalid @enderror">
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="form-check mb-3">
                    <input id="remember" type="checkbox" name="remember" class="form-check-input">
                    <label for="remember" class="form-check-label">{{ __('common.remember') }}</label>
                </div>
                <button type="submit" class="btn btn-primary w-100">{{ __('common.login') }}</button>
            </form>
        </div>
    </div>
@endsection
