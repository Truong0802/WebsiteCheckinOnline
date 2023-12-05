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

    <form method="post" action="{{route('scanPost')}}">
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
                #class-name, #class-group
                {
                    border: none;
                    width: 10%;
                }
                #class-group
                {
                    width: 5%;
                }
                .help
                {
                    cursor: pointer;
                    color: #adadad;
                }
            </style>
            <div class="class-list">
                <span>
                    <strong>HỌC PHẦN:</strong> Lập trình ứng dụng với Java <strong>( <input id="class-name" name="class-name" placeholder="VD: CMP0000"> )</strong> - Nhóm: <input id="class-group" name="class-group" placeholder="VD: 80"> - Số tín chỉ: 3
                    <i class="fa-regular fa-circle-question help" id="open"></i>
                    <div id="myModal1" class="modal">
                        <div class="modal-content1">
                            <span class="close"><i class="fa-solid fa-xmark"></i></span>
                            <br>
                            <h2>Nhập mã môn và nhóm môn vào ô</h2>
                        </div>
                    </div>
                    <script>
                        var modal = document.getElementById("myModal1");
                        var btn1 = document.getElementById("open");
                        var closeBtn1 = document.getElementsByClassName("close")[0];

                        btn1.addEventListener("click", function()
                        {
                            modal.style.display = "block";
                        });

                        closeBtn1.addEventListener("click", function()
                        {
                            modal.style.display = "none";
                        });

                        window.addEventListener("click", function(event)
                        {
                            if (event.target == modal)
                            {
                                modal.style.display = "none";
                            }
                        });
                    </script>
                </span>
                <div class="table">
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
                    </table>
                </div>
            </div>
        </div>
        <br>
        <div class="container">
            <button type="submit" class="btn btn-success" id="submit_button" name="submit_button">Thêm danh sách</button>
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
<script src="<?php echo asset('/js/scan-img.js')?>"></script>

@stop
