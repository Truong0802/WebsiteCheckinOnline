# VTZ_TeamPj

Source trắng Laravel 13, dựng theo kiến trúc **`Laravel_Server` của vjp-connect** (`E:\PHP\vjp-connect\Laravel_Server`) nhưng đã bỏ hết module nghiệp vụ (Company, Expert, Consultant, Bookmark, FAQ...). Source giữ lại phần khung để team bắt đầu thêm module mới:

- **API** xác thực bằng **JWT** (`php-open-source-saver/jwt-auth`, Bearer token), dữ liệu tài khoản ở bảng **`accounts`**, hỗ trợ nhiều loại tài khoản qua `account_type`.
- **Tài liệu API tự sinh** kiểu Swagger tại `/docs/api` (`dedoc/scramble`, chỉ mở khi `APP_ENV=local`).
- **Repository pattern** tự bind interface, phân trang/lọc/sắp xếp bằng `spatie/laravel-query-builder`.
- **Service layer** (`AuthenticationService`) để giữ nghiệp vụ, controller chỉ nhận request và trả response.
- **Lỗi API** trả JSON theo một format chung (`ApiExceptionHandler`).
- **Đa ngôn ngữ** `vi` / `en` / `ja` qua header `X-localization`.
- **Trang quản trị Blade + Bootstrap 5** (đăng nhập bằng session): login, dashboard, logout.

---

## 1. Công nghệ

| Thành phần | Phiên bản |
| --- | --- |
| PHP | `^8.3`, **bắt buộc extension `sodium`** cho JWT (máy hiện tại: 8.5.12, Laravel Herd; Herd Lite không có sodium) |
| php-open-source-saver/jwt-auth | `^2.10` (JWT, guard `api`) |
| dedoc/scramble | `^0.13.53` (tài liệu API `/docs/api`) |
| Laravel Framework | `^13.17` (đang cài: 13.40.0) |
| laravel/sanctum | `^4.3` (vẫn cài nhưng không còn dùng cho API) |
| spatie/laravel-query-builder | `^7.3` |
| bensampo/laravel-enum | `^6.14` |
| Frontend | Vite 7 + laravel-vite-plugin + Bootstrap 5 + Sass |
| Test | PHPUnit 12 (SQLite in-memory) |
| Database mặc định | SQLite (`database/database.sqlite`), có thể đổi sang MySQL trong `.env` |

So với vjp-connect: framework lên Laravel 13, spatie query-builder lên v7 (truyền tham số dạng variadic), không còn S3/Mail service và các model nghiệp vụ.

---

## 2. Cấu trúc thư mục chính

```
app/
├── Contracts/
│   ├── Repositories/   EloquentRepositoryInterface, UserRepositoryInterface
│   └── Services/       AuthenticationServiceInterface
├── Enums/              AuthEnum (loại tài khoản), LanguageEnum (vi/en/ja)
├── Exceptions/         ApiExceptionHandler (JSON lỗi), NoContentException (204)
├── Http/
│   ├── Controllers/
│   │   ├── Controller.php          Controller web nền (middleware is-web)
│   │   ├── Auth/LoginController    Đăng nhập/đăng xuất web (session)
│   │   └── Api/
│   │       ├── Controller.php      Controller API nền
│   │       └── Auth/AuthController login/register/profile/logout API
│   ├── Middleware/     IsWeb, LocalizationMiddleware, MultiAuthorMiddleware
│   ├── Requests/       Auth/LoginRequest (web), Api/GuestRequest, Api/Auth/{Login,Register}Request
│   └── Resources/      Auth/AuthResource
├── Models/             User (HasApiTokens)
├── Providers/          AppServiceProvider, AuthServiceProvider, RepositoryServiceProvider
├── Repositories/       EloquentRepository (abstract), UserRepository, Traits/{HasFilters,HasIncludes,HasMakeBuilder,HasSorts}
└── Services/           AuthenticationService
bootstrap/app.php       Khai báo routing, middleware, xử lý exception
config/auth.php         Guard 'web' (session) + 'api' (jwt), provider 'accounts' (App\Models\Account)
config/jwt.php          TTL, refresh TTL, blacklist của JWT
config/scramble.php     Tài liệu API /docs/api
lang/{en,vi,ja}/        message.php (thông điệp API), common.php (nhãn giao diện), auth.php
resources/views/        layouts (app, guest, partials), auth/login, dashboard, welcome
resources/sass, js      Bootstrap + axios (build bằng Vite)
routes/
├── web.php             /, /login, /dashboard, /logout
├── api.php             /api/* (thêm module vào group auth:api)
├── auth.php            /api/auth/*
└── console.php         Lịch dọn token Sanctum hết hạn
tests/Feature/          ExampleTest (web), Api/AuthApiTest, UserRepositoryTest
```

