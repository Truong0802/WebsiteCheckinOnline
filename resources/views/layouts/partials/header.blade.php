{{-- Thanh điều hướng trên cùng: tên ứng dụng + nút đăng xuất (POST kèm CSRF). --}}
<nav class="navbar navbar-dark bg-dark px-3">
    <a class="navbar-brand" href="{{ route('dashboard') }}">{{ config('app.name') }}</a>
    <div class="d-flex align-items-center gap-3 text-white">
        {{-- Tài khoản đăng nhập là App\Models\Account (không có cột name) nên hiển thị username. --}}
        <span>{{ auth()->user()?->username }}</span>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-outline-light btn-sm">{{ __('common.logout') }}</button>
        </form>
    </div>
</nav>
