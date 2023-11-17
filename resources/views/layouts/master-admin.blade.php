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
    <header id="header">
        <div id="logo-group">
            <span id="logo">
                <img src="<?php echo asset('/img/logo2.png')?>" alt="">
            </span>
        </div>
            <div class="project-context hidden-xs dropdown" id="dropdown">
                <span class="label">Liên kết</span>
                <span class="project-selector" for="dropdown-toggle" > Trang thông tin <i class="fa fa-angle-down"></i>
                </span>
                <ul class="dropdown-menu ng-star-inserted" id="dropdown-menu">
                    <li>
                      <a href="https://www.hutech.edu.vn/" target="_blank">
                        <i class="fa-solid fa-earth-asia"></i> Trang chủ HUTECH</a>
                    </li>
                    <li>
                      <a href="http://qlcntt.hutech.edu.vn/ITHUTECH/Index" target="_blank"><i class="fa-solid fa-earth-asia"></i> Trung tâm QLCNTT</a>
                    </li>
                    <li class="divider"></li>
                    <li>
                      <a href="https://www.facebook.com/hutechuniversity" target="_blank">
                        <i class="fa-brands fa-facebook"></i>  HUTECH</a>
                    </li>
                    <li>
                      <a href="https://www.facebook.com/hutech.itcenter/" target="_blank">
                        <i class="fa-brands fa-facebook"></i>  Trung tâm QLCNTT</a>
                    </li>
                </ul>
            </div>
    </header>
    <aside id="left-panel">
        <div class="login-info">
            <span class="ng-star-inserted">
                <a>
                    <?php
                        if(session()->exists('teacherid'))
                        {
                            $getInfoFromObject = DB::table('giang_vien')->where('MSGV',session()->get('teacherid'))->first();
                        }
                        if($getInfoFromObject->HinhDaiDien == null)
                        {
                            $imgAvatar = 'ori-ava.png';
                        }
                        else{
                            $imgAvatar = $getInfoFromObject->HinhDaiDien;
                        }
                    ?>
                    <img alt="" class="online" src="{{asset('img/Avatar/'.$imgAvatar)}}">
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
        <nav>
            <ul>
                <!-- <li>
                    <a title="Trang chủ" href="/trang-chu">
                        <i class="fa fa-lg fa-fw fa-home"></i>
                        <span class="menu-item-parent">Trang chủ</span>
                    </a>
                </li> -->
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

                <!-- <li>
                    <a href="">
                        <i class="fa fa-lg fa-fw fa-cog"></i>
                        <span class="menu-item-parent"> Cài đặt tài khoản </span>
                    </a>
                </li> -->
                <!-- <li>
                    <a title="Hỗ trợ" href="/ho-tro">
                        <i class="fa fa-lg fa-fw fa-phone"></i>
                        <span class="menu-item-parent">Hỗ trợ</span>
                    </a>
                </li> -->
                <li>
                    <a  href="/logout"  title="Đăng xuất">
                        <i class="fa fa-arrow-circle-left hit"></i>
                        <span class="menu-item-parent">Đăng xuất</span>
                    </a>
                </li>
            </ul>
        </nav>
    </aside>

    <div id="main" role="main">
        @yield('content')
    </div>
    <script src="<?php echo asset('/js/script.js')?>"></script>
</body>
</html>