---

## 3. Luồng xử lý

### 3.1. Vòng đời một request

```
HTTP Request
  → public/index.php → bootstrap/app.php
  → Global middleware: LocalizationMiddleware (đặt ngôn ngữ) → MultiAuthorMiddleware (chọn guard đang đăng nhập)
  → Route (web.php | api.php | auth.php) + route middleware (auth, auth:api, guest, throttle)
  → FormRequest (validate; lỗi → 422)
  → Controller (mỏng) → Service / Repository → Model (Eloquent)
  → Resource (JsonResource) hoặc View (Blade)
  → Response
Lỗi phát sinh ở bất kỳ bước nào với URL /api/* → ApiExceptionHandler → JSON {status:false, message, errors?}
```

### 3.2. Luồng API xác thực (JWT, bảng `accounts`)

| Method | URL | Middleware | Mô tả |
| --- | --- | --- | --- |
| POST | `/api/auth/register` | `throttle:10,1` | Đăng ký tài khoản (bảng `accounts`) |
| POST | `/api/auth/login` | `throttle:10,1` | Đăng nhập bằng `username`, nhận JWT |
| GET | `/api/auth/profile` | `auth:api` | Thông tin tài khoản hiện tại |
| POST | `/api/auth/refresh` | `auth:api` | Đổi JWT còn hạn lấy JWT mới |
| POST | `/api/auth/logout` | `auth:api` | Thu hồi JWT hiện tại (blacklist) |

1. `AuthController::__construct()` khai báo map `account_type → Model` (hiện tại `AuthEnum::ACCOUNT = 1 → Account`).
2. **Register**: `RegisterRequest` validate (`username` bắt buộc và duy nhất, `email` tùy chọn nhưng duy nhất, `phone` tùy chọn, `password` tối thiểu 8 ký tự, `confirm_password`, `account_type`) → `AuthenticationService::register()` chọn model theo `account_type` và `create()` (chỉ nhận `username`, `phone`, `email`, `password` theo `$fillable`; password tự hash nhờ cast `hashed`; `status` mặc định 1) → trả `AuthResource` (HTTP 201).
3. **Login**: `LoginRequest` validate → `AuthenticationService::login()` → `attempt()` tìm theo `username` với `status = 1` và `Hash::check` → đúng thì cấp JWT qua `auth('api')->login()`:
   ```json
   { "token_type": "Bearer", "access_token": "eyJ0eXAiOiJKV1Qi...", "expired_at": "2026-11-11 12:00:00" }
   ```
   Sai username/mật khẩu, tài khoản bị khóa (`status` khác 1) hoặc sai `account_type` đều trả **401** cùng một message (không tiết lộ lý do).
4. **Profile / Refresh / Logout**: client gửi header `Authorization: Bearer {access_token}`.
   - `refresh` cấp token mới, token cũ vào blacklist. Token phải **còn hạn** (route nằm trong `auth:api`); hết hạn thì đăng nhập lại.
   - `logout` đưa token đang dùng vào blacklist (lưu trong cache `CACHE_STORE`), gọi lại bằng token đó sẽ bị 401.
5. Token sai chữ ký, hết hạn hoặc đã blacklist → **401** theo format lỗi chung.

Ví dụ body đăng nhập:

```json
{ "username": "admin", "password": "password", "account_type": 1 }
```

### 3.3. Luồng web quản trị (session)

1. `GET /login` (middleware `guest`) → `LoginController@create` → view `auth/login`.
2. `POST /login` (field `username`, `password`, `remember`) → `Auth\LoginRequest::authenticate()`: validate, giới hạn **5 lần sai / (username + IP)**, `Auth::guard('web')->attempt()` trên bảng `accounts` với điều kiện `status = 1` → regenerate session → redirect `/dashboard`. Tick "Ghi nhớ" sẽ lưu `accounts.remember_token`.
3. `GET /dashboard` (middleware `auth`) → layout `layouts/app` (header + sidebar + footer).
4. `POST /logout` (form có `@csrf`) → logout, hủy session, đổi CSRF token → về `/`.

