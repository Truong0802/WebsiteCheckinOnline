<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo asset('/css/index.css') ?>">
    <link rel="stylesheet" href="<?php echo asset('/css/class-list.css')?>">
    <link rel="stylesheet" href="<?php echo asset('/fonts/fontawesome-free-6.2.1-web/fontawesome-free-6.2.1-web/css/all.css') ?>">
    <link rel="stylesheet" href="<?php echo asset('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css') ?>">
    <link href="<?php echo asset('https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css')?>" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <title>Danh sách sinh viên</title>
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
                <li>
                    <a  href="/logout"  title="Đăng xuất" href="">
                        <i class="fa fa-arrow-circle-left hit"></i>
                        <span class="menu-item-parent">Đăng xuất</span>
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
                                <i class="fa-fw fa fa-graduation-cap"></i> Danh sách sinh viên
                            </h1>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container">
                <form>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="student-name">Họ tên:</label>
                                <input type="text" class="form-control" id="student-name">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="student-id">MSSV:</label>
                                <input type="text" class="form-control" id="student-id">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="student-serial">STT:</label>
                                <input type="text" class="form-control" id="student-serial">
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="class-name">Tên lớp:</label>
                                <input type="text" class="form-control" id="class-name">
                            </div>
                        </div>
                    </div>
                    <br>
                    <button type="button" class="btn btn-primary" onclick="filterData()">Tìm kiếm</button>
                    <button type="button" class="btn btn-primary" onclick="removeFilterData()">Xóa tất cả bộ lọc</button>
                </form>
            </div>

            <div class="col-md-12 detail">
                <style>
                    .detail
                    {
                        grid-template-columns: 15fr
                    }
                </style>
                <div class="class-list">
                    @foreach($getinfoclass as $key)
                    <span><strong>HỌC PHẦN:</strong>

                        {{-- Lập trình ứng dụng với Java  --}}


                        <?php
                            $classname = DB::table('mon_hoc')->where('MaTTMH',$key->MaTTMH)->distinct()->first();
                        ?>
                        <?php

                        $studentname = DB::table('sinh_vien')->where('MSSV',$key->MSSV)->distinct()->first();
                        ?>
                    @endforeach
                        <?php
                            echo $classname->TenMH;
                        ?>
                    <strong> <?php echo $classname->MaMH ?> </strong> - (Nhóm <?php echo $classname->NhomMH ?>) - Số tín chỉ: <?php echo $classname->STC ?></span>
                    <br><br>

                    <div class="table">
                        <table>
                            <thead>

                                <tr>
                                    <td>STT</td>
                                    <td>Mã SV</td>
                                    <td>Họ tên</td>
                                    <td>Tên lớp</td>
                                    <td>1</td>
                                    <td>2</td>
                                    <td>3</td>
                                    <td>4</td>
                                    <td>5</td>
                                    <td>6</td>
                                    <td>7</td>
                                    <td>8</td>
                                    <td>9</td>
                                    <td>10</td>
                                    <td>11</td>
                                    <td>12</td>
                                    <td>13</td>
                                    <td>14</td>
                                    <td>15</td>
                                    <td>16</td>
                                    <td>ĐQT</td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                <?php
                                    $stt =0;
                                ?>


                                @foreach($getinfoclass as $allstudentlist)

                                    <td>
                                        <?php
                                            $stt+=1;
                                            echo $stt;
                                        ?>
                                    </td>

                                    <td>{{$studentname->MSSV}}</td>
                                    <td>
                                        <?php

                                            echo $studentname->HoTenSV;
                                        ?>
                                    </td>
                                    <td><?php echo $studentname->MaLop ?></td>


                                    @if($allstudentlist->MaBuoi == 1)
                                        <td>x</td>
                                    @else
                                        <td></td>
                                    @endif
                                    @if($allstudentlist->MaBuoi == 2)
                                    <td>x</td>
                                    @else
                                        <td></td>
                                    @endif
                                    @if($allstudentlist->MaBuoi == 3)
                                    <td>x</td>
                                    @else
                                        <td></td>
                                    @endif
                                    @if($allstudentlist->MaBuoi == 4)
                                    <td>x</td>
                                    @else
                                        <td></td>
                                    @endif
                                    @if($allstudentlist->MaBuoi == 5)
                                    <td>x</td>
                                    @else
                                        <td></td>
                                    @endif
                                    @if($allstudentlist->MaBuoi == 6)
                                    <td>x</td>
                                    @else
                                        <td></td>
                                    @endif
                                    @if($allstudentlist->MaBuoi == 7)
                                    <td>x</td>
                                    @else
                                        <td></td>
                                    @endif
                                    @if($allstudentlist->MaBuoi == 8)
                                    <td>x</td>
                                    @else
                                        <td></td>
                                    @endif
                                    @if($allstudentlist->MaBuoi == 9)
                                    <td>x</td>
                                    @else
                                        <td></td>
                                    @endif
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <?php
                                        $checkall = DB::table('danh_sach_sinh_vien')->where('MSSV',$studentname->MSSV)->whereNotNull('MaBuoi')->distinct()->count('MaBuoi');
                                    ?>
                                    @if($checkall < 9)
                                    <td></td>
                                    <td></td>
                                    @else
                                        @if($allstudentlist->Diem14 != null)
                                            <td>$allstudentlist->Diem14</td>
                                        @else
                                            <td></td>
                                        @endif
                                        <td></td>
                                        @if($allstudentlist->Diem16 != null)
                                            <td>$allstudentlist->Diem16</td>
                                        @else
                                            <td></td>
                                        @endif

                                        <?php
                                            $diemqt = DB::table('ket_qua')->where('MaKQSV',$allstudentlist->MaKQSV)->first();
                                        ?>
                                        @if($diemqt->DiemQT != null)
                                            <td><?php echo $diemqt->DiemQT ?></td>
                                        @else
                                            <td></td>
                                        @endif

                                    @endif
                                </tr>
                            </tbody>
                            @endforeach

                        </table>
                    </div>
                </div>
            </div>
            {{ $getinfoclass->appends(request()->all())->links('pagination::bootstrap-4') }}
            {{-- <div class="text-center">
                <div class="pagination">
                    <ul class="pagination-list">
                        <li class="hidden-phone current"><a title="1" href="" class="pagenav">1</a></li>
                        <li class="hidden-phone next"><a title="2" href="" class="pagenav number">2</a></li>
                        <li class="hidden-phone next-page"><a title="Trang sau" href="" class="pagenav"><i class="fa fa-chevron-right"></i></a></li>
                    </ul>
                    <input type="hidden" name="limitstart" value="0">
                </div>
            </div> --}}
        </div>
    </div>
</body>
</html>
