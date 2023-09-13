<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/css/index.css">
    <link rel="stylesheet" href="/assets/css/student-info.css">
    <link rel="stylesheet" href="/assets/fonts/fontawesome-free-6.2.1-web/fontawesome-free-6.2.1-web/css/all.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <title>Document</title>
</head>
<body>
    <aside id="left-panel">
        <div class="login-info">
            <span class="ng-star-inserted">
                <a>
                    <img alt="" class="online" src="/assets/img/avatar.png">
                    <span>Hồ Phú Tài</span>
                    <i class="fa fa-angle-down"></i>
                </a>
            </span>
        </div>
        <nav>
            <ul>
                <li>
                    <a title="Trang chủ" href="">
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
                        <span class="menu-item-parent">Học tập </span>
                        <b class="collapse-sign">
                            <em class="fa fa-minus-square-o"></em>
                        </b>
                    </a>
                </li>
                <li>
                    <a title="Xác nhận online" href="#/sinhvien/xac-nhan-sinh-vien">
                        <i class="fa fa-lg fa-fw fa-calendar-o"></i>
                        <span class="menu-item-parent">Xác nhận online</span>
                    </a>
                </li>
                <li>
                    <a href="" title="Đánh giá rèn luyện">
                        <i class="fa fa-lg fa-fw fa-balance-scale"></i>
                        <span class="menu-item-parent">Đánh giá rèn luyện</span>
                        <b class="collapse-sign">
                            <em class="fa fa-plus-square-o"></em>
                        </b>
                    </a>
                    <ul>
                        <li>
                            <a href="">Điểm cá nhân</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="" title="Khảo sát">
                        <i class="fa fa-lg fa-fw fa-calendar-check-o"></i>
                        <span class="menu-item-parent">Khảo sát</span>
                        <b class="collapse-sign">
                            <em class="fa fa-plus-square-o"></em>
                        </b>
                    </a>
                    <ul>
                        <li>
                            <a title="Hoạt động giảng dạy" href="">Hoạt động giảng dạy</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="">
                        <i class="fa fa-lg fa-fw fa-bar-chart-o"></i>
                        <span class="menu-item-parent">Hoạt động sinh viên</span>
                        <b class="collapse-sign">
                            <em class="fa fa-plus-square-o"></em>
                        </b>
                    </a>
                    <ul>
                        <li>
                            <a href="">Hồ sơ SINH VIÊN 5 TỐT</a>
                        </li>
                        <li class="">
                            <a href="">Ghi nhận tham gia hoạt động</a>
                        </li>
                        <li>
                            <a href="">Tài liệu, Thông tin hoạt động</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="">
                        <i class="fa fa-lg fa-fw fa-cog"></i>
                        <span class="menu-item-parent"> Cài đặt tài khoản </span>
                    </a>
                </li>
                <li>
                    <a href="#" title="Khác">
                        <i class="fa fa-lg fa-fw fa-bars"></i>
                        <span class="menu-item-parent">Khác</span>
                        <b class="collapse-sign">
                            <em class="fa fa-plus-square-o"></em>
                        </b>
                    </a>
                    <ul>
                        <li>
                            <a href=>COVID Khai báo y tế </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a title="Hỗ trợ" href="">
                        <i class="fa fa-lg fa-fw fa-phone"></i>
                        <span class="menu-item-parent">Hỗ trợ</span>
                    </a>
                </li>
            </ul>
        </nav>
        <span class="minifyme">
            <i class="fa fa-arrow-circle-left hit"></i>
        </span>
    </aside>

    <div id="main" role="main">
        <div id="ribbon">
            <span class="ribbon-button-alignment">
                <span class="btn btn-ribbon" id="refresh" placement="bottom">
                    <i class="fa fa-refresh"></i>
                </span>
            </span>
            <ol class="breadcrumb">
                <li class="ng-star-inserted">
                    <a>Danh sách sinh viên</a>
                </li>
            </ol>
        </div>
        <div class="mt-4" id="content">
            <div class="  mx-4">
                <div class="row mb-3">
                    <div class="col-md-6 pr-0">
                        <div>
                            <h1 class="page-title txt-color-blueDark">
                                <i class="fa fa-lg fa-fw fa-user"></i> Thông tin sinh viên
                            </h1>
                        </div>
                    </div>
                </div>
            </div>

            <style>
                .inner-class .custom-info
                {
                    margin-left: 20px;
                }

                .custom-info .custom-list-li .info
                {
                    font-weight: bold;
                }
            </style>

            <div class="inner-class">
                <div class="row well m-3">
                    <div class="col-md-3 custom-avatar p-0 m-0 mt-4 mb-4 mx-4" style = "width: 150px;">
                        <img alt="" class="online" src="/assets/img/avatar.png">
                    </div>
                    <section class="col-md-9 custom-info mt-4 mb-4 mx-4">
                        <ul class="list-unstyled custom-list-li">
                            <li>Họ tên:
                                <span class="info">Hồ Phú Tài</span>
                            </li>
                            <li>Mã số sinh viên:
                                <span class="info">2011060957</span>
                            </li>
                            <li>Chương trình:
                                <span class="info">Chưa cập nhật</span>
                            </li>
                            <li>Hệ đào tạo:
                                <span class="info">Chưa cập nhật</span>
                            </li>
                            <li>Khoa:
                                <span class="info">Khoa Công Nghệ Thông Tin</span>
                            </li>
                            <li>Lớp:
                                <span class="info">20DTHA2</span>
                            </li>
                            <li> Niên khóa:
                                <span class="info">2020 - 2024</span>
                            </li>
                        </ul>
                    </section>
                </div>
            </div>


        </div>
    </div>
</body>
</html>
