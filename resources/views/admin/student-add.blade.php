@extends('layouts.master-admin')

@section('content')

        <div id="ribbon">
            <ol class="breadcrumb">
                <li class="ng-star-inserted">
                    <a>Thêm sinh viên</a>
                </li>
            </ol>
        </div>
        @if (session('error-Add'))
            {{-- {{dd(session('error'))}} --}}
            <div class="alert alert-danger text-center">{{ session('error-Add') }}</div>
        @endif
        @if(session('success-Add'))
            <div class="alert alert-success text-center">{{ session('success-Add') }}</div>
        @endif
        <div class="mt-4" id="content">
            <div class="  mx-4">
                <div class="row mb-3">
                    <div class="col-md-6 pr-0">
                        <div>
                            <h1 class="page-title txt-color-blueDark">
                                <i class="fa-fw fa fa-graduation-cap"></i> Thêm sinh viên
                            </h1>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container">
                <form action='/them-sinh-vien' method='post'>
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
                                <label for="student-name">Họ tên:</label>
                                <input type="text" class="form-control" id="student-name" name="studentname">
                                @error('studentname')
                                    <div class="alert alert-danger">{{ $errors->first('studentname') }}</div>
                                @enderror
                            </div>
                        </div>

                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="class-name">Tên lớp:</label>
                                <input type="text" class="form-control" id="class-name" name="classname">
                                @error('classname')
                                    <div class="alert alert-danger">{{ $errors->first('classname') }}</div>
                                @enderror
                            </div>
                        </div>
                        {{-- <div class="col-md-4">
                            <div class="form-group">
                                <label for="student-name">Mật khẩu:</label>
                                <input type="text" class="form-control" id="password" name="password">
                                @error('password')
                                    <div class="alert alert-danger">{{ $errors->first('password') }}</div>
                                @enderror
                            </div>
                        </div> --}}
                    </div>

                    {{-- <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="class-name">Phường:</label>
                                <input type="text" class="form-control" id="phuong" name="phuong">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="class-name">Quận:</label>
                                <input type="text" class="form-control" id="quan" name="quan">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="class-name">Thành Phố:</label>
                                <input type="text" class="form-control" id="TP" name="TP">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="class-name">Địa chỉ chi tiết:</label>
                                <input type="text" class="form-control" id="DiaChi" name="DiaChi">
                            </div>
                        </div>
                    </div> --}}
                    <br>
                    <div class="btn-container">
                        <button type="submit" class="btn btn-primary" onclick="filterData()">Thêm sinh viên</button>
                        <input type="file" id="fileInput" class="custom-file-input">
                        <a type="button" href="/confirmToAdd" class="btn btn-primary" onclick="removeFilterData()">Xác nhận thêm</a>
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
                                    <td>Mã SV</td>
                                    <td>Họ tên</td>
                                    <td>Tên lớp</td>
                                    <td>Tác vụ</td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </thead>
                            <?php
                                $stt =1;
                            ?>
                        @if(session()->has('DanhSachTam'))
                            @foreach (session()->get('DanhSachTam') as $temp)
                                <?php
                                    $MSSVCut = substr($temp,0,10);
                                    $CutClass = substr($temp,-7);
                                    $HoTen = Str::between($temp,$MSSVCut,'MK');
                                    $Password = Str::between($temp,'MK',$CutClass);
                                ?>
                                <tbody>
                                    <tr>
                                        <td>{{$stt}}</td>
                                        <td>{{$MSSVCut}}</td>
                                        <td>{{$HoTen}}</td>
                                        <td>{{$CutClass}}</td>
                                        <td>Chỉnh sửa</td>
                                        <td><a href="/Delete-id?id={{$temp}}&num={{$stt}}">Xóa sinh viên</a></td>
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
