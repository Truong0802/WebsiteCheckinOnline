@extends('layouts.master-admin')

@section('content')
    <div id="ribbon">
        <ol class="breadcrumb">
            <li class="ng-star-inserted">
                <a>Quản lý sinh viên</a>
            </li>
        </ol>
    </div>
    @if (session('error-AddDSSV'))
        {{-- {{dd(session('error'))}} --}}
        <div class="alert alert-danger text-center">{{ session('error-AddDSSV') }}</div>
    @endif
    @if (session('success-AddDSSV'))
        <div class="alert alert-success text-center">{{ session('success-AddDSSV') }}</div>
    @endif
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
                            @error('mssv')
                                <div class="alert alert-danger">{{ $errors->first('mssv') }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="student-name">Tên sinh viên:</label>
                            <input type="text" class="form-control" id="student-name" name="studentname">
                            @error('studentname')
                                <div class="alert alert-danger">{{ $errors->first('studentname') }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="student-name">Lớp:</label>
                            <input type="text" class="form-control" id="class-Mame" name="classname">
                            @error('classname')
                                <div class="alert alert-danger">{{ $errors->first('classname') }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <input type='hidden' name='classid' value='<?php echo session()->get('classAddId'); ?>'>
                <br>
                <div class="btn-container">
                    <button type="submit" class="btn btn-primary" onclick="filterData()">Thêm sinh viên</button>
                    <a type="button" href="/confirmToAddDSSV" class="btn btn-success" onclick="removeFilterData()">Xác nhận
                        thêm</a>
                </div>
                @csrf
            </form>

            <style> 
                .detail {
                    grid-template-columns: 15fr
                }
            </style>
            <div class="col-md-12 detail">
                <div class="class-list">
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
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <?php
                            $stt = 0;
                            ?>
                            @if (session()->get('DanhSachSinhVienTam'))
                                @foreach (session()->get('DanhSachSinhVienTam') as $key)
                                    @if (session()->has('textByScan'))
                                        <?php
                                        $Mssv = Str::between($key, 'MSSV', 'MaTTMH');

                                        $findStudentName = Str::between($key, 'HoTenSV', 'NgayThangNamSinh');

                                        $CutClass = Str::before($key, 'HocKy');
                                        $findSubjectName = DB::table('mon_hoc')
                                            ->where('MaTTMH', $CutClass)
                                            ->first();
                                        $CutHK = Str::between($key, 'HocKy', 'NamHoc');
                                        $CutNamHoc = Str::between($key, 'NamHoc', 'MSGV');
                                        $CutMSGV = Str::between($key, 'MSGV', 'MSSV');
                                        $findNameofTeacher = DB::table('giang_vien')
                                            ->where('MSGV', $CutMSGV)
                                            ->first();
                                        ?>
                                        <tr>
                                            <td>{{ ++$stt }}</td>
                                            <td>{{ $Mssv }}</td>
                                            <td>{{ $findStudentName }}</td>
                                            <td>{{ $findNameofTeacher->HoTenGV }}</td>
                                            <td>{{ $CutHK }} - {{ $CutNamHoc }}</td>
                                            <td>{{ $findSubjectName->TenMH }}</td>
                                            <td><a href=""><i class="fa-regular fa-eye"></a></i></td>
                                            <td><a href="/DeleteSV?id={{ $key }}">Xóa sinh viên</a></td>
                                        </tr>
                                    @else
                                        <?php
                                        $Mssv = Str::between($key, 'MSSV', 'MaTTMH');

                                        $findStudentName = DB::table('sinh_vien')
                                            ->where('MSSV', $Mssv)
                                            ->first();

                                        if ($findStudentName == null) {
                                            $array = session('DanhSachSinhVienTam');
                                            $position = array_search($key, $array);
                                            unset($array[$position]);
                                            session(['DanhSachSinhVienTam' => $array]);
                                        }

                                        $CutClass = Str::before($key, 'HocKy');
                                        $findSubjectName = DB::table('mon_hoc')
                                            ->where('MaTTMH', $CutClass)
                                            ->first();
                                        $CutHK = Str::between($key, 'HocKy', 'NamHoc');
                                        $CutNamHoc = Str::between($key, 'NamHoc', 'MSGV');
                                        $CutMSGV = Str::between($key, 'MSGV', 'MSSV');
                                        $findNameofTeacher = DB::table('giang_vien')
                                            ->where('MSGV', $CutMSGV)
                                            ->first();
                                        ?>
                                        <tr>
                                            <td>{{ ++$stt }}</td>
                                            <td>{{ $Mssv }}</td>
                                            <td>{{ $findStudentName->HoTenSV }}</td>
                                            <td>{{ $findNameofTeacher->HoTenGV }}</td>
                                            <td>{{ $CutHK }} - {{ $CutNamHoc }}</td>
                                            <td>{{ $findSubjectName->TenMH }}</td>
                                            <td><a href=""><i class="fa-regular fa-eye"></a></i></td>
                                            <td><a href="/DeleteSV?id={{ $key }}">Xóa sinh viên</a></td>
                                        </tr>
                                    @endif
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@stop
