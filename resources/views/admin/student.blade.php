@extends('layouts.master-admin')

@section('content')
@if (session('error-AddDSSV'))
            {{-- {{dd(session('error'))}} --}}
    <div class="alert alert-danger text-center">{{ session('error-AddDSSV') }}</div>
@endif
@if(session('success-AddDSSV'))
    <div class="alert alert-success text-center">{{ session('success-AddDSSV') }}</div>
@endif
        <div id="ribbon">
            <span class="ribbon-button-alignment">
                <span class="btn btn-ribbon" id="refresh" placement="bottom">
                    <i class="fa fa-refresh"></i>
                </span>
            </span>
            <ol class="breadcrumb">
                <li class="ng-star-inserted">
                    <a>Quản lý sinh viên</a>
                </li>
            </ol>
        </div>
        <div class="mt-4" id="content">
            <div class="  mx-4">
                <div class="row mb-3">
                    <div class="col-md-6 pr-0">
                        <div>
                            <h1 class="page-title txt-color-blueDark">
                                <i class="fa fa-lg fa-fw fa-user"></i> Thêm Danh Sách Sinh Viên
                            </h1>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container">
                <form action='/them-danh-sach-sinh-vien' method='post'>
                    <div class="row">
                        <div class="col-md-4">
                                <div class="form-group">
                                <label for="student-id">MSSV:</label>
                                <input type="text" class="form-control" id="student-id" name="mssv">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="student-name">Tên sinh viên:</label>
                                <input type="text" class="form-control" id="student-name" name="studentname">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="student-name">Lớp:</label>
                                <input type="text" class="form-control" id="class-name" name="classname">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="hoc-ky">Học kì:</label>
                                <select class="form-control" id="Hocki" name="Hocki">
                                    <option value="">---Chọn Thông Tin---</option>
                                    <option value="1A">1A</option>
                                    <option value="1B">1B</option>
                                    <option value="2A">2A</option>
                                    <option value="2B">2B</option>
                                    <option value="3">3</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="year">Năm Học:</label>
                            <input type="text" class="form-control" id="year" name="year">
                        </div>
                    </div>
                    <input type='hidden' name='classid' value='<?php echo session()->get('classAddId') ?>'>
                    <br>
                    <div class="btn-container">
                        <button type="submit" class="btn btn-primary" onclick="filterData()">Thêm sinh viên</button>
                        <a type="button" href="/confirmToAddDSSV" class="btn btn-primary" onclick="removeFilterData()">Xác nhận thêm</a>
                    </div>

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
                <div class="col-md-12 detail">
                    <table>
                        <thead>
                            <tr>
                                <td>STT</td>
                                <td>MSSV</td>
                                <td>Họ Tên</td>
                                <td>Giảng viên</td>
                                <td>Học kỳ - Năm học</td>
                                <td>Môn Học</td>
                                <td></td>
                                <td></td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $stt=0;
                            ?>
                            @if(session()->get('DanhSachSinhVienTam'))
                                @foreach(session()->get('DanhSachSinhVienTam') as $key)
                                <?php
                                    $Mssv = Str::between($key,'MSSV','MaTTMH');
                                    $findStudentName = DB::table('sinh_vien')->where('MSSV',$Mssv)->first();
                                    $CutClass = Str::before($key,'HocKy');
                                    $findSubjectName = DB::table('mon_hoc')->where('MaTTMH',$CutClass)->first();
                                    $CutHK = Str::between($key,'HocKy','NamHoc');
                                    $CutNamHoc = Str::between($key,'NamHoc','MSGV');
                                    $CutMSGV = Str::between($key,'MSGV','MSSV');
                                    $findNameofTeacher = DB::table('giang_vien')->where('MSGV',$CutMSGV)->first();
                                ?>
                                    <tr>
                                        <td>{{++$stt;}}</td>
                                        <td>{{$Mssv}}</td>
                                        <td>{{$findStudentName->HoTenSV}}</td>
                                        <td>{{$findNameofTeacher->HoTenGV}}</td>
                                        <td>{{$CutHK}} - {{$CutNamHoc}}</td>
                                        <td>{{$findSubjectName->TenMH}}</td>
                                        <td><a href=""><i class="fa-regular fa-eye"></a></i></td>
                                        <td><a href="/DeleteSV?id={{$key}}">Xóa sinh viên</a></td>
                                    </tr>
                                @endforeach

                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

@stop