Controller web kế thừa `App\Http\Controllers\Controller`, luôn chạy middleware `is-web` để guard mặc định là `web`.

### 3.4. Xử lý lỗi API (`ApiExceptionHandler`)

Format chung:

```json
{ "status": false, "message": "Dữ liệu gửi lên không hợp lệ.", "errors": { "email": ["..."] } }
```

| Exception | HTTP |
| --- | --- |
| `NoContentException` | 204 (body rỗng) |
| `AuthenticationException` | 401 |
| `AuthorizationException`, `AccessDeniedHttpException` | 403 |
| `NotFoundHttpException`, `ModelNotFoundException` | 404 |
| `MethodNotAllowedHttpException` | 405 |
| `ValidationException` | 422 (có `errors`) |
| `HttpException` khác | giữ status gốc |
| Lỗi hệ thống khác | 500; chỉ hiện message chi tiết khi `APP_DEBUG=true` |

> Khác với source gốc vjp-connect: source gốc gọi `__('messages.errors.*')` trong khi file ngôn ngữ tên `message.php`, nên message lỗi không dịch được. Bản này đã dùng thống nhất `message.errors.*`.

### 3.5. Đa ngôn ngữ

- Client gửi header `X-localization: vi | en | ja`. Giá trị không hợp lệ hoặc không gửi thì dùng `APP_LOCALE` trong `.env`.
- Thông điệp API: `lang/{locale}/message.php`; nhãn giao diện: `lang/{locale}/common.php`.

### 3.6. Repository + Query Builder

- `RepositoryServiceProvider` quét `app/Repositories/*Repository.php` và tự bind
  `App\Contracts\Repositories\{Model}RepositoryInterface` → `App\Repositories\{Model}Repository`.
- `EloquentRepository::getWithPagination()` đọc query string và chỉ cho phép field trong allow-list:
  - `?filter[name]=abc` — field khai báo trong `canFilters()` (mặc định có `created_at`, `updated_at`)
  - `?sort=name` / `?sort=-name` — field trong `canSorts()`; mặc định sắp xếp `-{table}.updated_at`
  - `?include=relation` — property `$includes`
  - `?fields[users]=id,name` — property `$fields`
  - Mỗi trang 20 bản ghi (`$_paginate`).

### 3.7. Thêm một module mới (ví dụ `Post`)

1. `php artisan make:model Post -mf` → sửa migration, khai báo `$fillable`.
2. Tạo `app/Contracts/Repositories/PostRepositoryInterface.php` (extends `EloquentRepositoryInterface`) và `app/Repositories/PostRepository.php` (extends `EloquentRepository`, `getModel()` trả `Post::class`, khai báo `canFilters()`/`canSorts()`). Không cần đăng ký thủ công, provider tự bind.
3. Tạo FormRequest trong `app/Http/Requests/Api/Post/`, Resource trong `app/Http/Resources/`.
4. Tạo `app/Http/Controllers/Api/PostController.php` (extends `Api\Controller`), inject `PostRepositoryInterface`.
5. Khai báo route trong group `auth:api` của `routes/api.php`; thêm Policy vào `AuthServiceProvider::boot()` nếu cần kiểm tra quyền sở hữu.
6. Thêm message vào `lang/{vi,en,ja}` và viết test trong `tests/Feature`.

---

## 4. Hướng dẫn chạy source bằng terminal

Các lệnh dưới đây chạy trong PowerShell hoặc Git Bash, tại thư mục project:

```bash
cd E:/PHP/my_project/VTZ_TeamPj
```

### 4.1. Kiểm tra phiên bản PHP, Composer, Laravel trên máy

Phiên bản PHP (cần `>= 8.3`):

```bash
php -v
```

Đường dẫn file php đang dùng (khi máy cài nhiều bản PHP):

```bash
where.exe php
```

Các extension đang bật (cần `pdo_sqlite` hoặc `pdo_mysql`, `mbstring`, `openssl`, `fileinfo`):

```bash
php -m
```

Phiên bản Composer:

```bash
composer --version
```

Phiên bản Laravel Framework của project (chạy trong thư mục project, sau khi đã `composer install`):

```bash
php artisan --version
```

Thông tin chi tiết hơn (Laravel, PHP, môi trường, driver DB/cache/queue):

```bash
php artisan about
```

Phiên bản package `laravel/framework` mà Composer đã cài:

```bash
composer show laravel/framework
```

