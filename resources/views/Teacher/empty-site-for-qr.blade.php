@extends('layouts.master-teacher')

@section('content')
    <div id="ribbon">
        <ol class="breadcrumb">
            <li class="ng-star-inserted">
                <a>Danh sách Lớp Học</a>
            </li>
        </ol>
    </div>
    <div class="mt-4" id="content">
        <div class="  mx-4">
            <div class="row mb-3">
                <div class="col-md-6 pr-0">
                    <div>
                        <h1 class="page-title txt-color-blueDark">
                            <i class="fa-solid fa-qrcode"></i> QR Điểm danh
                        </h1>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <a type="button" href="/tro-ve" class="btn btn-primary" onclick="removeFilterData()">Trở về danh sách</a>
            <div class="container text-center">
                <div class="row">
                    <div class="col">
                        {{-- <p class="mb-0">QR ĐIỂM DANH</p> --}}
                        <br>
                        {!! $simple !!}
                    </div>
                </div>
            </div>
        </div>
        <br><br><br><br><br>
    </div>
@stop
