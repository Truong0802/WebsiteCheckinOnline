<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- Start Add icon for head of website --}}
    <link rel="shortcut icon" type="image/jpg" href="{{ asset('/img/logo/logo-hutech.png') }}" width="50%" />
    {{-- End Add icon for head of website --}}
    <link rel="stylesheet" href="<?php echo asset('/css/login.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('/fonts/fontawesome-free-6.2.1-web/fontawesome-free-6.2.1-web/css/all.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css'); ?>">
    <link href="<?php echo asset('https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet'); ?>" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ"
        crossorigin="anonymous">
    <link rel="stylesheet" href="<?php echo asset('/css/smartadmin-production.min.css'); ?>">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <title>HUTECH | DIEM DANH</title>
</head>

<body onload="getLocation()">
    <div class="loader-container">
        <div class="inner one"></div>
        <div class="inner two"></div>
        <div class="inner three"></div>
    </div>
    <script>
        $(window).on('load', function() {
            $(".loader-container").fadeOut(1000);
            $(".extr-page").fadeIn(1000);
        })
    </script>
    <div class="extr-page" id="extr-page">
        <header class="animated fadeInDown" id="header">
            <div id="logo-group">
                <span id="logo">
                    <img alt="HUTECH" src="<?php echo asset('/img/logo.png'); ?>">
                </span>
            </div>
            <span id="extr-page-header-space"></span>
        </header>

        <div class="animated fadeInDown" id="main" role="main">
            <div class="container" id="content">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-7 col-lg-8 hidden-xs hidden-sm">
                        <h1 class="txt-color-red login-header-big">Cổng thông tin sinh viên</h1>
                        <div class="hero">
                            <div class="pull-left login-desc-box-l">
                                <!-- <h4 class="paragraph-header">Kênh thông tin dành cho sinh viên. </h4> -->
                                <div class="login-app-icons">
                                    <style>
                                        .btn-info {
                                            color: #fff;
                                            background-color: #57889c;
                                            border-color: #4e7a8c;
                                        }

                                        .btn-info:hover {
                                            color: #fff;
                                            background-color: #456b7b;
                                            border-color: #456b7b;
                                        }
                                    </style>
                                    <a class="btn btn-info btn-sm" href="https://www.hutech.edu.vn/"
                                        target="_blank">Trang chủ HUTECH</a>
                                    <a class="btn btn-info btn-sm" href="http://qlcntt.hutech.edu.vn"
                                        target="_blank">TT. QLCNTT</a>
                                </div>
                            </div>
                            <img alt="" class="pull-right display-image" src="<?php echo asset('/img/iphoneview.png'); ?>"
                                style="width:210px">
                        </div>
                        <div class="row">
                            <div class="col-xs-2 col-sm-2 col-md-3 col-lg-3"></div>
                            <style>
                                .img-responsive {
                                    max-width: 100%;
                                }
                            </style>
                            <div class="col-xs-4 col-sm-4 col-md-3 col-lg-3">
                                <a href="https://itunes.apple.com/us/app/e-hutech/id1237567424?ls=1&amp;mt=8">
                                    <img alt="Tải trên App store" class="img-responsive" src="<?php echo asset('/img/ios.png'); ?>">
                                </a>
                            </div>
                            <div class="col-xs-4 col-sm-4 col-md-3 col-lg-3">
                                <a href="https://play.google.com/store/apps/details?id=hutech.edu.vn714799&amp;hl=vi">
                                    <img alt="Tải trên CH Play" class="img-responsive" src="<?php echo asset('/img/android.png'); ?>">
                                </a>
                            </div>
                            <div class="col-xs-2 col-sm-2 col-md-3 col-lg-3"></div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-5 col-lg-4">
                        <div class="well no-padding">
                            <form action="/account-login" method="post"
                                class="smart-form client-form ng-dirty ng-touched ng-valid" novalidate="">

                                <header>Đăng nhập</header>
                                <fieldset>
                                    <section>
                                        @if (session('error-Login'))
                                            {{-- {{dd(session('error'))}} --}}
                                            <div class="alert alert-danger text-center">{{ session('error-Login') }}
                                            </div>
                                        @endif

                                        <label class="label">Tài khoản</label>
                                        <label class="input">
                                            <i class="icon-append fa fa-user"></i>
                                            <input id="username"
                                                class="form-control login-form ng-dirty ng-valid ng-touched"
                                                name="username" ngmodel="" required="" type="text">
                                            @error('username')
                                                <div class="alert alert-danger">{{ $errors->first('username') }}</div>
                                            @enderror
                                            <b class="tooltip tooltip-top-right">
                                                <i class="fa fa-user txt-color-teal"></i> Vui lòng điền tài khoản đăng
                                                nhập </b>
                                        </label>
                                    </section>
                                    <section>
                                        <label class="label">Mật mã</label>
                                        <label class="input">
                                            <i class="icon-append fa fa-lock"></i>
                                            <input class="form-control login-form ng-dirty ng-valid ng-touched"
                                                name="password" ngmodel="" required="" type="password"
                                                id="passwordInput">
                                            {{-- Đổi dấu pasword --}}
                                            {{-- <script>
                                                    const passwordInput = document.getElementById('passwordInput');

                                                    passwordInput.addEventListener('input', function() {
                                                    const value = this.value;
                                                    const maskedValue = value.replace(/./g, '*');
                                                    this.value = maskedValue;
                                                    });
                                                </script> --}}
                                            @error('password')
                                                <div class="alert alert-danger">{{ $errors->first('password') }}</div>
                                            @enderror
                                            <b class="tooltip tooltip-top-right" id="password-field">
                                                <i class="fa fa-lock txt-color-teal"></i> Nhập mật mã của bạn </b>
                                        </label>
                                        <input id="show-password" type="checkbox" onclick="showPassword()"> Hiện mật
                                        khẩu
                                    </section>
                                    {{-- <section>
                                            <style>
                                                .select-form
                                                {
                                                    font-size: 13px;
                                                    width: 100%;
                                                    height: 32px;
                                                    border: 1px solid #ccc;
                                                }
                                            </style>
                                            <select class="select-form" name="app_key">
                                                <option value="0: null">-- Chọn phân hệ --</option>
                                                <option value="1: MOBILE_HUTECH">Đại học - Cao đẳng </option>
                                                <option value="2: VQT-OUM">Viện quốc tế - OUM </option>
                                                <option value="3: VQT-LINCOLN">Viện quốc tế - LINCOLN </option>
                                                <option value="4: VIEN_DTTX">Viện hợp tác và phát triển đào tạo </option>
                                            </select>
                                        </section> --}}
                                    <section>
                                        <div class="note" style="font-size: 13px;"> Quên mật khẩu ?
                                            {{-- <a href="http://qlcntt.hutech.edu.vn/ho-tro?tieu_de=tai%20khoan">Xem hướng
                                                dẫn tại đây</a> --}}
                                                <a href="/forgot-password">Thay đổi mật khẩu tại đây</a>
                                            </div>
                                    </section>
                                </fieldset>
                                <footer>
                                    <button disabled id="login-btn" class="btn btn-primary" type="submit"> Đăng nhập
                                    </button>
                                    {{-- <button id="login-btn" class="btn btn-primary" type="submit"> Đăng nhập </button> --}}
                                </footer>
                                @csrf
                            </form>
                        </div>
                        <ul class="list-inline text-center socials-list">
                            <style>
                                .socials-list {
                                    display: flex;
                                    justify-content: center;
                                    margin-top: 10px;
                                    gap: 10px;
                                }
                            </style>
                            <li>
                                <a class="btn btn-primary btn-circle" href="https://www.facebook.com/hutech.itcenter/"
                                    target="_blank"><i class="fa-brands fa-facebook-f"></i>
                                </a>
                            </li>
                            <li>
                                <a class="btn btn-danger btn-circle"
                                    href="https://www.youtube.com/channel/UCICDAfLAzWgTrMJrDLcMZWw" target="_blank"><i
                                        class="fa-brands fa-youtube"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <script src="<?php echo asset('/js/location.js'); ?>"></script>
        {{-- <script src="<?php echo asset('/js/script.js'); ?>"></script> --}}
        <script>
            function showPassword()
            {
                const passwordField = document.getElementById('passwordInput');
                const showPassword = document.getElementById('show-password');
                if (showPassword.checked)
                {
                    passwordField.type = "text";
                }
                else
                {
                    passwordField.type = "password";
                }
            }
        </script>
</body>

</html>
