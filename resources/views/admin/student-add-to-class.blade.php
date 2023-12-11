@extends('layouts.master-admin')

@section('content')
    <div id="ribbon">
        <ol class="breadcrumb">
            <li class="ng-star-inserted">
                <a>Danh sách sinh viên</a>
            </li>
        </ol>
    </div>
    <div class="mt-4" id="content">
        <div class="  mx-4">
            <div class="row mb-3">
                <div class="col-md-6 pr-0">
                    <div>
                        <h1 class="page-title txt-color-blueDark">
                            <i class="fa fa-lg fa-fw fa-book"></i> Danh sách sinh viên
                        </h1>
                    </div>
                </div>
            </div>
        </div>

        <form method="post" action="{{ route('scanPost') }}">
            <div class="col-md-12 detail">
                <style>
                    .detail {
                        grid-template-columns: 15fr
                    }
                </style>
                <div class="class-list">
                    @if (session('error-input'))
                        <div class="alert alert-danger text-center">{{ session('error-input') }}</div>
                    @endif
                    <span>
                        <strong>HỌC PHẦN:</strong> <input id="class-name" name="subjectname"
                            placeholder="VD: Lập trình ứng dụng với java"> <strong>( <input id="class-id" name="classname"
                                placeholder="VD: CMP0000"> )</strong> - Nhóm: <input id="class-group" name="classgroup"
                            placeholder="VD: 80"> - Số tín chỉ: <input id="class-num" name="STC" placeholder="VD: 3">
                        <i class="fa-regular fa-circle-question help" id="open"></i>
                        <div id="myModal1" class="modal">
                            <div class="modal-content1">
                                <span class="close"><i class="fa-solid fa-xmark"></i></span>
                                <br>
                                <h2>Nhập mã môn và nhóm môn vào ô</h2>
                            </div>
                        </div>
                    </span>
                    <div class="table-add">
                        <table class="student-table" id="student-table">
                            <thead>
                                <tr>
                                    <td>STT</td>
                                    <td>Mã SV</td>
                                    <td>Họ tên</td>
                                    <td>Ngày sinh</td>
                                    <td>Tên lớp</td>
                                </tr>
                            </thead>
                            <tbody id="tableBody">
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
            <br>
            <div class="container">
                <button type="submit" class="btn btn-success" id="submit_button" name="submit_button">Thêm danh
                    sách</button>
            </div>
            @csrf
        </form>
        <br>
        <div class="container">
            <button class="btn btn-primary" onclick="exportToExcel()">Xuất Excel</button>
            <input type="file" id="uploadInput" accept="image/*">
            <button class="btn btn-primary" onclick="uploadAndConvert()">Chuyển đổi</button>
            {{-- <button class="btn btn-success" onclick="uploadAndConvert()">Thêm danh sách</button> --}}
        </div>
        <br><br><br>
    </div>
    <script src="<?php echo asset('/js/scan-img.js'); ?>"></script>
    <script src="<?php echo asset('/js/script.js'); ?>"></script>
    <script>
        btn1.addEventListener("click", function()
        {
            modal.style.display = "block";
        });

        closeBtn1.addEventListener("click", function()
        {
            modal.style.display = "none";
        });
    </script>
@stop
