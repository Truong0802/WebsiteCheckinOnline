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

        <?php
            if(session()->exists('studentid'))
            {
                $Name = $dataBefore->HoTenSV;
                $MS = $dataBefore->MSSV;
                $ClassId = $dataBefore->MaLop;
                $getIdNienKhoa = DB::table('lop')->where('MaLop',$ClassId)->first();

                $NienKhoa = DB::table('khoa_hoc')->where('KhoaHoc',$getIdNienKhoa->KhoaHoc)->first();
                $getNameOfDepartment = null;
                if($dataBefore->HinhDaiDien ==null )
                {
                    $imgAvatar = 'ori-ava.png';
                }
                else {
                    $imgAvatar = $dataBefore->HinhDaiDien;
                }

            }
            else
            {
                if(session()->exists('teacherid')){
                    $Name = $dataBefore->HoTenGV;
                    $MS = $dataBefore->MSGV;
                    $getDepartmentId = $dataBefore->MaKhoa;
                    $getNameOfDepartment = DB::table('khoa')->where('MaKhoa',$getDepartmentId)->first();
                    //dd($getNameOfDepartment);
                    $NienKhoa = null;
                    $ClassId = null;
                    if($dataBefore->HinhDaiDien ==null )
                    {
                        $imgAvatar = 'ori-ava.png';
                    }
                    else {
                        $imgAvatar = $dataBefore->HinhDaiDien;
                    }
                }

            }
        ?>

    <form action="{{ route('ChangeInfo') }}" method="post" enctype="multipart/form-data">
        <div class="inner-class">
            <div class="row well m-3">

                <div class="col-md-3 custom-avatar p-0 m-0 mt-4 mb-4 mx-4" style = "width: 150px;">
                    <img alt="" class="online" style = "width: 150px;" src="{{asset('img/Avatar/'.$imgAvatar)}}">
                    <input class="img-upload" type="file" name="imagePath" size="30"/>
                </div>
                <section class="col-md-9 custom-info mt-4 mb-4 mx-4">
                    <ul class="list-unstyled custom-list-li">

                        <li>Họ tên:
                            <span class="info">{{$Name}}</span>
                        </li>
                        @if(session()->exists('studentid'))
                            <li>Mã số sinh viên:
                                <span class="info">{{$MS}}</span>
                            </li>
                        @else
                            @if(session()->exists('teacherid'))
                                <li>Mã số giảng viên:
                                    <span class="info">{{$MS}}</span>
                                </li>
                            @endif
                        @endif
                        <li>Chương trình:
                            <span class="info">Chưa cập nhật</span>
                        </li>
                        <li>Hệ đào tạo:
                            <span class="info">Chưa cập nhật</span>
                        </li>
                        <li>Khoa:
                            <span class="info">Khoa Công Nghệ Thông Tin</span>
                        </li>
                        @if(session()->exists('studentid'))
                            <li>Lớp:
                                <span class="info">{{$ClassId}}</span>
                            </li>
                        @endif
                            <li>Email:
                                @if($dataBefore->Email == null)
                                    <input class="info" name="mailDetail" type="text" placeholder="abc@gmail.com">
                                @else
                                    <input class="info" name="mailDetail" type="text" placeholder="{{$dataBefore->Email}}">
                                @endif
                                @error('mailDetail')
                                    <div class="alert alert-danger">{{ $errors->first('mailDetail') }}</div>
                                @enderror
                            </li>
                            <li>Số điện thoại:
                                @if($dataBefore->SDT == null)
                                    <input class="info" name="phoneNum" type="text" placeholder="09xxxxxx99">
                                @else
                                    <input class="info" name="phoneNum" type="text" placeholder="{{$dataBefore->SDT}}">
                                @endif
                                @error('phoneNum')
                                    <div class="alert alert-danger">{{ $errors->first('phoneNum') }}</div>
                                @enderror
                            </li>
                            @if(session()->exists('studentid'))
                                <li> Niên khóa:
                                    <span class="info">{{$NienKhoa->NamHocDuKien}}</span>
                                </li>

                            @endif
                        <li>Địa chỉ:
                            <ul>
                                <li>Thành phố / Tỉnh:
                                    <select class="info" id="city" name="city">
                                        <option value="" selected></option>
                                    </select>
                                </li>

                                <li>Quận / Huyện:
                                    <select class="info" id="district" name="district">
                                        <option value="" selected></option>
                                    </select>
                                </li>

                                <li>Phường / Xã:
                                    <select class="info" id="ward" name="ward">
                                        <option value="" selected></option>
                                    </select>
                                </li>

                                <li>Số nhà:
                                    <input class="info" name="address" type="text">
                                </li>

                                <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" referrerpolicy="no-referrer"></script>
                                <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.21.1/axios.min.js"></script>
                                <script src="<?php echo asset('/js/provinces.js')?>"></script>
                            </ul>
                        </li>


                        <li>
                            <br>
                            <button type="submit" class="btn btn-success" id="page-back">Xác nhận</button>
                        </li>
                    </ul>
                </section>

            </div>
        </div>
    @csrf
    </form>
    </div>
@stop
