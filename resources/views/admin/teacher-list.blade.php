@extends('layouts.master-admin')

@section('content')

    <div id="ribbon">
        <ol class="breadcrumb">
            <li class="ng-star-inserted">
                <a>Danh sách giảng viên</a>
            </li>
        </ol>
    </div>

    <div class="mt-4" id="content">
        <div class="  mx-4">
            <div class="row mb-3">
                <div class="col-md-6 pr-0">
                    <div>
                        <h1 class="page-title txt-color-blueDark">
                            <i class="fa-fw fa fa-graduation-cap"></i> Danh sách giảng viên
                        </h1>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <form action='/tim-kiem-giang-vien' method='get'>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="student-name">Họ tên:</label>
                            <input type="text" class="form-control" id="teacher-name" name="teachername">
                            @error('teachername')
                                <div class="alert alert-danger">{{ $errors->first('teachername') }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="student-id">Mã giảng viên:</label>
                            <input type="text" class="form-control" id="student-id" name="msgv">
                            @error('msgv')
                                <div class="alert alert-danger">{{ $errors->first('msgv') }}</div>
                            @enderror
                        </div>
                    </div>

                </div>

                <br>
                <button type="submit" class="btn btn-primary" onclick="filterData()">Tìm kiếm</button>
                <a type="button" href="/xoa-tim-kiem-gv" class="btn btn-danger" onclick="removeFilterData()">Xóa tất cả
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
                                    <td>Mã GV</td>
                                    <td>Họ tên</td>
                                    <td>Chức vụ</td>
                                    <td>Khoa</td>
                                    <td></td>

                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $stt=1;

                                ?>
                                @foreach($listTeacher as $key)
                                    <tr>
                                        <td>{{$stt}}</td>
                                        <td>{{$key->MSGV}}</td>
                                        <td>{{$key->HoTenGV}}</td>
                                        <td>{{$key->ChucVu}}</td>
                                        <td>{{$key->TenKhoa}}</td>
                                        <td><a href="/quan-ly-gv?msgv={{$key->MSGV}}">Đặt lại mật khẩu</a></td>
                                        <?php
                                            $stt++;
                                        ?>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                {{ $listTeacher->appends(request()->all())->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
    </div>

@stop
