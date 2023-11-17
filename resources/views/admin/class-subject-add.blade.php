@extends('layouts.master-admin')

@section('content')
@if (session('error-AddClass'))
            {{-- {{dd(session('error'))}} --}}
    <div class="alert alert-danger text-center">{{ session('error-AddClass') }}</div>
@endif
@if(session('success-AddClass'))
    <div class="alert alert-success text-center">{{ session('success-AddClass') }}</div>
@endif
<div id="ribbon">
            <ol class="breadcrumb">
                <li class="ng-star-inserted">
                    <a>Quản lý lớp học</a>
                </li>
            </ol>
        </div>
        <div class="mt-4" id="content">
            <div class="  mx-4">
                <div class="row mb-3">
                    <div class="col-md-6 pr-0">
                        <div>
                            <h1 class="page-title txt-color-blueDark">
                                <i class="fa-fw fa fa-graduation-cap"></i> Quản lý lớp học
                            </h1>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container">
                <form action='/them-danh-sach' method='post'>
                    <div class="row">
                        <div class="col-md-4">
                                <div class="form-group">
                                <label for="student-id">Thời gian học:</label>
                                <input type="datetime-local" class="form-control" id="time-start" name="timestart">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="teacher">Giảng viên:</label>
                                <?php
                                    $listTeacher = DB::table('giang_vien')->where('MaChucVu', '<>', 'AM')->where('MaChucVu', '<>', 'QL')->get();
                                ?>
                                <select class="form-control" id="teacher" name="teacherid">
                                    <option value="">---Chọn Thông Tin---</option>
                                    @foreach($listTeacher as $AllTeacher)
                                        <option value="{{$AllTeacher->MSGV}}">{{$AllTeacher->HoTenGV}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="class-name">Môn học:</label>
                                <?php
                                    $listSubject = DB::table('mon_hoc')->get();
                                ?>
                                <select class="form-control" id="subject-name" name="subjectname">
                                    <option value="">---Chọn Thông Tin---</option>
                                    @foreach($listSubject as $AllSubject)
                                        <option value="{{$AllSubject->MaTTMH}}">{{$AllSubject->TenMH.'- Nhóm: '.$AllSubject->NhomMH}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="class-name">Lớp:</label>
                                <?php
                                    $listClass = DB::table('lop')->get();
                                ?>
                                <select class="form-control" id="classname" name="classname">
                                    <option value="">---Chọn Thông Tin---</option>
                                    @foreach($listClass as $AllClass)
                                        <option value="{{$AllClass->MaLop}}">{{$AllClass->TenLop}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <br>
                    <button type="submit" class="btn btn-primary" onclick="filterData()">Thêm Lớp</button>
                    <a type="button" href="/confirmToAddSubject" class="btn btn-primary" onclick="removeFilterData()">Xác nhận thêm</a>
                    @csrf
                </form>
                <div class="col-md-12 detail">
                <style>
                    .detail
                    {
                        grid-template-columns: 15fr
                    }
                </style>
                <div class="class-list">
                    {{-- <span><strong>HỌC PHẦN:</strong> Lập trình ứng dụng với Java <strong> (CMP3025) </strong> - Nhóm 2 - Số tín chỉ: 3</span> --}}
                    <br><br>
                    <div class="table">
                        <table>
                            <thead>
                                <tr>
                                    <td>STT</td>
                                    <td>Tiết bắt đầu</td>
                                    <td>Tên Môn Học</td>
                                    <td>Mã Môn Học</td>
                                    <td>Nhóm Môn Học</td>
                                    <td>Lớp</td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </thead>
                        <?php
                            $stt =0;
                        ?>
                        @if(session()->has('DanhSachLopTam'))
                            @foreach (session()->get('DanhSachLopTam') as $temp)
                                <?php
                                    $date = Str::before($temp,'MaMH');
                                    // $CutClass = substr($temp,-7);
                                    $CutClass = Str::between($temp,'Lop','GV');
                                    $MaTTMH = Str::between($temp,'MaMH','TenMH');
                                    $MaMH = substr($MaTTMH, 0 ,-2);
                                    $NhomMH = substr($MaTTMH,-2);
                                    $TenMH = Str::between($temp,'TenMH','Lop');
                                ?>
                                <tbody>
                                    <tr>
                                        <td>{{++$stt}}</td>
                                        <td>{{$date}}</td>
                                        <td>{{$MaMH}}</td>
                                        <td>{{$TenMH}}</td>
                                        <td>{{$NhomMH}}</td>
                                        <td>{{$CutClass}}</td>
                                        <td>Chỉnh sửa</td>
                                        <td><a href="/Delete-subject?id={{$temp}}">Xóa</a></td>
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
                            </tr>
                        </tbody>
                        @endif
                        </table>
                    </div>
                </div>
            </div>
            </div>
        </div>
</div>

@stop
