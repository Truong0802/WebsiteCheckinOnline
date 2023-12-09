<?php
use Carbon\Carbon;
?>
@extends('layouts.master-student')

@section('content')


    <div id="ribbon">
        <ol class="breadcrumb">
            <li class="ng-star-inserted">
                <a>Thời khoá biểu</a>
            </li>
        </ol>
    </div>
    @if (session('error'))
        {{-- {{dd(session('error'))}} --}}
        <div class="alert alert-danger text-center">Bạn đang thực hiện hành vi không cho phép</div>
    @elseif(session('error2'))
        <div class="alert alert-danger text-center">{{ session('error2') }}</div>
    @elseif(session('success1'))
        <div class="alert alert-success text-center">{{ session('success1') }}</div>
    @endif
    <div class="mt-4" id="content">
        <div class="  mx-4">
            <div class="row mb-3">
                <div class="col-md-6 pr-0">
                    <header-tkb-hoc-vu>
                        <div>
                            <h1 class="page-title txt-color-blueDark">
                                <i class="fa-fw fa fa-graduation-cap"></i> Thời khoá biểu
                            </h1>
                        </div>
                    </header-tkb-hoc-vu>
                </div>
                <div class="col-md-6  mt-3 chon-tuan-p">
                    <?php
                    if (session()->has('BatDauTuan')) {
                        $startOfWeek = Carbon::parse(session()->pull('BatDauTuan'));
                        if (session()->has('KetThucTuan')) {
                            $endOfWeek = Carbon::parse(session()->pull('KetThucTuan'));
                        }
                    } else {
                        $startOfWeek = Carbon::now()->startOfWeek();
                        $endOfWeek = Carbon::now()->endOfWeek();
                    }

                    // $nextweek1 = $startOfWeek;
                    // $nextweek2 = $endOfWeek;

                    ?>
                    <div class="doi-tuan">
                        <div class="text-left">
                            <a href="/previous-week?day={{ $startOfWeek }}&toDay={{ $endOfWeek }}" type="button"
                                class="btn btn-primary ">
                                <i aria-hidden="true" class="fa fa-chevron-left"></i>
                            </a>
                        </div>
                        <div class="col-xs-8 text-center">
                            <label class="text-filter"> Từ ngày
                                <strong><?php echo $startOfWeek->format('d-m') . '-' . $startOfWeek->format('Y'); ?>
                                </strong> đến <strong><?php echo $endOfWeek->format('d-m') . '-' . $endOfWeek->format('Y'); ?></strong>
                            </label>
                        </div>
                        <div class="text-right">
                            <a href="/next-week?day={{ $startOfWeek }}&toDay={{ $endOfWeek }}" type="button"
                                class="btn btn-primary ">
                                <i aria-hidden="true" class="fa fa-chevron-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <section class="inner-tkb ng-star-inserted">
            <div id="inbox-content" class="inbox-body mx-4">
                <div class="well">
                    <div class="row">
                        <div class="col-md-12">
                            <div role="alert " class="alert alert-warning alert-dismissible ng-star-inserted ">
                                <i class="fa-fw fa fa-warning "></i>
                                <strong>Chú ý:</strong> Sinh viên thường xuyên theo dõi thời khoá biểu để sớm cập nhật thông
                                tin mới nhất!
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table font-size-lich-thi">
                                    <thead>
                                        <tr class="black-muted-bg size-min">
                                            <th class="STT" title="STT">Tiết</th>
                                            <style>
                                                .STT {
                                                    text-align: center;
                                                }
                                            </style>
                                            <th title="MMH-TM">Mã Môn học - Tên môn</th>
                                            <th title="TTTH">Thông tin tiết học</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($getallsubject as $tkblist)
                                            <?php
                                            $subjectlist = DB::table('lich_giang_day')
                                                ->where('MaNgay', $tkblist->MaNgay)
                                                ->first();
                                            $dateparse = Carbon::parse($subjectlist->NgayDay);
                                            ?>

                                            @if ($dateparse->between($startOfWeek, $endOfWeek))
                                                <?php
                                                //Chuyển từ string sang datetime để so sánh

                                                $dayOfWeek = $dateparse->format('l');
                                                $daysOfWeek = [
                                                    'Sunday' => 'Chủ nhật',
                                                    'Monday' => 'Thứ 2',
                                                    'Tuesday' => 'Thứ 3',
                                                    'Wednesday' => 'Thứ 4',
                                                    'Thursday' => 'Thứ 5',
                                                    'Friday' => 'Thứ 6',
                                                    'Saturday' => 'Thứ 7',
                                                ];

                                                ?>

                                                <tr>
                                                    <th colspan="4" class="bagroud">
                                                        <strong>
                                                            <?php
                                                            echo $daysOfWeek[$dayOfWeek] . ', ' . $dateparse->format('d/m') . '/' . $dateparse->format('Y');
                                                            ?>
                                                        </strong>

                                                    </th>
                                                </tr>



                                                <tr class="mt-3 cursor-pointer open-detail">
                                                    <td class="data-html" style="display: none;">

                                                        {{-- <div class="row mb-4">
                                                            <div class="col-md-12">
                                                                <h1 class="font-weight-bold text-center"> Tiếng Anh 6 (ENC106) </h1>
                                                            </div>
                                                        </div> --}}
                                                        <div class="row mb-2">
                                                            <div class="col-md-12">
                                                                <h4 style="text-decoration: underline;">Thông tin chi tiết
                                                                </h4>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12 well">
                                                                {{-- <table class="table table-condensed table-detail">
                                                                    <tbody>
                                                                        <tr>
                                                                            <td width="120">Lớp:</td>
                                                                            <td class="font-weight-bold"> 20DTHA2 </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td width="120">Phòng học:</td>
                                                                            <td class="font-weight-bold"> E1-09.01 </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td width="120">Thứ:</td>
                                                                            <td> Thứ 5 </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td width="120">Tiết:</td>
                                                                            <td class="font-weight-bold"> 2-6 </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td width="120"> Giảng viên: </td>
                                                                            <td> LÐT0020478 </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table> --}}
                                                            </div>
                                                        </div>

                                                    </td>

                                                    <td class=" text-center pt-4">
                                                        <strong class="title-red"> {{ $subjectlist->MaTietHoc }}</strong>
                                                    </td>
                                                    <td class=" pt-4">
                                                        <?php
                                                        $subjectname = DB::table('mon_hoc')
                                                            ->where('MaTTMH', $subjectlist->MaTTMH)
                                                            ->first();
                                                        if (session()->exists('teacherid')) {
                                                            echo '<a href=' . '/danh-sach-lop?' . 'lop=' . $subjectlist->MaTTMH . ' >' . $subjectname->MaMH . ' - ' . $subjectname->TenMH . '</a>';
                                                        } else {
                                                            echo '<Strong >' . $subjectname->MaMH . ' - ' . $subjectname->TenMH . '</Strong>';
                                                        }

                                                        ?>
                                                    </td>
                                                    <td class=" pt-4">
                                                        <p>
                                                            {{-- <span> Phòng: <strong> E1-09.01 - </strong>
                                                            </span> --}}

                                                            <span> Lớp: {{ $subjectlist->MaLop }} </span>

                                                        </p>
                                                    </td>

                                                    @if (session()->exists('teacherid'))
                                                        <td class=" pt-4"><a
                                                                href="/buoi?num={{ $subjectlist->MaBuoi }}">ĐIỂM DANH</a>
                                                        </td>
                                                    @else
                                                        <td class=" pt-4"></td>
                                                    @endif

                                                </tr>
                                            @endif
                                        @endforeach

                                    </tbody>

                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section>
            <div class="modal fade ql-user" role="dialog" tabindex="-1">
                <div class="modal-dialog detail-sinh-vien">
                    <div class="content modal-content bg-modal modal-style-custom">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <loader>
                                        <div class="chasing-dots-spinner">
                                            <div class="dot1 ng-star-inserted" style="background-color: red;"></div>
                                            <div class="dot2 ng-star-inserted" style="background-color: red;"></div>
                                        </div>
                                    </loader>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-default">Quay lại</button>
                        </div>
                    </div>
                </div>
            </div>
        </section>


        <?php
        if (session()->exists('studentid')) {
            $checkConfirmOrNot = DB::table('sinh_vien')
                ->where('MSSV', session()->get('studentid'))
                ->first();
        }
        ?>
        @if ($checkConfirmOrNot)

            @if ($checkConfirmOrNot->Confirmed != 1)
                <!--Xuất popup để chuyển qua trang xác thực khi bấm Ok-->
                <div class="popup-container" id="popup">
                    <div class="popup-content">
                        <h2>Thông báo</h2>
                        <p>Bạn cần thay đổi thông tin mật khẩu</p>
                        <a class="btn-change" type="button" href="/xac-nhan-nguoi-dung">Đi thay đổi</a>
                        <button class="btn-primary" onclick = "closePopup()">Đóng</button>
                    </div>

                    <script>
                        const popup = document.getElementById("popup");

                        function showPopup() {
                            popup.style.display = "flex";
                        }

                        function closePopup() {
                            popup.style.display = "none";
                        }
                        window.onload = showPopup;
                    </script>
                </div>
            @else
            @endif

        @endif

    </div>
@stop
