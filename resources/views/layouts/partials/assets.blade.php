{{-- Chỉ nạp asset Vite khi đã chạy "npm run dev" (public/hot) hoặc "npm run build" (manifest),
     để trang vẫn mở được khi máy chưa cài Node. --}}
@if (file_exists(public_path('hot')) || file_exists(public_path('build/manifest.json')))
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
@endif
