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
                            echo $classname->TenMH;
                        ?>
                    <strong> <?php echo $classname->MaMH ?> </strong> - (Nhóm <?php echo $classname->NhomMH ?>) - Số tín chỉ: <?php echo $classname->STC ?></span>
                    <br><br>
                    @endforeach
                    <div class="table">
                        <table>
                            <thead>
                                <tr>
                                    <td>STT</td>
                                    <td>Mã SV</td>
                                    <td>Họ tên</td>
                                    <td>Tên lớp</td>
                                    <td class="buoi-hoc"><a href="">1</a></td>
                                    <td class="buoi-hoc"><a href="">2</a></td>
                                    <td class="buoi-hoc"><a href="">3</a></td>
                                    <td class="buoi-hoc"><a href="">4</a></td>
                                    <td class="buoi-hoc"><a href="">5</a></td>
                                    <td class="buoi-hoc"><a href="">6</a></td>
                                    <td class="buoi-hoc"><a href="">7</a></td>
                                    <td class="buoi-hoc"><a href="">8</a></td>
                                    <td class="buoi-hoc"><a href="">9</a></td>
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
                                @foreach($getinfoclass as $allstudentlist)
                                <tr>
                                    <td>
                                    <?php
                                        $stt+=1;
                                        echo $stt;
                                    ?>
                                    </td>
                                    <td>{{$allstudentlist->MSSV}}</td>
                                    <td>
                                        <?php
                                            $studentname = DB::table('sinh_vien')->where('MSSV',$allstudentlist->MSSV)->first();
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
                                </tr>
                            </tbody>
                            @endforeach
                            {{-- <tbody>
                                <tr>
                                    <td>2</td>
                                    <td>2011060957</td>
                                    <td>Hồ Phú Tài</td>
                                    <td>20DTHA2</td>
                                    <td>x</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>x</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>

                                </tr>
                            </tbody>
                            <tbody>
                                <tr>
                                    <td>3</td>
                                    <td>2011062236</td>
                                    <td>Nguyễn Phương Minh</td>
                                    <td>20DTHA2</td>
                                    <td>x</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>x</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tbody>
                            <tbody>
                                <tr>
                                    <td>4</td>
                                    <td>20111061224</td>
                                    <td>Trần Nguyên Trường</td>
                                    <td>20DTHA2</td>
                                    <td>x</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>x</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tbody>
                            <tbody>
                                <tr>
                                    <td>5</td>
                                    <td>2011060957</td>
                                    <td>Hồ Phú Tài</td>
                                    <td>20DTHA2</td>
                                    <td>x</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>x</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>

                                </tr>
                            </tbody>
                            <tbody>
                                <tr>
                                    <td>6</td>
                                    <td>2011062236</td>
                                    <td>Nguyễn Phương Minh</td>
                                    <td>20DTHA2</td>
                                    <td>x</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>x</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tbody>
                            <tbody>
                                <tr>
                                    <td>7</td>
                                    <td>20111061224</td>
                                    <td>Trần Nguyên Trường</td>
                                    <td>20DTHA2</td>
                                    <td>x</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>x</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tbody>
                            <tbody>
                                <tr>
                                    <td>8</td>
                                    <td>2011060957</td>
                                    <td>Hồ Phú Tài</td>
                                    <td>20DTHA2</td>
                                    <td>x</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>x</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>

                                </tr>
                            </tbody>
                            <tbody>
                                <tr>
                                    <td>9</td>
                                    <td>2011062236</td>
                                    <td>Nguyễn Phương Minh</td>
                                    <td>20DTHA2</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tbody>
                            <tbody>
                                <tr>
                                    <td>10</td>
                                    <td>20111061224</td>
                                    <td>Trần Nguyên Trường</td>
                                    <td>20DTHA2</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tbody>
                            <tbody>
                                <tr>
                                    <td>11</td>
                                    <td>2011060957</td>
                                    <td>Hồ Phú Tài</td>
                                    <td>20DTHA2</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>

                                </tr>
                            </tbody>
                            <tbody>
                                <tr>
                                    <td>12</td>
                                    <td>2011062236</td>
                                    <td>Nguyễn Phương Minh</td>
                                    <td>20DTHA2</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tbody>
                            <tbody>
                                <tr>
                                    <td>13</td>
                                    <td>20111061224</td>
                                    <td>Trần Nguyên Trường</td>
                                    <td>20DTHA2</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tbody>
                            <tbody>
                                <tr>
                                    <td>14</td>
                                    <td>2011060957</td>
                                    <td>Hồ Phú Tài</td>
                                    <td>20DTHA2</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>

                                </tr>
                            </tbody>
                            <tbody>
                                <tr>
                                    <td>15</td>
                                    <td>2011062236</td>
                                    <td>Nguyễn Phương Minh</td>
                                    <td>20DTHA2</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tbody> --}}
                        </table>
                    </div>
                </div>
            </div>
        </div>
@stop


