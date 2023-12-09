@extends('layouts.master-admin')

@section('content')

    <div id="ribbon">
        <ol class="breadcrumb">
            <li class="ng-star-inserted">
                <a>Thêm lớp</a>
            </li>
        </ol>
    </div>
    @if (session('error-Add-C'))
        {{-- {{dd(session('error'))}} --}}
        <div class="alert alert-danger text-center">{{ session('error-Add-C') }}</div>
    @endif
    @if (session('success-Add-C'))
        <div class="alert alert-success text-center">{{ session('success-Add-C') }}</div>
    @endif
    <div class="mt-4" id="content">
        <div class="  mx-4">
            <div class="row mb-3">
                <div class="col-md-6 pr-0">
                    <div>
                        <h1 class="page-title txt-color-blueDark">
                            <i class="fa-fw fa fa-graduation-cap"></i> Thêm lớp
                        </h1>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <form action='/them-lop' method='post'>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="teacher-id">Lớp:</label>
                            <input type="text" class="form-control" id="Classid" name="classid">
                            @error('classid')
                                <div class="alert alert-danger">{{ $errors->first('classid') }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="student-name">Niên khóa:</label>
                            <input type="text" class="form-control" id="KhoaHoc" name="KhoaHoc">
                            @error('KhoaHoc')
                                <div class="alert alert-danger">{{ $errors->first('KhoaHoc') }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="student-name">Năm bắt đầu:</label>
                            <input type="text" class="form-control" id="startYears" name="startYears">
                            @error('startYears')
                                <div class="alert alert-danger">{{ $errors->first('startYears') }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="student-name">Năm kết thúc:</label>
                            <input type="text" class="form-control" id="endYears" name="endYears">
                            @error('endYears')
                                <div class="alert alert-danger">{{ $errors->first('endYears') }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <br>
                <button type="submit" class="btn btn-primary" onclick="filterData()">Thêm Lớp</button>
                {{-- <input type="file" id="fileInput" class="custom-file-input"> --}}
                <a type="button" href="/confirmToAddClass" class="btn btn-success" onclick="removeFilterData()">Xác nhận
                    thêm</a>
                @csrf
            </form>
            <div class="col-md-12 detail class-detail">
                <style>
                    .detail {
                        grid-template-columns: 15fr
                    }
                </style>
                <div class="class-list">
                    {{-- <span><strong>HỌC PHẦN:</strong> Lập trình ứng dụng với Java <strong> (CMP3025) </strong> - Nhóm 2 - Số tín chỉ: 3</span> --}}
                    <div class="table">
                        <table>
                            <thead>
                                <tr>
                                    <td>STT</td>
                                    <td>Lớp</td>
                                    <td>Khóa học</td>
                                    <td>Năm học bắt đầu</td>
                                    <td>Năm học kết thúc</td>
                                    <td></td>
                                </tr>
                            </thead>
                            <?php
                            $stt = 1;
                            ?>
                            @if (session()->has('DanhSachLopNKTam'))
                                @foreach (session()->get('DanhSachLopNKTam') as $temp)
                                    <?php
                                    $CutLop = Str::between($temp, 'Lop', 'KHOAHOC');
                                    $CutKhoaHoc = Str::between($temp, 'KHOAHOC', 'year');
                                    $CutNamBatDau = Str::between($temp, 'year', '-');
                                    $CutNamKetThuc = Str::after($temp, '-');
                                    ?>
                                    <tbody>
                                        <tr>
                                            <td>{{ $stt }}</td>
                                            <td>{{ $CutLop }}</td>
                                            <td>{{ $CutKhoaHoc }}</td>
                                            <td>{{ $CutNamBatDau }}</td>
                                            <td>{{ $CutNamKetThuc }}</td>
                                            <td><a href="/Delete-class-id?id={{ $temp }}">Xóa lớp</a></td>
                                        </tr>
                                    </tbody>
                                    <?php
                                    $stt++;
                                    ?>
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
