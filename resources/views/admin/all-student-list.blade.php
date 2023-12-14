<?php
    use Carbon\Carbon;
?>
@extends('layouts.master-admin')

@section('content')

    <div id="ribbon">
        <ol class="breadcrumb">
            <li class="ng-star-inserted">
                <a>Danh sách tất cả sinh viên</a>
            </li>
        </ol>
    </div>
    @if (session('errorClassList1'))
        <div class="alert alert-danger text-center">{{ session('errorClassList1') }}</div>
    @endif
    <div class="mt-4" id="content">
        <div class="  mx-4">
            <div class="row mb-3">
                <div class="col-md-6 pr-0">
                    <div>
                        <h1 class="page-title txt-color-blueDark">
                            <i class="fa-fw fa fa-graduation-cap"></i> Danh sách tất cả sinh viên
                        </h1>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <form action='/tim-kiem-tat-ca-sinh-vien' method='get'>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="student-name">Họ tên:</label>
                            <input type="text" class="form-control" id="student-name" name="studentname">
                            @error('studentname')
                                <div class="alert alert-danger">{{ $errors->first('studentname') }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="student-id">MSSV:</label>
                            <input type="text" class="form-control" id="student-id" name="mssv">
                            @error('mssv')
                                <div class="alert alert-danger">{{ $errors->first('mssv') }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <br>
                <button type="submit" class="btn btn-primary" onclick="filterData()">Tìm kiếm</button>
                <a type="button" href="/xoa-tim-kiem-tat-ca-sinh-vien" class="btn btn-danger" onclick="removeFilterData()">Xóa tất cả
                    bộ lọc</a>
                @csrf
            </form>
            <div class="col-md-12 detail teacher-detail">
                <style>
                    .detail {
                        grid-template-columns: 15fr
                    }
                </style>
                <div class="class-list">
                    <div class="table">
                        <table>
                            <thead>
                                <tr>
                                    <td>STT</td>
                                    <td>MSSV</td>
                                    <td>Họ tên</td>
                                    <td>Lớp</td>
                                    <td>Trạng thái</td>
                                    <td></td>

                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $stt=1;

                                ?>
                                @foreach($listStudent as $key)
                                    <tr>
                                        <td>{{$stt}}</td>
                                        <td>{{$key->MSSV}}</td>
                                        <td>{{$key->HoTenSV}}</td>
                                        <td>{{$key->MaLop}}</td>
                                       @if( $key->LastActive != null)

                                            @if(Carbon::now()->greaterThan(Carbon::parse($key->LastActive)->addMonths(6)) == true)
                                                <td style="color: red">Đã vô hiệu hóa</td>
                                            @else
                                                <td style="color: green">Active</td>
                                            @endif
                                        @else
                                            <td style="color: green">Active</td>
                                        @endif
                                        <td><a href="/quan-ly-sinh-vien?mssv={{$key->MSSV}}">Đặt lại mật khẩu</a></td>
                                        <?php
                                            $stt++;
                                        ?>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                {{ $listStudent->appends(request()->all())->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
    </div>

@stop
