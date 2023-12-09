@extends('layouts.master-admin')

@section('content')
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
                            <i class="fa-fw fa fa-graduation-cap"></i> Lớp học
                        </h1>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <form action='/tim-kiem-sinh-vien' method='get'>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="student-id">Tên lớp:</label>
                            <input type="text" class="form-control" id="student-id" name="mssv">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="student-name">Tên môn:</label>
                            <input type="text" class="form-control" id="student-name" name="studentname">
                        </div>
                    </div>
                </div>
                <br>
                <div class="btn-container">
                    <button type="submit" class="btn btn-primary" onclick="filterData()">Thêm lớp</button>
                </div>

                @csrf
            </form>
            <div class="col-md-12 detail">
                <style>
                    .detail {
                        grid-template-columns: 15fr
                    }
                </style>
                <div class="class-list">
                    <div class="col-md-12 detail">
                        <table>
                            <thead>
                                <tr>
                                    <td>STT</td>
                                    <td>Mã môn</td>
                                    <td>Môn học</td>
                                    <td>Lớp</td>
                                    <td>Nhóm môn</td>
                                    <td>Tiết</td>
                                    <td>Số tín chỉ</td>
                                    <td>Tên giảng viên</td>
                                    <td></td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>CMP101</td>
                                    <td>Công nghệ phần mềm</td>
                                    <td>20DTHA2</td>
                                    <td>02</td>
                                    <td>5</td>
                                    <td>3</td>
                                    <td>Nguyễn Hữu Trung</td>
                                    <td><a href=""><i class="fa-regular fa-eye"></a></i></td>
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
