{{-- Layout trang quản trị (đã đăng nhập): header + sidebar + nội dung (@yield('content')) + footer. --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name'))</title>
    @include('layouts.partials.assets')
</head>
<body>
    @include('layouts.partials.header')
    <div class="d-flex">
        @include('layouts.partials.sidebar')
        <main class="flex-grow-1 p-4">
            @yield('content')
        </main>
    </div>
    @include('layouts.partials.footer')
</body>
</html>
