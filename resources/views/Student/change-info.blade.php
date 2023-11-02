<?php
    use Carbon\carbon;
?>

@extends('layouts.master-student')

@section('content')
    <div id="ribbon">
        <span class="ribbon-button-alignment">
            <span class="btn btn-ribbon" id="refresh" placement="bottom">
                <i class="fa fa-refresh"></i>
            </span>
        </span>
        <ol class="breadcrumb">
            <li class="ng-star-inserted">
                <a>Hồ sơ cá nhân</a>
            </li>
        </ol>
    </div>
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
                    <img alt="" class="online" style = "width: 150px;" src="/assets/img/ori-ava.png">
                    <input class="img-upload" type="file" size="30"/>
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
                        <li>Email:
                            <span class="info">Chưa cập nhật</span>
                        </li>
                        <li>Số điện thoại:
                            <span class="info">Chưa cập nhật</span>
                        </li>
                        <li> Niên khóa:
                            <span class="info">2020 - 2024</span>
                        </li>
                        <li>
                            <br>
                            <button class="btn btn-success" id="page-back">Xác nhận</button>
                        </li>
                    </ul>
                </section>
            </div>
        </div>
    </div>
@stop
