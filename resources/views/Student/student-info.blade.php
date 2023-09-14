<?php
    use Carbon\Carbon;
?>

@extends('layouts.master-student')

@section('content')
<div class="mt-4" id="content">
    <div class="  mx-4">
        <div class="row mb-3">
            <div class="col-md-6 pr-0">
                <div>
                    <h1 class="page-title txt-color-blueDark">
                        <i class="fa fa-lg fa-fw fa-user"></i> Thông tin sinh viên
                    </h1>
                </div>
            </div>
        </div>
    </div>

    <style>
        .inner-class .custom-info
        {
            margin-left: 20px;
        }

        .custom-info .custom-list-li .info
        {
            font-weight: bold;
        }
    </style>

    <div class="inner-class">
        <div class="row well m-3">
            <div class="col-md-3 custom-avatar p-0 m-0 mt-4 mb-4 mx-4" style = "width: 150px;">
                {{-- <img alt="" class="online" src="/assets/img/avatar.png"> --}}
                <img src="https://api.hutech.edu.vn/files/public/file-avatar/mq/d536e02ea9ba2e126486358694479d7c.jpegpng" class="img-responsive" style="margin:auto" onerror="this.src='assets/images/noimage.jpg'">
            </div>
            <section class="col-md-9 custom-info mt-4 mb-4 mx-4">
                <ul class="list-unstyled custom-list-li">
                    <li>Họ tên:
                        <span class="info">Hồ Phú Tài</span>
                    </li>
                    <li>Mã số sinh viên:
                        <span class="info">2011060957</span>
                    </li>
                    <li>Chương trình:
                        <span class="info">Chưa cập nhật</span>
                    </li>
                    <li>Hệ đào tạo:
                        <span class="info">Chưa cập nhật</span>
                    </li>
                    <li>Khoa:
                        <span class="info">Khoa Công Nghệ Thông Tin</span>
                    </li>
                    <li>Lớp:
                        <span class="info">20DTHA2</span>
                    </li>
                    <li> Niên khóa:
                        <span class="info">2020 - 2024</span>
                    </li>
                </ul>
            </section>
        </div>
    </div>


</div>
@stop
