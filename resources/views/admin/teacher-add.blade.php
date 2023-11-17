@extends('layouts.master-admin')

@section('content')
@if (session('error-Add-T'))
            {{-- {{dd(session('error'))}} --}}
    <div class="alert alert-danger text-center">{{ session('error-Add-T') }}</div>
@endif
@if(session('success-Add-T'))
    <div class="alert alert-success text-center">{{ session('success-Add-T') }}</div>
@endif
        <div id="ribbon">
            <ol class="breadcrumb">
                <li class="ng-star-inserted">
                    <a>Thêm giảng viên</a>
                </li>
            </ol>
        </div>
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
                                    @foreach($AllKhoa as $khoa)
                                        <option value="{{$khoa->MaKhoa}}">{{$khoa->TenKhoa}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="hoc-ky">Chức vụ:</label>
                                <?php
                                    if(session('ChucVu') != 'AM')
                                    {
                                        $AllRole = DB::table('chuc_vu')
                                        ->where('MaChucVu','!=',session('ChucVu'))
                                        ->where('MaChucVu','!=','AM')->get();
                                    }
                                    elseif(session('ChucVu') == 'AM')
                                    {
                                        $AllRole = DB::table('chuc_vu')->get();
                                    }
                                ?>
                                <select class="form-control" id="Role" name="role">
                                    <option value="">---Chọn Thông Tin---</option>
                                    @foreach($AllRole as $role)
                                        <option value="{{$role->MaChucVu}}">{{$role->ChucVu}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="btn-container">
                        <button type="submit" class="btn btn-primary" onclick="filterData()">Thêm Giảng Viên</button>
                        <input type="file" id="fileInput" class="custom-file-input">
                        <a type="button" href="/confirmToAddGV" class="btn btn-primary" onclick="removeFilterData()">Xác nhận thêm</a>
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
                    {{-- <span><strong>HỌC PHẦN:</strong> Lập trình ứng dụng với Java <strong> (CMP3025) </strong> - Nhóm 2 - Số tín chỉ: 3</span> --}}
                    <br><br>
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
                            <?php
                                $stt =1;
                            ?>
                        @if(session()->has('DanhSachGVTam'))
                            @foreach (session()->get('DanhSachGVTam') as $temp)
                                <?php
                                    $MSGVCut = Str::between($temp,'MSGV','HoTen');
                                    $HoTen = Str::between($temp,'HoTen','Pass');
                                    $Password = Str::between($temp,'Pass','Role');
                                    $CutRoleid = Str::between($temp,'Role','KHOA');
                                    $FindCV = DB::table('chuc_vu')->where('MaChucVu',$CutRoleid)->first();
                                    $CutKhoa = Str::after($temp,'KHOA');
                                    $FindNameKhoa = DB::table('khoa')->where('MaKhoa',$CutKhoa)->first();
                                ?>
                                <tbody>
                                    <tr>
                                        <td>{{$stt}}</td>
                                        <td>{{$MSGVCut}}</td>
                                        <td>{{$HoTen}}</td>
                                        <td>{{$FindCV->ChucVu}}</td>
                                        <td>{{$FindNameKhoa->TenKhoa}}</td>
                                        <td><a href="/Delete-gv-id?id={{$temp}}&num={{$stt}}">Xóa giảng viên</a></td>
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
