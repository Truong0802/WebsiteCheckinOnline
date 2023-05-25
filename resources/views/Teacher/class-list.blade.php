
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo asset('/css/index.css')?>">
    <link rel="stylesheet" href="<?php echo asset('/css/class-list.css')?>">
    <link rel="stylesheet" href="<?php echo asset('/fonts/fontawesome-free-6.2.1-web/fontawesome-free-6.2.1-web/css/all.css')?>">
    <link rel="stylesheet" href="<?php echo asset('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css')?>">
    <link href="<?php echo asset('https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css')?>" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <title>Danh sách lớp học</title>
</head>
<body>
    <aside id="left-panel">
        <div class="login-info">
            <span class="ng-star-inserted">
                <a>
                    <img alt="" class="online" src="/assets/img/avatar.png">
                    <span>
                        <?php
                        $name =session()->get('name');
                        if($name)
                        {
                            echo $name;
                        }
                    ?>
                    </span>
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
                                <i class="fa-fw fa fa-graduation-cap"></i> Danh sách lớp học
                            </h1>
                        </div>
                    </div>
                </div>
            </div>
            @if(session()->get('ChucVu') == 'QL')
            <div class="container">
                <form action='/tim-kiem' method='get'>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="class-name">Tên lớp:</label>
                                <input type="text" class="form-control" id="class-name" name="classname">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="date-picker">Ngày:</label>
                                <input type="date" class="form-control" id="date-picker">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="school-year">Năm học:</label>
                                <select name="courselist" class="form-control" id="school-year">
                                    <?php
                                        $allcourse = DB::table('khoa_hoc')->distinct()->get();
                                    ?>
                                    <option value="">--Chọn năm học--</option>
                                    @foreach($allcourse as $courselist)
                                    <option value="<?php echo $courselist->KhoaHoc ?>">{{$courselist->NamHocDuKien}}</option>
                                    {{-- <option value="2022-2023">2022-2023</option> --}}
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="course-name">Khóa học:</label>
                                <input type="text" class="form-control" id="course-name" name="coursename">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="subject-name">Môn học:</label>
                                <input type="text" class="form-control" id="subject-name" name="subjectname">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="lecturer-name">Tên giảng viên:</label>
                                <input type="text" class="form-control" id="lecturer-name" name="lecturename">
                            </div>
                        </div>

                    </div>
                    <br>
                    <button type="submit"  class="btn btn-primary" onclick="filterData()">Tìm kiếm</button>
                    <a type="button" href="/xoa-tim-kiem" class="btn btn-primary" onclick="removeFilterData()">Xóa tất cả bộ lọc</a>
                    @csrf
                </form>
            </div>
            @endif

            <div class="col-md-12 detail">
                <div class="class-list">
                    <table>
                        <thead>
                            <tr>
                                <td>STT</td>
                                <td>Mã môn</td>
                                <td>Môn học</td>
                                <td>Lớp</td>
                                <td>Nhóm môn</td>
                                <td>Tiết</td>
                                <td>Số tín chỉ</td>
                                <td>Tên giảng viên</td>
                                <td></td>
                            </tr>
                        </thead>
                        <?php
                            $stt=0;
                        ?>
                        @foreach($getallsubject as $key)

                        <tbody>
                            <tr>
                                <td>
                                <?php
                                    $stt+=1;
                                    echo $stt;
                                ?>
                                </td>
                                <td>
                                    <?php
                                        $subjectid = DB::table('mon_hoc')->where('MaTTMH',$key->MaTTMH)->first();

                                        echo $subjectid->MaMH;
                                    ?>
                                </td>
                                <td><?php
                                    echo $subjectid->TenMH
                                ?></td>
                                <td> {{$key->MaLop}}</td>
                                <td>Nhóm <?php echo $subjectid->NhomMH ?></td>
                                <td>{{$key->MaTietHoc}}</td>
                                <td><?php
                                    echo $subjectid->STC;
                                ?></td>
                                <td><?php
                                    $teachername= DB::table('giang_vien')->where('MSGV',$key->MSGV)->first();
                                    echo $teachername->HoTenGV;
                                ?></td>
                                <td><a href="/danh-sach-sinh-vien?lop={{$key->MaTTMH}}"><i class="fa-regular fa-eye"></a></i></td>
                            </tr>
                        </tbody>
                        @endforeach

                    </table>
                </div>
            </div>

            {{-- Phân trang dùng laravel --}}
            {{ $getallsubject->appends(request()->all())->links('pagination::bootstrap-4') }}

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
