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
                            <i class="fa-fw fa fa-graduation-cap"></i> Danh sách ban cán sự
                        </h1>
                    </div>
                </div>
            </div>
        </div>

        <form method="post" action="{{ route('addClassManage') }}">
            <div class="col-md-12 detail">
                <style>
                    .detail {
                        grid-template-columns: 15fr;
                    }

                    .class-list {
                        width: 80%;
                    }

                    #class-name,
                    #class-group {
                        border: none;
                        width: 10%;
                    }

                    #class-group {
                        width: 5%;
                    }

                    .help {
                        cursor: pointer;
                        color: #adadad;
                    }
                </style>
                <div class="class-list">
                    @if (session('error-inputLeader'))
                        <div class="alert alert-danger text-center">{{ session('error-inputLeader') }}</div>
                    @endif
                    @if (session('success-AddLeader'))
                        <div class="alert alert-success text-center">{{ session('success-AddLeader') }}</div>
                    @endif
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
                            <tbody id="tableBody2"></tbody>
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
            <button class="btn btn-primary" onclick="uploadAndConvert2()">Chuyển đổi</button>
            {{-- <button class="btn btn-success" onclick="uploadAndConvert()">Thêm danh sách</button> --}}
        </div>
        <br><br><br>
    </div>
    <script src="<?php echo asset('/js/scan-img.js'); ?>"></script>

@stop
