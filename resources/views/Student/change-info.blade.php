<?php
use Carbon\carbon;
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
                            <i class="fa fa-lg fa-fw fa-user"></i> Thông tin sinh viên
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

        <?php
        if (session()->exists('studentid')) {
            $Name = $dataBefore->HoTenSV;
            $MS = $dataBefore->MSSV;
            $ClassId = $dataBefore->MaLop;
            $getIdNienKhoa = DB::table('lop')
                ->where('MaLop', $ClassId)
                ->first();

            $NienKhoa = DB::table('khoa_hoc')
                ->where('KhoaHoc', $getIdNienKhoa->KhoaHoc)
                ->first();
            $getNameOfDepartment = null;
            if ($dataBefore->HinhDaiDienSV == null) {
                $imgAvatar = 'ori-ava.png';
            } else {
                $imgAvatar = $dataBefore->HinhDaiDienSV;
            }
        } else {
            if (session()->exists('teacherid')) {
                $Name = $dataBefore->HoTenGV;
                $MS = $dataBefore->MSGV;
                $getDepartmentId = $dataBefore->MaKhoa;
                $getNameOfDepartment = DB::table('khoa')
                    ->where('MaKhoa', $getDepartmentId)
                    ->first();
                //dd($getNameOfDepartment);
                $NienKhoa = null;
                $ClassId = null;
                if ($dataBefore->HinhDaiDienGV == null) {
                    $imgAvatar = 'ori-ava.png';
                } else {
                    $imgAvatar = $dataBefore->HinhDaiDienGV;
                }
            }
        }
        ?>

        <form action="{{ route('ChangeInfo') }}" method="post" enctype="multipart/form-data">
            <div class="inner-class">
                <div class="row well m-3">
                    <div class="col-md-3 custom-avatar p-0 m-0 mt-4 mb-4 mx-4" style = "width: 150px;">
                        <div class="custom-line">
                            <img alt="" class="online img-show" style = "width: 200px;" src="{{ asset('img/Avatar/' . $imgAvatar) }}">
                            <input class="img-upload" type="file" accept="image/*" name="imagePath" size="30" onchange="previewImage(event)"/>
                            <script>
                                function previewImage(event)
                                {
                                    const input = event.target;
                                    if (input.files && input.files[0])
                                    {
                                        const reader = new FileReader();
                                        reader.onload = function (e)
                                        {
                                            const imgShow = document.querySelector('.img-show');
                                            imgShow.src = e.target.result;
                                        };
                                        reader.readAsDataURL(input.files[0]);
                                    }
                                }
                            </script>
                        </div>
                        <div class="vertical-line"></div>
                    </div>
                    <section class="col-md-9 jarviswidget custom-info mt-4 mb-4 mx-4">
                        <div class="widget-body">
                            <div class="form-horizontal">
                                <ul class="list-unstyled custom-list-li">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <li>Họ tên:
                                                <span class="info">{{ $Name }}</span>
                                            </li>
                                        </div>
                                        <div class="col-md-6">
                                            <li>Hệ đào tạo:
                                                <span class="info">Chưa cập nhật</span>
                                            </li>
                                        </div>
                                        <div class="col-md-6">
                                            <li>Niên khóa:
                                                @if (session()->exists('studentid'))
                                                    <span class="info">{{ $NienKhoa->NamHocDuKien }}</span>
                                                @endif
                                            </li>
                                        </div>
                                        <div class="col-md-6">
                                            <li>Chương trình:
                                                <span class="info">Chưa cập nhật</span>
                                            </li>
                                        </div>
                                        <div class="col-md-6">
                                            @if (session()->exists('studentid'))
                                                <li>Mã số sinh viên:
                                                    <span class="info">{{ $MS }}</span>
                                                </li>
                                            @else
                                                @if (session()->exists('teacherid'))
                                                    <li>Mã số giảng viên:
                                                        <span class="info">{{ $MS }}</span>
                                                    </li>
                                                @endif
                                            @endif
                                        </div>
                                        <div class="col-md-6">
                                            <li>Khoa:
                                                <span class="info">Khoa Công Nghệ Thông Tin</span>
                                            </li>
                                        </div>
                                        <div class="col-md-6">
                                            @if (session()->exists('studentid'))
                                                <li>Lớp:
                                                    <span class="info">{{ $ClassId }}</span>
                                                </li>
                                            @endif
                                        </div>
                                    </div>
                                    <li>
                                        <legend>
                                            <h6 class="font-weight-bold" style="font-size: 18px">Thông tin cá nhân</h6>
                                        </legend>
                                        <ul class="user-info">
                                            <div class="col-md-12">
                                                <div class="row form-group">
                                                    <div class="col-md-2">
                                                        <label for="info" class="control-label">Email: </label>
                                                    </div>
                                                    <div class="col-md-4">
                                                        @if ($dataBefore->Email == null)
                                                            <input class="info form-control" style="max-width: 250px; height: 38px" name="mailDetail" type="text" placeholder="abc@gmail.com">
                                                        @else
                                                            <input class="info form-control" style="max-width: 250px; height: 38px" name="mailDetail" type="text"
                                                                placeholder="{{ $dataBefore->Email }}">
                                                        @endif
                                                        @error('mailDetail')
                                                            <div class="alert alert-danger">{{ $errors->first('mailDetail') }}</div>
                                                        @enderror
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label for="info" class="control-label">Số điện thoại: </label>
                                                    </div>
                                                    <div class="col-md-4">
                                                        @if ($dataBefore->SDT == null)
                                                            <input class="info form-control" style="max-width: 250px; height: 38px" name="phoneNum" type="text" placeholder="09xxxxxx99">
                                                        @else
                                                            <input class="info form-control" style="max-width: 250px; height: 38px" name="phoneNum" type="text"
                                                                placeholder="{{ $dataBefore->SDT }}">
                                                        @endif
                                                        @error('phoneNum')
                                                            <div class="alert alert-danger">{{ $errors->first('phoneNum') }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="col-md-12">
                                                <div class="row form-group">
                                                    <div class="col-md-2">
                                                        <label for="info" class="control-label">Ngày tháng năm sinh: </label>
                                                    </div>
                                                    <div class="col-md-4">
                                                        @if (session()->exists('studentid'))
                                                            @if ($dataBefore->NgaySinh == null)
                                                                <input type="date" style="max-width: 250px; height: 38px" class="info form-control" name="birthday">
                                                            @else
                                                                <input type="date" style="max-width: 250px; height: 38px" class="info form-control" name="birthday"
                                                                    value="{{ Carbon::parse($dataBefore->NgaySinh)->toDateString() }}">
                                                            @endif
                                                        @elseif(session()->exists('teacherid'))
                                                            @if ($dataBefore->NgaySinhGV == null)
                                                                <input type="date" style="max-width: 250px; height: 38px" class="info form-control" name="birthday">
                                                            @else
                                                                <input type="date" style="max-width: 250px; height: 38px" class="info form-control" name="birthday"
                                                                    value="{{ Carbon::parse($dataBefore->NgaySinhGV)->toDateString() }}">
                                                            @endif
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </ul>
                                    </li>
                                    <li>
                                        <legend>
                                            <h6 class="font-weight-bold" style="font-size: 18px">Địa chỉ</h6>
                                        </legend>
                                        <ul class="user-info">
                                            <div class="col-md-12">
                                                <div class="row form-group">
                                                    <div class="col-md-2">
                                                        <label for="info" class="control-label">Số nhà: </label>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <input class="info form-control" name="address" style="max-width: 250px; height: 38px" type="text">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label for="info" class="control-label">Tỉnh / Thành phố:</label>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <select class="info form-control" style="max-width: 250px; height: 38px" id="city" name="city">
                                                            <option style="max-width: 250px; height: 38px" value="" selected>Chọn tỉnh / thành phố</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="col-md-12">
                                                <div class="row form-group">
                                                    <div class="col-md-2">
                                                        <label for="info" class="control-label">Quận / Huyện:</label>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <select class="info form-control" style="max-width: 250px; height: 38px" id="district" name="district">
                                                            <option style="max-width: 250px; height: 38px"  value="" selected></option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label for="info" class="control-label">Phường / Xã:</label>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <select class="info form-control" style="max-width: 250px; height: 38px" id="ward" name="ward">
                                                            <option style="max-width: 250px; height: 38px"  value="" selected></option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" referrerpolicy="no-referrer"></script>
                                            <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.21.1/axios.min.js"></script>
                                            <script src="<?php echo asset('/js/provinces.js'); ?>"></script>
                                        </ul>
                                    </li>
                                    <li>
                                        <br>
                                        <button type="submit" class="btn btn-success" id="page-back">Xác nhận</button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </section>

                </div>
            </div>
            @csrf
        </form>
    </div>
@stop
