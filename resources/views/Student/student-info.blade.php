<?php
use Carbon\Carbon;
?>

@extends('layouts.master-student')

@section('content')
    <div id="ribbon">
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
                            <i class="fa fa-lg fa-fw fa-user"></i> Thông tin cá nhân
                        </h1>
                    </div>
                </div>
            </div>
        </div>

        <style>
            .inner-class .custom-info {
                margin-left: 20px;
            }

            .custom-info .custom-list-li .info {
                font-weight: bold;
            }
        </style>

        <div class="inner-class">
            <div class="row well m-3 info-container">
                <div class="col-md-3 custom-avatar p-0 m-0 mt-4 mb-4 mx-4" style = "width: 150px;">
                    <?php

                    if (session()->exists('studentid')) {
                        if ($getInfoFromObject->HinhDaiDienSV == null) {
                            $imgAvatar = 'ori-ava.png';
                        } else {
                            $imgAvatar = $getInfoFromObject->HinhDaiDienSV;
                        }
                    } elseif (session()->exists('teacherid')) {
                        if ($getInfoFromObject->HinhDaiDienGV == null) {
                            $imgAvatar = 'ori-ava.png';
                        } else {
                            $imgAvatar = $getInfoFromObject->HinhDaiDienGV;
                        }
                    }

                    ?>
                    <img alt="" class="online img-responsive" style = "width: 250px;" style="margin:auto"
                        src="{{ asset('img/Avatar/' . $imgAvatar) }}">
                </div>
                <section class="col-md-9 custom-info mt-4 mb-4 mx-4">
                    <ul class="list-unstyled custom-list-li">
                        <?php
                        if (session()->exists('studentid')) {
                            $Name = $getInfoFromObject->HoTenSV;
                            $MS = $getInfoFromObject->MSSV;
                            $ClassId = $getInfoFromObject->MaLop;
                            $Birth = $getInfoFromObject->NgaySinh;
                            $getIdNienKhoa = DB::table('lop')
                                ->where('MaLop', $ClassId)
                                ->first();

                            $NienKhoa = DB::table('khoa_hoc')
                                ->where('KhoaHoc', $getIdNienKhoa->KhoaHoc)
                                ->first();
                            $getNameOfDepartment = null;
                            //dd($NienKhoa->NamHocDuKien);
                        } else {
                            if (session()->exists('teacherid')) {
                                $Name = $getInfoFromObject->HoTenGV;
                                $MS = $getInfoFromObject->MSGV;
                                $getDepartmentId = $getInfoFromObject->MaKhoa;
                                $Birth = $getInfoFromObject->NgaySinhGV;
                                $getNameOfDepartment = DB::table('khoa')
                                    ->where('MaKhoa', $getDepartmentId)
                                    ->first();
                                //dd($getNameOfDepartment);
                                $NienKhoa = null;
                                $ClassId = null;
                            }
                        }
                        ?>
                        <li>Họ tên:
                            <span class="info">{{ $Name }}</span>
                        </li>
                        @if (session()->exists('teacherid'))
                            <li>Mã số giảng viên:
                                <span class="info">{{ $MS }}</span>
                            </li>
                        @else
                            <li>Mã số sinh viên:
                                <span class="info">{{ $MS }}</span>
                            </li>
                        @endif

                        @if (session()->exists('teacherid'))
                            <li></li>
                            <li></li>
                        @else
                            <li>Chương trình:
                                <span class="info">Chưa cập nhật</span>
                            </li>

                            <li>Hệ đào tạo:
                                <span class="info">Chưa cập nhật</span>
                            </li>
                        @endif
                        <li>Khoa:
                            @if ($getNameOfDepartment != null)
                                <span class="info">{{ $getNameOfDepartment->TenKhoa }}</span>
                            @else
                                <span class="info">Khoa Công Nghệ Thông Tin</span>
                            @endif
                        </li>

                        @if ($ClassId != null)
                            <li>Lớp:
                                <span class="info">{{ $ClassId }}</span>
                            </li>
                        @else
                            <li></li>
                        @endif

                        <li>Email:
                            @if ($getInfoFromObject->Email == null)
                                <span class="info">Chưa cập nhật</span>
                            @else
                                <span class="info">{{ $getInfoFromObject->Email }}</span>
                            @endif
                        </li>

                        <li>Số điện thoại:
                            @if ($getInfoFromObject->SDT == null)
                                <span class="info">Chưa cập nhật</span>
                            @else
                                <span class="info">{{ $getInfoFromObject->SDT }}</span>
                            @endif
                        </li>

                        <li>Ngày tháng năm sinh:
                            @if ($Birth == null)
                                <span class="info">Chưa cập nhật</span>
                            @else
                                <span class="info">{{ $Birth }}</span>
                            @endif
                        </li>

                        <li>Địa chỉ:
                            @if ($getInforAddress->DiaChi == null)
                                <span class="info">Chưa cập nhật</span>
                            @else
                                <span
                                    class="info">{{ $getInforAddress->DiaChi . ', ' . $getInforAddress->Phuong . ', ' . $getInforAddress->Quan . ', ' . $getInforAddress->ThanhPho }}</span>
                            @endif
                        </li>



                        @if ($NienKhoa != null)
                            <li> Niên khóa:
                                <span class="info">{{ $NienKhoa->NamHocDuKien }}</span>
                            </li>
                        @else
                            <li></li>
                        @endif
                        <li>
                            <br>
                            <form action="/trang-thay-doi-thong-tin" method="get">
                                <button class="btn btn-primary" id="change-information">Thay đổi thông tin</button>
                            </form>

                        </li>
                    </ul>
                </section>
            </div>
        </div>


    </div>
@stop
