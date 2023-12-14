<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/jpg" href="{{ asset('/img/logo/hutech-favicon.jpg') }}" width="50%" />
    <link rel="stylesheet" href="{{ asset('/css/login.css') }}">
    <link rel="stylesheet"
        href="{{ asset('/fonts/fontawesome-free-6.2.1-web/fontawesome-free-6.2.1-web/css/all.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('/css/smartadmin-production.min.css') }}">
    <title>Xác nhận người dùng</title>
</head>

<body>
    <div id="extr-page">
        <header class="animated fadeInDown" id="header">
            <div id="logo-group">
                <span id="logo">
                    <img alt="HUTECH" src="{{ asset('/img/logo.png') }}">
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
                                <h4 class="paragraph-header">Kênh thông tin dành cho sinh viên. </h4>
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
                            <img alt="" class="pull-right display-image"
                                src="{{ asset('/img/iphoneview.png') }}" style="width:210px">
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
                                    <img alt="Tải trên App store" class="img-responsive"
                                        src="{{ asset('/img/ios.png') }}">
                                </a>
                            </div>
                            <div class="col-xs-4 col-sm-4 col-md-3 col-lg-3">
                                <a href="https://play.google.com/store/apps/details?id=hutech.edu.vn714799&amp;hl=vi">
                                    <img alt="Tải trên CH Play" class="img-responsive"
                                        src="{{ asset('/img/android.png') }}">
                                </a>
                            </div>
                            <div class="col-xs-2 col-sm-2 col-md-3 col-lg-3"></div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-5 col-lg-4">
                        <div class="well no-padding">
                            <form action="/Confirmed-change-password-from-mail" method="post"
                                class="smart-form client-form ng-dirty ng-touched ng-valid" novalidate="">

                                <header>Xác thực & thay đổi mật khẩu</header>
                                @if (session('error-change'))
                                    {{-- {{dd(session('error'))}} --}}
                                    <div class="alert alert-danger text-center">{{ session('error-change') }}</div>
                                @endif
                                <fieldset>
                                    <section>
                                        <label class="label">Tài khoản</label>
                                        <label class="input">
                                            <i class="icon-append fa fa-user"></i>
                                            <input class="form-control login-form ng-dirty ng-valid ng-touched"
                                                name="username" ngmodel="" value="{{ $ChangePassForUser }}"
                                                required="" type="text" readonly>
                                            <b class="tooltip tooltip-top-right">
                                                <i class="fa fa-user txt-color-teal"></i> Vui lòng điền tài khoản đăng
                                                nhập </b>
                                        </label>
                                    </section>
                                    <section>
                                        <label class="label">Mật mã</label>
                                        <label class="input">
                                            <i class="icon-append fa fa-lock"></i>
                                            <input class="form-control login-form" name="password" required="" type="password" id="passwordInput">
                                            @error('password')
                                                <div class="alert alert-danger">{{ $errors->first('password') }}</div>
                                            @enderror
                                            <b class="tooltip tooltip-top-right">
                                                <i class="fa fa-lock txt-color-teal"></i> Nhập mật mã của bạn </b>
                                        </label>
                                        <input id="show-password" type="checkbox" onclick="showPassword()"> Hiện mật khẩu
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
                                    </section>
                                    <section>
                                        <label class="label">Xác nhận mật mã</label>
                                        <label class="input">
                                            <i class="icon-append fa fa-lock"></i>
                                            <input class="form-control login-form ng-dirty ng-valid ng-touched"
                                                name="passwordverify" required="" type="password" id="passwordInput1">
                                            @error('passwordverify')
                                                <div class="alert alert-danger">{{ $errors->first('passwordverify') }}
                                                </div>
                                            @enderror
                                            <b class="tooltip tooltip-top-right">
                                                <i class="fa fa-lock txt-color-teal"></i> Nhập mật mã của bạn </b>
                                        </label>
                                        <input id="show-password1" type="checkbox" onclick="showPassword1()"> Hiện mật khẩu
                                        <script>
                                            function showPassword1()
                                            {
                                                const passwordField1 = document.getElementById('passwordInput1');
                                                const showPassword1 = document.getElementById('show-password1');
                                                if (showPassword1.checked)
                                                {
                                                    passwordField1.type = "text";
                                                }
                                                else
                                                {
                                                    passwordField1.type = "password";
                                                }
                                            }
                                        </script>
                                    </section>
                                    <section>
                                        <!--<div class="note"> Đăng nhập không được?
                                                <a href="http://qlcntt.hutech.edu.vn/ho-tro?tieu_de=tai%20khoan">Xem hướng dẫn tại đây</a>
                                            </div>-->
                                    </section>
                                </fieldset>
                                <footer>
                                    <button class="btn btn-primary" type="submit"> Xác nhận thay đổi </button>
                                </footer>
                                @csrf
                            </form>
                        </div>
                        <ul class="list-inline text-center socials-list">
                            <style>
                                .socials-list
                                {
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
</body>

</html>
