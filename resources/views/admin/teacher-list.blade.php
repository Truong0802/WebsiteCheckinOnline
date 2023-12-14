@extends('layouts.master-admin')

@section('content')

    <div id="ribbon">
        <ol class="breadcrumb">
            <li class="ng-star-inserted">
                <a>Thêm giảng viên</a>
            </li>
        </ol>
    </div>
    @if (session('error-Add-T'))
        {{-- {{dd(session('error'))}} --}}
        <div class="alert alert-danger text-center">{{ session('error-Add-T') }}</div>
    @endif
    @if (session('success-Add-T'))
        <div class="alert alert-success text-center">{{ session('success-Add-T') }}</div>
    @endif
    <div class="mt-4" id="content">
        <div class="  mx-4">
            <div class="row mb-3">
                <div class="col-md-6 pr-0">
                    <div>
                        <h1 class="page-title txt-color-blueDark">
                            <i class="fa-fw fa fa-graduation-cap"></i> Thêm giảng viên
                        </h1>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <form action='/them-giang-vien' method='post'>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="teacher-id">MSGV:</label>
                            <input type="text" class="form-control" id="teacher-id" name="msgv">
                            @error('msgv')
                                <div class="alert alert-danger">{{ $errors->first('msgv') }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="teacher-name">Họ tên:</label>
                            <input type="text" class="form-control" id="teacher-name" name="teachername">
                            @error('teachername')
                                <div class="alert alert-danger">{{ $errors->first('teachername') }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="student-name">Mật khẩu:</label>
                            <input type="text" class="form-control" id="password" name="password">
                            @error('password')
                                <div class="alert alert-danger">{{ $errors->first('password') }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="student-name">Khoa:</label>
                            <?php
                            $AllKhoa = DB::table('khoa')->get();
                            ?>
                            <select class="form-control" id="khoa" name="khoa">
                                <option value="">---Chọn Thông Tin---</option>
                                @foreach ($AllKhoa as $khoa)
                                    <option value="{{ $khoa->MaKhoa }}">{{ $khoa->TenKhoa }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="hoc-ky">Chức vụ:</label>
                            <?php
                            if (session('ChucVu') != 'AM') {
                                $AllRole = DB::table('chuc_vu')
                                    ->where('MaChucVu', '!=', session('ChucVu'))
                                    ->where('MaChucVu', '!=', 'AM')
                                    ->get();
                            } elseif (session('ChucVu') == 'AM') {
                                $AllRole = DB::table('chuc_vu')->get();
                            }
                            ?>
                            <select class="form-control" id="Role" name="role">
                                <option value="">---Chọn Thông Tin---</option>
                                @foreach ($AllRole as $role)
                                    <option value="{{ $role->MaChucVu }}">{{ $role->ChucVu }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <br>
                <div class="col-md-4">
                    <div class="form-group">
                        <input type="checkbox" id="" name="reset" value="1">
                        <label for="reset"><b style="font-size: 17px;">Reset password</b></label>
                    </div>
                </div>
                <br>
                <div class="btn-container">
                    <button type="submit" class="btn btn-primary" onclick="filterData()">Thêm Giảng Viên</button>
                    {{-- <input type="file" id="fileInput" class="custom-file-input"> --}}
                    <a type="button" href="/confirmToAddGV" class="btn btn-success" onclick="removeFilterData()">Xác nhận
                        thêm</a>
                </div>
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
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

@stop
