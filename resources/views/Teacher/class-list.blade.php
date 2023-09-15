@extends('layouts.master-teacher')


@section('content')
        @if(session('success1'))
            <div class="alert alert-success text-center">{{ session('success1') }}</div>
        @endif
        <div id="ribbon">
            <span class="ribbon-button-alignment">
                <span class="btn btn-ribbon" id="refresh" placement="bottom">
                    <i class="fa fa-refresh"></i>
                </span>
            </span>
            <ol class="breadcrumb">
                <li class="ng-star-inserted">
                    <a>Danh sách Lớp Học</a>
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
                        @if(session()->get('ChucVu') == 'QL' || session()->get('ChucVu') == 'AM')
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="lecturer-name">Tên giảng viên:</label>
                                <input type="text" class="form-control" id="lecturer-name" name="lecturename">
                            </div>
                        </div>
                        @endif

                    </div>
                    <br>
                    <button type="submit"  class="btn btn-primary" onclick="filterData()">Tìm kiếm</button>
                    <a type="button" href="/xoa-tim-kiem" class="btn btn-primary" onclick="removeFilterData()">Xóa tất cả bộ lọc</a>
                    @csrf
                </form>
            </div>


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
                                @if(session()->get('ChucVu') == 'QL' || session()->get('ChucVu') == 'AM')
                                <td></td>
                                @endif
                            </tr>
                        </thead>
                        <?php
                            $stt=0;
                        ?>
                    @if($getallsubject != null)
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
                                @if(session()->get('ChucVu') == 'QL' || session()->get('ChucVu') == 'AM')
                                    <td><a href="/Them-danh-sach-sv?lop={{$key->MaTTMH}}">Thêm danh sách</a></i></td>
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
                                @if(session()->get('ChucVu') == 'QL' || session()->get('ChucVu') == 'AM')
                                    <td></td>
                                @endif
                            </tr>
                        </tbody>
                    @endif
                    </table>
                </div>
            </div>
            @if($getallsubject != null)
                {{-- Phân trang dùng laravel --}}
                {{ $getallsubject->appends(request()->all())->links('pagination::bootstrap-4')}}
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
                    if(session()->exists('teacherid') )
                    {
                        $checkConfirmOrNot = DB::table('giang_vien')->where('MSGV',session()->get('teacherid') )->first();
                    }
                ?>
                    @if($checkConfirmOrNot)

                        @if($checkConfirmOrNot->Confirmed != 1)
                            <!--Xuất popup để chuyển qua trang xác thực khi bấm Ok-->
                            <div class="popup-container" id="popup">
                                <div class="popup-content">
                                    <h2>Thông báo</h2>
                                    <p>Bạn cần thay đổi thông tin mật khẩu</p>
                                    <a type="button" href="/xac-nhan-nguoi-dung">Đi thay đổi</a>
                                    <button onclick = "closePopup()">Đóng</button>
                                </div>

                                <script>
                                    const popup = document.getElementById("popup");
                                    function showPopup()
                                    {
                                        popup.style.display = "flex";
                                    }
                                    function closePopup()
                                    {
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
