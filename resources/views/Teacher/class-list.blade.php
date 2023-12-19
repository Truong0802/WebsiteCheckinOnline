@extends('layouts.master-teacher')

@section('content')
    <div id="ribbon">
        <ol class="breadcrumb">
            <li class="ng-star-inserted">
                <a>Danh sách Lớp Học</a>
            </li>
        </ol>
    </div>
    @if (session('errorClass1'))
        <div class="alert alert-danger text-center">{{ session('errorClass1') }}</div>
    @endif
    @if (session('SuccessClass1'))
        <div class="alert alert-success text-center">{{ session('SuccessClass1') }}</div>
    @endif
    <div class="mt-4" id="content">
        <div class="  mx-4">
            <div class="row mb-3">
                <div class="col-md-6 pr-0">
                    <div>
                        <h1 class="page-title txt-color-blueDark">
                            <i class="fa fa-lg fa-fw fa-book"></i> Danh sách lớp học
                        </h1>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            @if (session()->exists('teacherid'))
                <form action='/tim-kiem' method='get'>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="class-name">Tên lớp:</label>
                                <input type="text" class="form-control" id="class-Name" name="classname">
                            </div>
                        </div>
                        {{-- <div class="col-md-4">
                            <div class="form-group">
                                <label for="date-picker">Ngày:</label>
                                <input type="date" class="form-control datetime-local" id="date-picker">
                            </div>
                        </div> --}}
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="school-year">Lớp theo niên khóa:</label>
                                <select name="courselist" class="form-control" id="school-year">
                                    <?php
                                    $allcourse = DB::table('khoa_hoc')
                                        ->distinct()
                                        ->get();
                                    ?>
                                    <option value="">--Chọn niên khóa--</option>
                                    @foreach ($allcourse as $courselist)
                                        <option value="<?php echo $courselist->KhoaHoc; ?>">{{ $courselist->KhoaHoc }}</option>
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
                                <label for="course-name">Học kỳ:</label>
                                {{-- <input type="text" class="form-control" id="course-name" name="coursename"> --}}
                                <select name="coursename" class="form-control" id="course-name">
                                    <?php
                                    $allcoursename = DB::table('hoc_ky')
                                        ->where('HocKy','1A')
                                        ->distinct()
                                        ->limit(6)
                                        ->latest('NamHoc')
                                        ->get();
                                    ?>
                                    <option value="">--Chọn năm học --</option>
                                    @foreach ($allcoursename as $coursenamelist)
                                        <option value="{{$coursenamelist->NamHoc}}">{{ $coursenamelist->NamHoc}}</option>
                                        {{-- <option value="2022-2023">2022-2023</option> --}}
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="subject-name">Môn học:</label>
                                <input type="text" class="form-control" id="subject-name" name="subjectname">
                            </div>
                        </div>
                        @if (session()->get('ChucVu') == 'QL' || session()->get('ChucVu') == 'AM')
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="lecturer-name">Tên giảng viên:</label>
                                    <input type="text" class="form-control" id="lecturer-name" name="lecturename">
                                </div>
                            </div>
                        @endif

                    </div>
                    <br>
                    <button type="submit" class="btn btn-primary" onclick="filterData()">Tìm kiếm</button>
                    <a type="button" href="/xoa-tim-kiem" class="btn btn-danger" onclick="removeFilterData()">Xóa tất cả bộ
                        lọc</a>
                    @csrf
                </form>
            @endif
        </div>

        <div class="col-md-12 detail class-list-detail">
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
                            @if (session()->get('ChucVu') == 'QL' || session()->get('ChucVu') == 'AM')
                                <td></td>
                            @endif
                        </tr>
                    </thead>
                    <?php
                    $stt = 0;
                    ?>
                    @if ($getallsubject != null)
                        @foreach ($getallsubject as $key)
                            <tbody>
                                <tr>
                                    <td>
                                        <?php
                                        $stt += 1;
                                        echo $stt;
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        $subjectid = DB::table('mon_hoc')
                                            ->where('MaTTMH', $key->MaTTMH)
                                            ->first();

                                        echo $subjectid->MaMH;
                                        ?>
                                    </td>
                                    <td><?php
                                    echo $subjectid->TenMH;
                                    ?></td>
                                    <td> {{ $key->MaLop }}</td>
                                    <td>Nhóm <?php echo $subjectid->NhomMH; ?></td>
                                    <td>{{ $key->MaTietHoc }}</td>
                                    <td><?php
                                    echo $subjectid->STC;
                                    ?></td>
                                    <td><?php
                                    $teachername = DB::table('giang_vien')
                                        ->where('MSGV', $key->MSGV)
                                        ->first();
                                    echo $teachername->HoTenGV;
                                    ?></td>

                                    <td><a href="/danh-sach-sinh-vien?lop={{ $key->MaTTMH }}&HK={{ $key->MaHK }}"><i
                                                class="fa-regular fa-eye"></a></i></td>
                                    @if (session()->get('ChucVu') == 'QL' || session()->get('ChucVu') == 'AM')
                                        <td><a href="/Them-danh-sach-sv?lop={{ $key->MaTTMH }}&HK={{ $key->MaHK }}">Thêm
                                                danh sách</a></i></td>
                                    @endif
                                </tr>
                            </tbody>
                        @endforeach
                    @else
                        <tbody>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                @if (session()->get('ChucVu') == 'QL' || session()->get('ChucVu') == 'AM')
                                    <td></td>
                                @endif
                            </tr>
                        </tbody>
                    @endif
                </table>
            </div>
        </div>
        @if ($getallsubject != null)
            {{-- Phân trang dùng laravel --}}
            {{ $getallsubject->appends(request()->url())->links('pagination::bootstrap-4') }}
            <br><br><br><br><br><br>
        @endif
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
        <?php
        if (session()->exists('teacherid')) {
            $checkConfirmOrNot = DB::table('giang_vien')
                ->where('MSGV', session()->get('teacherid'))
                ->first();
        } elseif (session()->exists('studentid')) {
            $checkConfirmOrNot = DB::table('sinh_vien')
                ->where('MSSV', session()->get('studentid'))
                ->first();
        }
        ?>
        @if ($checkConfirmOrNot)
            @if ($checkConfirmOrNot->Confirmed != 1 && session()->get('ChucVu') != 'QL' && session()->get('ChucVu') != 'AM')
                <!--Xuất popup để chuyển qua trang xác thực khi bấm Ok-->
                <div class="popup-container" id="popup">
                    <div class="popup-content">
                        <h2>Thông báo</h2>
                        <p>Bạn cần thay đổi thông tin mật khẩu</p>
                        <a class="btn-change" type="button" href="/xac-nhan-nguoi-dung">Đi thay đổi</a>
                        <button class="btn-primary" onclick = "closePopup()">Đóng</button>
                    </div>
                    <script>
                        const popup = document.getElementById("popup");

                        function showPopup() {
                            popup.style.display = "flex";
                        }

                        function closePopup() {
                            popup.style.display = "none";
                        }
                        window.onload = showPopup;
                    </script>
                </div>
            @else
            @endif
        @endif
    </div>
@stop
