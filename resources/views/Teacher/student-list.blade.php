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
                <form>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="student-name">Họ tên:</label>
                                <input type="text" class="form-control" id="student-name">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="student-id">MSSV:</label>
                                <input type="text" class="form-control" id="student-id">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="student-serial">STT:</label>
                                <input type="text" class="form-control" id="student-serial">
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="class-name">Tên lớp:</label>
                                <input type="text" class="form-control" id="class-name">
                            </div>
                        </div>
                    </div>
                    <br>
                    <button type="button" class="btn btn-primary" onclick="filterData()">Tìm kiếm</button>
                    <button type="button" class="btn btn-primary" onclick="removeFilterData()">Xóa tất cả bộ lọc</button>
                </form>
            </div>

            <div class="col-md-12 detail">
                <style>
                    .detail
                    {
                        grid-template-columns: 15fr
                    }
                </style>
                <div class="class-list">
                    @foreach($getinfoclass as $key)
                    <span><strong>HỌC PHẦN:</strong>

                        {{-- Lập trình ứng dụng với Java  --}}


                        <?php
                            $classname = DB::table('mon_hoc')->where('MaTTMH',$key->MaTTMH)->distinct()->first();
                        ?>
                        <?php

                            $studentname = DB::table('sinh_vien')->where('MSSV',$key->MSSV)->distinct()->first();
                        ?>
                    @endforeach
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
                                        $check1 = DB::table('danh_sach_sinh_vien')->where('MSGV',$teacherid)->where('MaBuoi',1)->first();
                                        $check2 = DB::table('danh_sach_sinh_vien')->where('MSGV',$teacherid)->where('MaBuoi',2)->first();
                                        $check3 = DB::table('danh_sach_sinh_vien')->where('MSGV',$teacherid)->where('MaBuoi',3)->first();
                                        $check4 = DB::table('danh_sach_sinh_vien')->where('MSGV',$teacherid)->where('MaBuoi',4)->first();
                                        $check5 = DB::table('danh_sach_sinh_vien')->where('MSGV',$teacherid)->where('MaBuoi',5)->first();
                                        $check6 = DB::table('danh_sach_sinh_vien')->where('MSGV',$teacherid)->where('MaBuoi',6)->first();
                                        $check7 = DB::table('danh_sach_sinh_vien')->where('MSGV',$teacherid)->where('MaBuoi',7)->first();
                                        $check8 = DB::table('danh_sach_sinh_vien')->where('MSGV',$teacherid)->where('MaBuoi',8)->first();
                                        $check9 = DB::table('danh_sach_sinh_vien')->where('MSGV',$teacherid)->where('MaBuoi',9)->first();
                                    ?>
{{-- Buoi1--}}
                                    @if($check1 == null)
                                        <td class="buoi-hoc"><a href="/diem-danh?buoi=1">1</a></td>
                                    @else
                                        <td>1</td>
                                    @endif
{{-- Buoi2 --}}
                                    @if($check2 == null)
                                        <td class="buoi-hoc"><a href="/diem-danh?buoi=2">2</a></td>
                                    @else
                                        <td>2</td>
                                    @endif
{{-- Buoi3 --}}
                                    @if($check3)
                                        <td>3</td>
                                    @else
                                        <td class="buoi-hoc"><a href="/diem-danh?buoi=3">3</a></td>
                                    @endif
{{-- Buoi4 --}}
                                    @if($check4)
                                        <td>4</td>
                                    @else
                                        <td class="buoi-hoc"><a href="/diem-danh?buoi=4">4</a></td>
                                    @endif
{{-- Buoi5 --}}
                                    @if($check5)
                                        <td>5</td>
                                    @else
                                        <td class="buoi-hoc"><a href="/diem-danh?buoi=5">5</a></td>
                                    @endif
{{-- Buoi6 --}}
                                    @if($check6)
                                        <td>6</td>
                                    @else
                                        <td class="buoi-hoc"><a href="/diem-danh?buoi=6">6</a></td>
                                    @endif
{{-- Buoi7 --}}
                                    @if($check7)
                                        <td>7</td>
                                    @else
                                        <td class="buoi-hoc"><a href="/diem-danh?buoi=7">7</a></td>
                                    @endif
{{-- Buoi8 --}}
                                    @if($check8)
                                        <td>8</td>
                                    @else
                                        <td class="buoi-hoc"><a href="/diem-danh?buoi=8">8</a></td>
                                    @endif
{{-- Buoi9 --}}
                                    @if($check9)
                                        <td>9</td>
                                    @else
                                        <td class="buoi-hoc"><a href="/diem-danh?buoi=9">9</a></td>
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
                                <tr>
                                <?php
                                    $stt =0;
                                ?>


                                @foreach($getinfoclass as $allstudentlist)

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


                                    @if($allstudentlist->MaBuoi == 1)
                                        <td>x</td>
                                    @else
                                        <td></td>
                                    @endif
                                    @if($allstudentlist->MaBuoi == 2)
                                    <td>x</td>
                                    @else
                                        <td></td>
                                    @endif
                                    @if($allstudentlist->MaBuoi == 3)
                                    <td>x</td>
                                    @else
                                        <td></td>
                                    @endif
                                    @if($allstudentlist->MaBuoi == 4)
                                    <td>x</td>
                                    @else
                                        <td></td>
                                    @endif
                                    @if($allstudentlist->MaBuoi == 5)
                                    <td>x</td>
                                    @else
                                        <td></td>
                                    @endif
                                    @if($allstudentlist->MaBuoi == 6)
                                    <td>x</td>
                                    @else
                                        <td></td>
                                    @endif
                                    @if($allstudentlist->MaBuoi == 7)
                                    <td>x</td>
                                    @else
                                        <td></td>
                                    @endif
                                    @if($allstudentlist->MaBuoi == 8)
                                    <td>x</td>
                                    @else
                                        <td></td>
                                    @endif
                                    @if($allstudentlist->MaBuoi == 9)
                                    <td>x</td>
                                    @else
                                        <td></td>
                                    @endif
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <?php
                                        $checkall = DB::table('danh_sach_sinh_vien')->where('MSSV',$studentname->MSSV)->whereNotNull('MaBuoi')->distinct()->count('MaBuoi');
                                    ?>
                                    @if($checkall < 9)
                                    <td></td>
                                    <td></td>
                                    @else
                                        @if($allstudentlist->Diem14 != null)
                                            <td>$allstudentlist->Diem14</td>
                                        @else
                                            <td></td>
                                        @endif
                                        <td></td>
                                        @if($allstudentlist->Diem16 != null)
                                            <td>$allstudentlist->Diem16</td>
                                        @else
                                            <td></td>
                                        @endif

                                        <?php
                                            $diemqt = DB::table('ket_qua')->where('MaKQSV',$allstudentlist->MaKQSV)->first();
                                        ?>
                                        @if($diemqt->DiemQT != null)
                                            <td><?php echo $diemqt->DiemQT ?></td>
                                        @else
                                            <td></td>
                                        @endif
                                    @endif
                                </tr>
                            </tbody>
                            @endforeach

                        </table>
                    </div>
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
            </div>
        </div>
@stop


