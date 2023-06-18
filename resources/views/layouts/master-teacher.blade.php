<?php
 use Carbon\Carbon;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo asset('/css/index.css')?>">
    <link rel="stylesheet" href="<?php echo asset('/fonts/fontawesome-free-6.2.1-web/fontawesome-free-6.2.1-web/css/all.css')?>">
    <link href="<?php echo asset('https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css')?>" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="<?php echo asset('/css/class-list.css')?>">
    <link rel="shortcut icon" type="image/jpg" href="{{asset('/img/logo/hutech-favicon.jpg')}}" width="50%" />
    <title>Trang chủ</title>
</head>
<body>
    <aside id="left-panel">
        <div class="login-info">
            <span class="ng-star-inserted">
                <a>
                    <img alt="" class="online" src="/assets/img/avatar.png">
                    <span>
                        <?php
                            $studentname =session()->get('name');
                            if($studentname)
                            {
                                echo $studentname;
                            }
                        ?>
                    </span>
                    <i class="fa fa-angle-down"></i>
                </a>
            </span>
        </div>
        @if(session()->get('ChucVu') == 'GV')
        <nav>
            <ul>
                <li>
                    <a title="Trang chủ" href="/trang-chu">
                        <i class="fa fa-lg fa-fw fa-home"></i>
                        <span class="menu-item-parent">Trang chủ</span>
                    </a>
                </li>
                <li>
                    <a title="Hồ sơ cá nhân" href="">
                        <i class="fa fa-lg fa-fw fa-user"></i>
                        <span class="menu-item-parent">Hồ sơ cá nhân</span>
                    </a>
                </li>
                <li class="open">
                    <a href="" title="Học tập">
                        <i class="fa fa-lg fa-fw fa-book"></i>
                        <span class="menu-item-parent">Hồ sơ giảng dạy </span>
                        <b class="collapse-sign">
                            <em class="fa fa-minus-square-o"></em>
                        </b>
                    </a>
                </li>
                <li>
                    <a href="">
                        <i class="fa fa-lg fa-fw fa-cog"></i>
                        <span class="menu-item-parent"> Cài đặt tài khoản </span>
                    </a>
                </li>
                <li>
                    <a title="Hỗ trợ" href="/ho-tro">
                        <i class="fa fa-lg fa-fw fa-phone"></i>
                        <span class="menu-item-parent">Hỗ trợ</span>
                    </a>
                </li>

                {{-- <li class="open">
                    <a href="/quan-ly-sinh-vien" title="Học tập">
                        <i class="fa fa-lg fa-fw fa-book"></i>
                        <span class="menu-item-parent">Danh Mục Quản lý</span>
                        <b class="collapse-sign">
                            <em class="fa fa-minus-square-o"></em>
                        </b>
                    </a>
                </li> --}}

                <li>
                    <a  href="/logout"  title="Đăng xuất" >
                        <i class="fa fa-arrow-circle-left hit"></i>
                        <span class="menu-item-parent">Đăng xuất</span>
                    </a>
                </li>
            </ul>
        </nav>
        @endif
        @if(session()->get('ChucVu') == 'QL' || session()->get('ChucVu') == 'AM')
        <nav>
            <ul>
                @if(session()->get('ChucVu') == 'AM')
                    <li>
                        <a title="Hồ sơ cá nhân" href="/quan-ly-sinh-vien">
                            <i class="fa fa-lg fa-fw fa-user"></i>
                            <span class="menu-item-parent">Quản lý sinh viên</span>
                        </a>
                    </li>
                    <li class="open">
                        <a href="/quan-ly-lop-hoc" title="Học tập">
                            <i class="fa fa-lg fa-fw fa-book"></i>
                            <span class="menu-item-parent">Quản lý lớp học</span>
                            <b class="collapse-sign">
                                <em class="fa fa-minus-square-o"></em>
                            </b>
                        </a>
                    </li>
                    <li class="open">
                        <a href="/quan-ly-gv" title="Giảng viên">
                            <i class="fa fa-lg fa-fw fa-book"></i>
                            <span class="menu-item-parent">Quản lý giảng viên</span>
                            <b class="collapse-sign">
                                <em class="fa fa-minus-square-o"></em>
                            </b>
                        </a>
                    </li>
                    <li class="open">
                        <a href="/them-lop-nien-khoa" title="Lớp niên khóa">
                            <i class="fa fa-lg fa-fw fa-book"></i>
                            <span class="menu-item-parent">Thêm Lớp Niên Khóa</span>
                            <b class="collapse-sign">
                                <em class="fa fa-minus-square-o"></em>
                            </b>
                        </a>
                    </li>
                    <li class="open">
                        <a href="/test-excel" title="Học tập">
                            <i class="fa fa-lg fa-fw fa-book"></i>
                            <span class="menu-item-parent">Thêm vào danh sách lớp</span>
                            <b class="collapse-sign">
                                <em class="fa fa-minus-square-o"></em>
                            </b>
                        </a>
                    </li>
                @endif
                <li class="open">
                    <a href="/danh-sach-lop" title="Học tập">
                        <i class="fa fa-lg fa-fw fa-book"></i>
                        <span class="menu-item-parent">Danh sách lớp</span>
                        <b class="collapse-sign">
                            <em class="fa fa-minus-square-o"></em>
                        </b>
                    </a>
                </li>
                <li>
                    <a  href="/logout"  title="Đăng xuất" >
                        <i class="fa fa-arrow-circle-left hit"></i>
                        <span class="menu-item-parent">Đăng xuất</span>
                    </a>
                </li>
            </ul>
        </nav>
        @endif
        <span class="minifyme">
                <i class="fa fa-arrow-circle-left hit"></i>
        </span>
    </aside>

    <div id="main" role="main">
        @yield('content')
    </div>

    <script src="<?php echo asset('/js/pop-up.js')?>"></script>
</body>
</html>