Phiên bản Laravel Installer cài toàn cục (công cụ `laravel new`, không phải framework):

```bash
laravel --version
```

Node.js và npm (chỉ cần khi build giao diện Bootstrap):

```bash
node -v
```

```bash
npm -v
```

Kết quả trên máy hiện tại (ngày 10/11/2026): PHP 8.5.12, Composer 2.8.12, Laravel Framework 13.40.0. **Máy chưa cài Node.js/npm.**

### 4.2. Cài đặt lần đầu

Cài thư viện PHP:

```bash
composer install
```

Tạo file môi trường (PowerShell dùng `Copy-Item .env.example .env`):

```bash
cp .env.example .env
```

Tạo `APP_KEY`:

```bash
php artisan key:generate
```

Với SQLite (mặc định), tạo file database nếu chưa có (PowerShell dùng `New-Item database/database.sqlite`):

```bash
touch database/database.sqlite
```

Nếu dùng MySQL: sửa trong `.env` các biến `DB_CONNECTION=mysql`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` và tạo database trước.

Tạo secret ký JWT (ghi `JWT_SECRET` vào `.env`; chỉ chạy một lần cho mỗi môi trường, chạy lại với `--force` sẽ làm mọi token đang dùng mất hiệu lực):

```bash
php artisan jwt:secret
```

Tạo bảng (users, sessions, cache, jobs, accounts và các bảng nghiệp vụ, cột `accounts.remember_token`):

```bash
php artisan migrate
```

Tạo tài khoản mẫu trong bảng `accounts`: username `admin` / mật khẩu `password` (chỉ dùng cho môi trường dev):

```bash
php artisan db:seed
```

Hoặc chạy gộp các bước cài đặt PHP bằng script trong `composer.json`:

```bash
composer setup
```

### 4.3. Build giao diện (cần Node.js)

```bash
npm install
```

Build một lần cho môi trường chạy thật:

```bash
npm run build
```

Hoặc chạy Vite dev server (tự reload khi sửa SCSS/JS/Blade), để mở ở một terminal riêng:

```bash
npm run dev
```

Khi chưa build, trang vẫn mở được nhưng chưa có CSS/JS Bootstrap (`layouts/partials/assets.blade.php` bỏ qua `@vite` khi chưa có `public/hot` hoặc `public/build/manifest.json`).

### 4.4. Chạy server

```bash
php artisan serve
```

Mặc định chạy tại `http://127.0.0.1:8000`. Đổi port:

```bash
php artisan serve --port=8080
```

Kiểm tra nhanh:

- `http://127.0.0.1:8000/` → chuyển hướng sang `/docs/api` khi `APP_ENV=local`; môi trường khác hiển thị trang chào
- `http://127.0.0.1:8000/login` → đăng nhập quản trị
- `http://127.0.0.1:8000/up` → health check
- `http://127.0.0.1:8000/docs/api` → tài liệu API, gọi thử (Try It); `http://127.0.0.1:8000/docs/api.json` → OpenAPI JSON

Gọi thử API đăng nhập bằng curl:

```bash
curl -X POST http://127.0.0.1:8000/api/auth/login -H "Accept: application/json" -H "Content-Type: application/json" -H "X-localization: vi" -d "{\"username\":\"admin\",\"password\":\"password\",\"account_type\":1}"
```

Lấy profile (thay `TOKEN` bằng `access_token` vừa nhận):

```bash
curl http://127.0.0.1:8000/api/auth/profile -H "Accept: application/json" -H "Authorization: Bearer TOKEN"
```

Đổi token mới trước khi hết hạn:

```bash
curl -X POST http://127.0.0.1:8000/api/auth/refresh -H "Accept: application/json" -H "Authorization: Bearer TOKEN"
```

Đăng xuất (token bị thu hồi):

```bash
curl -X POST http://127.0.0.1:8000/api/auth/logout -H "Accept: application/json" -H "Authorization: Bearer TOKEN"
```

Trên `/docs/api`: gọi `POST /auth/login` ở panel Try It → copy `access_token` → mở API cần đăng nhập, dán vào ô **Auth → Token** → **Send API Request**.

### 4.5. Lệnh thường dùng khác

Danh sách route (bỏ route của package):

```bash
php artisan route:list --except-vendor
```

Chạy test (dùng SQLite in-memory, không đụng database thật):

```bash
php artisan test
```

Kiểm tra code style (Laravel Pint):

```bash
composer lint:check
```

