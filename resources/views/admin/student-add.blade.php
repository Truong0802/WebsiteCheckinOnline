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
    @if (session('success-Add'))
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
                            @if(isset($StudentToChange))
                                <input type="text" class="form-control" id="student-id" name="mssv" value="{{$StudentToChange->MSSV}}" readonly>
                            @else
                                <input type="text" class="form-control" id="student-id" name="mssv">
                            @endif
                            @error('mssv')
                                <div class="alert alert-danger">{{ $errors->first('mssv') }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="student-name">Họ tên:</label>
                            @if(isset($StudentToChange))
                                <input type="text" class="form-control" id="student-name" name="studentname" value="{{$StudentToChange->HoTenSV}}">
                            @else
                                <input type="text" class="form-control" id="student-name" name="studentname">
                            @endif
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
                            @if(isset($StudentToChange))
                                <input type="text" class="form-control" id="" name="classname" value="{{$StudentToChange->MaLop}}">
                            @else
                                <input type="text" class="form-control" id="" name="classname">
                            @endif
                            @error('classname')
                                <div class="alert alert-danger">{{ $errors->first('classname') }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <br>
                @if(isset($StudentToChange))
                    <div class="col-md-4">
                        <div class="form-group">
                            <input type="checkbox" id="" name="reset" value="1" checked>
                            <label for="reset"><b style="font-size: 17px;">Reset password</b></label>
                        </div>
                    </div>
                @endif
                <br>
                <div class="btn-container">
                    <button type="submit" class="btn btn-primary" onclick="filterData()">Thêm sinh viên</button>
                    {{-- <input type="file" id="fileInput" class="custom-file-input"> --}}
                    <a type="button" href="/confirmToAdd" class="btn btn-success" onclick="removeFilterData()">Xác nhận
                        thêm</a>
                </div>

                @csrf
            </form>
            <div class="col-md-12 detail student-list-detail">
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
                                    <td>Mã SV</td>
                                    <td>Họ tên</td>
                                    <td>Tên lớp</td>
                                    <td>Tác vụ</td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </thead>
                            <?php
                            $stt = 1;
                            ?>
                            @if (session()->has('DanhSachTam'))
                                @foreach (session()->get('DanhSachTam') as $temp)
                                    <?php
                                    // $MSSVCut = substr($temp, 0, 10);
                                    $MSSVCut = Str::before($temp,'HoTen');
                                    $CutClass = substr($temp, -7);
                                    $HoTen = Str::between($temp, 'HoTen', 'MK');
                                    $Password = Str::between($temp, 'MK', $CutClass);
                                    ?>
                                    <tbody>
                                        <tr>
                                            <td>{{ $stt }}</td>
                                            <td>{{ $MSSVCut }}</td>
                                            <td>{{ $HoTen }}</td>
                                            <td>{{ $CutClass }}</td>
                                            <td>Chỉnh sửa</td>
                                            <td><a href="/Delete-id?id={{ $temp }}&num={{ $stt }}">Xóa
                                                    sinh viên</a></td>
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
