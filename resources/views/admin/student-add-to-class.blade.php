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
						<i class="fa-fw fa fa-graduation-cap"></i> Danh sách sinh viên
					</h1>
				</div>
			</div>
		</div>
	</div>

	<div class="col-md-12 detail">
		<style>
			.detail
            {
				grid-template-columns: 15fr;
			}
            .class-list
            {
                width: 80%;
            }
		</style>
		<div class="class-list">
			<span>
				{{-- <strong>HỌC PHẦN:</strong> Lập trình ứng dụng với Java <strong> (CMP3025) </strong> - Nhóm 2 - Số tín chỉ: 3 </span> --}}
			<div class="table">
                <form method="post" action="{{route('scanPost')}}">
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
                        <tbody id="tableBody"></tbody>
                        </style>
                    </table>
                    <br>
                    <button type="submit" class="btn btn-success" name="submit_button">Thêm danh sách</button>
                    @csrf
                </form>
			</div>


		</div>
        <div class="container">
            </style>
            <button class="btn btn-primary" onclick="exportToExcel()">Xuất Excel</button>
            <input type="file" id="uploadInput" accept="image/*">
            <button class="btn btn-primary" onclick="uploadAndConvert()">Chuyển đổi</button>
            {{-- <button class="btn btn-success" onclick="uploadAndConvert()">Thêm danh sách</button> --}}
        </div>
	</div>

</div>
<script src="<?php echo asset('/js/scan-img.js')?>"></script>

@stop
