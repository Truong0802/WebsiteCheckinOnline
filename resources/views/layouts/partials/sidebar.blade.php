{{-- Menu trái: thêm link module mới tại đây. --}}
<aside class="app-sidebar bg-light border-end p-3">
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active fw-bold' : '' }}" href="{{ route('dashboard') }}">
                {{ __('common.dashboard') }}
            </a>
        </li>
    </ul>
</aside>
