@extends('layouts.master-teacher')

@section('content')

        <div id="ribbon">
            <span class="ribbon-button-alignment">
                <span class="btn btn-ribbon" id="refresh" placement="bottom">
                    <i class="fa fa-refresh"></i>
                </span>
            </span>
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

            <div class="container">
                <form action='/tim-kiem-sinh-vien' method='get'>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="student-name">Họ tên:</label>
                                <input type="text" class="form-control" id="student-name" name="studentname">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="student-id">MSSV:</label>
                                <input type="text" class="form-control" id="student-id" name="mssv">
                            </div>
                        </div>
                        {{-- <div class="col-md-4">
                            <div class="form-group">
                                <label for="student-serial">STT:</label>
                                <input type="text" class="form-control" id="student-serial">
                            </div>
                        </div> --}}
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="class-name">Tên lớp:</label>
                                <input type="text" class="form-control" id="class-name" name="classname">
                            </div>
                        </div>
                    </div>
                    <br>
                    <button type="submit" class="btn btn-primary" onclick="filterData()">Tìm kiếm</button>
                    <a type="button" href="/xoa-tim-kiem-sv" class="btn btn-primary" onclick="removeFilterData()">Xóa tất cả bộ lọc</a>
                    @csrf
                </form>
            </div>
            @foreach($getinfoclass as $key)
            <?php
            if($key != null)
            {
                $listid = substr($key->MaDanhSach, 0, -1);

                $classname = DB::table('mon_hoc')->where('MaTTMH',$key->MaTTMH)->distinct()->first();
            }

            ?>
            @endforeach
            <br><br>
        @if(isset($listid) && isset($classname))
            @if(DB::table('diem_danh')->where('MaDanhSach','like',$listid.'%')->where('MaBuoi',9)->exists())
                <div class="container">
                    <strong>Hãy lựa chọn cách tính điểm chuyên cần:</strong>
                    <form action="/option-row-14" method="post">
                        <br>
                        <div class="col-md-4">
                            <div class="form-group">
                                <input type="radio" name="divideall" value="{{$listid}}" id="divideall">
                                <label>Chia đều 3 điểm cho 9 buổi</label>
                                <button type="button" id="openModal1">Chi tiết</button>
                                <div id="myModal1" class="modal1">
                                    <div class="modal-content">
                                        <span class="close1">&times;</span>
                                        <h2>Hướng dẫn</h2>
                                        <p>Chia 3 điểm chuyên cần đều cho 9 buổi ( 0.33 điểm cho từng buổi )</p>
                                        <p>VD: Điểm danh được 9 buổi = 2.97 ( Làm tròn thành 3 ), Điểm danh được 7 buổi = 2.31 ( Làm tròn thành 2 )</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="col-md-4">
                            <div class="form-group">
                                <input type="radio" name="divide3" value="{{$listid}}" id="divide3">
                                <label>Chia lấy 3 buổi 1 điểm</label>
                                <button type="button" id="openModal2">Chi tiết</button>
                                <div id="myModal2" class="modal2">
                                    <div class="modal-content">
                                        <span class="close2">&times;</span>
                                        <h2>Hướng dẫn</h2>
                                        <p>Chia 9 buổi ra thành 3 buổi nhỏ</p>
                                        <p>Cách tính: 3 buổi nhỏ = 1 điểm, 2 buổi nhỏ = 0.5 điểm, 1 buổi nhỏ = 0 điểm</p>
                                        <p>VD: Điểm danh được 3 3 3 ( Buổi nhỏ ) = 3 điểm, Điểm danh 3 2 1 ( Buổi nhỏ ) = 1.5 điểm </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                        <button type="submit" class="btn btn-primary" >Chọn</button>
                    @csrf
                    </form>
                    <script>
                        var divideall = document.getElementById("divideall");
                        var divide3 = document.getElementById("divide3");

                        var modal1 = document.getElementById("myModal1");
                        var btn1 = document.getElementById("openModal1");
                        var modal2 = document.getElementById("myModal2");
                        var btn2 = document.getElementById("openModal2");
                        var closeBtn1 = document.getElementsByClassName("close1")[0];
                        var closeBtn2 = document.getElementsByClassName("close2")[0];

                        divideall.addEventListener("change", function() {
                            if (this.checked) {
                                divide3.checked = false;
                            }
                        });

                        divide3.addEventListener("change", function() {
                            if (this.checked) {
                                divideall.checked = false;
                            }
                        });

                        btn1.addEventListener("click", function()
                        {
                            modal1.style.display = "block";
                        });

                        btn2.addEventListener("click", function()
                        {
                            modal2.style.display = "block";
                        });


                        closeBtn1.addEventListener("click", function()
                        {
                            modal1.style.display = "none";
                        });

                        closeBtn2.addEventListener("click", function()
                        {
                            modal2.style.display = "none";
                        });

                        window.addEventListener("click", function(event)
                        {
                            if (event.target == modal1 && event.target == modal2)
                            {
                                modal1.style.display = "none";
                                modal2.style.display = "none";
                            }
                        });
                    </script>
                </div>
            @endif
            <br><br>
            <div class="col-md-12 detail">
                <style>
                    .detail
                    {
                        grid-template-columns: 15fr
                    }
                </style>
                <div class="class-list">
                        <span><strong>HỌC PHẦN:</strong>
                            {{-- Lập trình ứng dụng với Java  --}}
                            <?php
                                echo $classname->TenMH;
                            ?>
                        <strong> <?php echo $classname->MaMH ?> </strong> - (Nhóm <?php echo $classname->NhomMH ?>) - Số tín chỉ: <?php echo $classname->STC ?></span>
                        <br><br>
                        <div class="table">
                            <table>
                                <thead>
                                    <tr>
                                        <td>STT</td>
                                        <td>Mã SV</td>
                                        <td>Họ tên</td>
                                        <td>Tên lớp</td>
                                        <?php
                                            $teacherid = session()->get('teacherid');
                                            // $check = DB::table('diem_danh')->where('MaDanhSach','like','%'.$listid.'%')->where('MaBuoi',1)->exists();
                                        ?>

    {{-- Buoi1--}}
                                        @if(DB::table('diem_danh')->where('MaDanhSach','like',$listid.'%')->where('MaBuoi',1)->exists())
                                            <td>1</td>
                                        @else
                                            <td class="buoi-hoc"><a href="/diem-danh?lop={{$classname->MaTTMH}}&buoi=1">1</a></td>
                                        @endif
    {{-- Buoi2 --}}
                                        @if(DB::table('diem_danh')->where('MaDanhSach','like',$listid.'%')->where('MaBuoi',2)->exists())

                                            <td>2</td>
                                        @else
                                            <td class="buoi-hoc"><a href="/diem-danh?lop={{$classname->MaTTMH}}&buoi=2">2</a></td>
                                        @endif
    {{-- Buoi3 --}}
                                        @if(DB::table('diem_danh')->where('MaDanhSach','like',$listid.'%')->where('MaBuoi',3)->exists())
                                            <td>3</td>
                                        @else
                                            <td class="buoi-hoc"><a href="/diem-danh?lop={{$classname->MaTTMH}}&buoi=3">3</a></td>
                                        @endif
    {{-- Buoi4 --}}
                                        @if(DB::table('diem_danh')->where('MaDanhSach','like',$listid.'%')->where('MaBuoi',4)->exists())
                                            <td>4</td>
                                        @else
                                            <td class="buoi-hoc"><a href="/diem-danh?lop={{$classname->MaTTMH}}&buoi=4">4</a></td>
                                        @endif
    {{-- Buoi5 --}}
                                        @if(DB::table('diem_danh')->where('MaDanhSach','like',$listid.'%')->where('MaBuoi',5)->exists())
                                            <td>5</td>
                                        @else
                                            <td class="buoi-hoc"><a href="/diem-danh?lop={{$classname->MaTTMH}}&buoi=5">5</a></td>
                                        @endif
    {{-- Buoi6 --}}
                                        @if(DB::table('diem_danh')->where('MaDanhSach','like',$listid.'%')->where('MaBuoi',6)->exists())
                                            <td>6</td>
                                        @else
                                            <td class="buoi-hoc"><a href="/diem-danh?lop={{$classname->MaTTMH}}&buoi=6">6</a></td>
                                        @endif
    {{-- Buoi7 --}}
                                        @if(DB::table('diem_danh')->where('MaDanhSach','like',$listid.'%')->where('MaBuoi',7)->exists())
                                            <td>7</td>
                                        @else
                                            <td class="buoi-hoc"><a href="/diem-danh?lop={{$classname->MaTTMH}}&buoi=7">7</a></td>
                                        @endif
    {{-- Buoi8 --}}
                                        @if(DB::table('diem_danh')->where('MaDanhSach','like',$listid.'%')->where('MaBuoi',8)->exists())
                                            <td>8</td>
                                        @else
                                            <td class="buoi-hoc"><a href="/diem-danh?lop={{$classname->MaTTMH}}&buoi=8">8</a></td>
                                        @endif
    {{-- Buoi9 --}}
                                        @if(DB::table('diem_danh')->where('MaDanhSach','like',$listid.'%')->where('MaBuoi',9)->exists())
                                            <td>9</td>
                                        @else
                                            <td class="buoi-hoc"><a href="/diem-danh?lop={{$classname->MaTTMH}}&buoi=9">9</a></td>
                                        @endif
                                        <td>10</td>
                                        <td>11</td>
                                        <td>12</td>
                                        <td>13</td>
                                        <td class="DCC"><a href="">14</a></td>
                                        <td>15</td>
                                        <td class="DKT"><a href="">16</a></td>
                                        <td>ĐQT</td>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $stt =0;
                                    ?>
                            <form action="/nhap-diem" method="post">
                                @if(DB::table('diem_danh')->where('MaDanhSach','like',$listid.'%')->where('MaBuoi',9)->exists())
                                    <button type="submit" class="btn btn-primary" >Xác nhận điểm</button>
                                @endif
                                    @foreach($getinfoclass as $allstudentlist)
                                    <?php
                                        // dd($getinfoclass);

                                        $studentname = DB::table('sinh_vien')->where('MSSV',$allstudentlist->MSSV)->first();
                                    ?>
                                    <tr>
                                        <td>
                                            <?php
                                                $stt+=1;
                                                echo $stt;
                                            ?>
                                        </td>

                                        <td>{{$studentname->MSSV}}</td>
                                        <td>
                                            <?php

                                                echo $studentname->HoTenSV;
                                            ?>
                                        </td>
                                        <td><?php echo $studentname->MaLop ?></td>

                                        @if(DB::table('diem_danh')->where('MaDanhSach',$allstudentlist->MaDanhSach)->where('MaBuoi',1)->exists())

                                            <td>x</td>
                                        @else
                                            <td></td>
                                        @endif
                                        @if(DB::table('diem_danh')->where('MaDanhSach',$allstudentlist->MaDanhSach)->where('MaBuoi',2)->exists())
                                        <td>x</td>
                                        @else
                                            <td></td>
                                        @endif
                                        @if(DB::table('diem_danh')->where('MaDanhSach',$allstudentlist->MaDanhSach)->where('MaBuoi',3)->exists())
                                        <td>x</td>
                                        @else
                                            <td></td>
                                        @endif
                                        @if(DB::table('diem_danh')->where('MaDanhSach',$allstudentlist->MaDanhSach)->where('MaBuoi',4)->exists())
                                        <td>x</td>
                                        @else
                                            <td></td>
                                        @endif
                                        @if(DB::table('diem_danh')->where('MaDanhSach',$allstudentlist->MaDanhSach)->where('MaBuoi',5)->exists())
                                        <td>x</td>
                                        @else
                                            <td></td>
                                        @endif
                                        @if(DB::table('diem_danh')->where('MaDanhSach',$allstudentlist->MaDanhSach)->where('MaBuoi',6)->exists())
                                        <td>x</td>
                                        @else
                                            <td></td>
                                        @endif
                                        @if(DB::table('diem_danh')->where('MaDanhSach',$allstudentlist->MaDanhSach)->where('MaBuoi',7)->exists())
                                        <td>x</td>
                                        @else
                                            <td></td>
                                        @endif
                                        @if(DB::table('diem_danh')->where('MaDanhSach',$allstudentlist->MaDanhSach)->where('MaBuoi',8)->exists())
                                        <td>x</td>
                                        @else
                                            <td></td>
                                        @endif
                                        @if(DB::table('diem_danh')->where('MaDanhSach',$allstudentlist->MaDanhSach)->where('MaBuoi',9)->exists())
                                        <td>x</td>
                                        @else
                                            <td></td>
                                        @endif

                                        <style>
                                            .detail .class-list table tr .score-input
                                            {
                                                padding: 0;
                                            }

                                            .table tbody tr td input
                                            {
                                                padding: 10px;
                                                width: 35px;
                                                border: none;
                                            }
                                        </style>
                                        <td class="score-input"><input type="text"></td>
                                        <td class="score-input"><input type="text"></td>
                                        <td class="score-input"><input type="text"></td>
                                        <td class="score-input"><input type="text"></td>

                                        @if(DB::table('diem_danh')->where('MaDanhSach','like',$listid.'%')->where('MaBuoi',9)->exists())
                                            <!-- Điểm 14 -->
                                            @if($allstudentlist->Diem14 != null)
                                                <td>{{$allstudentlist->Diem14}}</td>
                                            @else
                                                <td></td>
                                            @endif
                                            <td></td>
                                            <!-- Điểm 16 -->
                                            @if($allstudentlist->Diem16 != null)
                                                @if(session()->has('timeForChange'))
                                                    <td class="score-input"><input type="text" id="row16" name="row16[]" value="{{$allstudentlist->Diem16}}" >{{session()->push('row16',$allstudentlist->MaDanhSach)}}</td>
                                                @else
                                                    <td>{{$allstudentlist->Diem16}}</td>
                                                @endif
                                            @else
                                            {{-- Yêu cầu phải đi học hơn 70% số buổi --}}
                                                @if(DB::table('diem_danh')->where('MaDanhSach',$allstudentlist->MaDanhSach)->distinct()->count('MaBuoi') >= 7)
                                                    {{-- Nhập điểm --}}
                                                    <td class="score-input"><input type="text" id="row16" name="row16[]" >{{session()->push('row16',$allstudentlist->MaDanhSach)}}</td>
                                                @else
                                                    <td></td>
                                                @endif
                                            @endif

                                            <?php
                                                $diemqt = DB::table('ket_qua')->where('MaKQSV',$allstudentlist->MaKQSV)->first();
                                            ?>
                                            @if($diemqt->DiemQT != null)
                                                <td>
                                                    <?php echo $diemqt->DiemQT ?>
                                                </td>
                                            @else
                                                <td></td>
                                            @endif


                                    @else
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    @endif
                                </tr>
                                @endforeach
                                @csrf
                            </form>
                            </tbody>


                        </table>

                    </div>
                @endif
                </div>
            </div>
            {{ $getinfoclass->appends(request()->all())->links('pagination::bootstrap-4') }}
            {{-- <div class="text-center">
                <div class="pagination">
                    <ul class="pagination-list">
                        <li class="hidden-phone current"><a title="1" href="" class="pagenav">1</a></li>
                        <li class="hidden-phone next"><a title="2" href="" class="pagenav number">2</a></li>
                        <li class="hidden-phone next-page"><a title="Trang sau" href="" class="pagenav"><i class="fa fa-chevron-right"></i></a></li>
                    </ul>
                    <input type="hidden" name="limitstart" value="0">
                </div>
            </div> --}}
        </div>



@stop