Tự sửa code style:

```bash
composer lint
```

Xóa cache config/route/view khi sửa `.env` hoặc config mà không thấy thay đổi:

```bash
php artisan optimize:clear
```

Chạy scheduler (lịch `sanctum:prune-expired` cũ vẫn giữ, hiện không có token Sanctum nào được tạo):

```bash
php artisan schedule:work
```

Chạy queue worker (`QUEUE_CONNECTION=database`):

```bash
php artisan queue:work
```

Xem log realtime:

```bash
php artisan pail
```

---

## 5. Ghi chú

- Không commit file `.env` (đã có trong `.gitignore`). Mỗi môi trường có `JWT_SECRET` riêng; lộ secret = ai cũng ký được token, phải tạo lại (`php artisan jwt:secret --force`).
- Thời hạn JWT cấu hình trong `.env`: `JWT_TTL` (phút, mặc định 60), `JWT_REFRESH_TTL` (phút, mặc định 20160 = 14 ngày), `JWT_BLACKLIST_ENABLED` (mặc định `true`, cần để logout/refresh thu hồi token).
- Blacklist lưu trong cache (`CACHE_STORE`); xóa cache (`php artisan cache:clear`) sẽ làm token đã logout dùng lại được tới khi hết hạn.
- Bảng `users` và model `User` vẫn giữ nhưng không dùng để đăng nhập; `UserRepository` chỉ còn là repository mẫu.
- Khi thêm loại tài khoản mới (ví dụ admin): thêm hằng số trong `AuthEnum`, thêm model (implements `JWTSubject`) vào map trong `AuthController`, thêm guard/provider trong `config/auth.php`, và mở rộng `AuthResource::getAuthInfo()`.

---

## 6. Hướng dẫn chuyển xác thực sang JWT + bảng `accounts`

Áp dụng khi lấy source mới về máy đang chạy bản cũ (Sanctum + bảng `users`).

### 6.1. Chuẩn bị PHP có `sodium`

`lcobucci/jwt` (thư viện ký token của jwt-auth) bắt buộc extension `sodium`. Herd Lite **không có** sodium, nên cần cài [Laravel Herd](https://herd.laravel.com/windows) bản đầy đủ hoặc PHP có bật `extension=sodium`. Bỏ `...\.config\herd-lite\bin` khỏi PATH, mở terminal mới rồi kiểm tra:

```bash
where.exe php
```

```bash
php -m | findstr sodium
```

### 6.2. Cài thư viện và cấu hình

```bash
composer install
```

Bổ sung vào `.env` các biến JWT có trong `.env.example` (`JWT_TTL`, `JWT_REFRESH_TTL`, `JWT_BLACKLIST_ENABLED`), rồi tạo secret:

```bash
php artisan jwt:secret
```

Nếu `.env` đang ghi đè `AUTH_MODEL` hoặc `AUTH_PASSWORD_BROKER` theo bản cũ, xóa hai biến đó (giá trị mặc định mới là `App\Models\Account` / `accounts`).

```bash
php artisan optimize:clear
```

### 6.3. Cập nhật database

```bash
php artisan migrate
```

Migration `2026_11_11_000025_add_remember_token_to_accounts_table` thêm cột `remember_token`. Tài khoản cũ trong bảng `users` **không tự chuyển** sang `accounts`: tạo lại tài khoản qua `POST /api/auth/register`, `php artisan db:seed` (tạo `admin`), hoặc tự viết script chuyển dữ liệu nếu cần.

### 6.4. Thay đổi phía client

| Trước | Sau |
| --- | --- |
| Login body `email` | `username` |
| Register body `name`, `email` (bắt buộc) | `username` (bắt buộc), `email`, `phone` (tùy chọn) |
| Token Sanctum dạng `1\|xxxx`, không hết hạn | JWT `eyJ...`, hết hạn sau `JWT_TTL` phút, gọi `/api/auth/refresh` trước khi hết hạn |
| Response `account_type: "user"`, có `name`, `verified_at` | `account_type: "account"`, có `username`, `phone`, `status` |
| Form web login field `email` | field `username` |

Giá trị `account_type = 1` giữ nguyên. Token Sanctum cũ không còn dùng được; client cần đăng nhập lại.

### 6.5. Kiểm tra

```bash
php artisan test
```

Sau đó chạy `php artisan serve` và thử đăng nhập trên `/login`, `/docs/api` (mục 4.4).
