<?php
 use Carbon\Carbon;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo asset('/css/index.css')?>">
    <link rel="stylesheet" href="<?php echo asset('/fonts/fontawesome-free-6.2.1-web/fontawesome-free-6.2.1-web/css/all.css')?>">
    <link href="<?php echo asset('https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css')?>" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <title>Trang chủ</title>
</head>
<body>
    <aside id="left-panel">
        <div class="login-info">
            <span class="ng-star-inserted">
                <a>
                    <img alt="" class="online" src="/assets/img/avatar.png">
                    <span>
                        <?php
                            $studentname =session()->get('name');
                            if($studentname)
                            {
                                echo $studentname;
                            }
                        ?>
                    </span>
                    <i class="fa fa-angle-down"></i>
                </a>
            </span>
        </div>
        <nav>
            <ul>
                <li>
                    <a title="Trang chủ" href="">
                        <i class="fa fa-lg fa-fw fa-home"></i>
                        <span class="menu-item-parent">Trang chủ</span>
                    </a>
                </li>
                <li>
                    <a title="Hồ sơ cá nhân" href="">
                        <i class="fa fa-lg fa-fw fa-user"></i>
                        <span class="menu-item-parent">Hồ sơ cá nhân</span>
                    </a>
                </li>
                <li class="open">
                    <a href="" title="Học tập">
                        <i class="fa fa-lg fa-fw fa-book"></i>
                        <span class="menu-item-parent">Học tập </span>
                        <b class="collapse-sign">
                            <em class="fa fa-minus-square-o"></em>
                        </b>
                    </a>
                </li>
                <li>
                    <a title="Xác nhận online" href="#/sinhvien/xac-nhan-sinh-vien">
                        <i class="fa fa-lg fa-fw fa-calendar-o"></i>
                        <span class="menu-item-parent">Xác nhận online</span>
                    </a>
                </li>
                <li>
                    <a href="" title="Đánh giá rèn luyện">
                        <i class="fa fa-lg fa-fw fa-balance-scale"></i>
                        <span class="menu-item-parent">Đánh giá rèn luyện</span>
                        <b class="collapse-sign">
                            <em class="fa fa-plus-square-o"></em>
                        </b>
                    </a>
                    <ul>
                        <li>
                            <a href="">Điểm cá nhân</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="" title="Khảo sát">
                        <i class="fa fa-lg fa-fw fa-calendar-check-o"></i>
                        <span class="menu-item-parent">Khảo sát</span>
                        <b class="collapse-sign">
                            <em class="fa fa-plus-square-o"></em>
                        </b>
                    </a>
                    <ul>
                        <li>
                            <a title="Hoạt động giảng dạy" href="">Hoạt động giảng dạy</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="">
                        <i class="fa fa-lg fa-fw fa-bar-chart-o"></i>
                        <span class="menu-item-parent">Hoạt động sinh viên</span>
                        <b class="collapse-sign">
                            <em class="fa fa-plus-square-o"></em>
                        </b>
                    </a>
                    <ul>
                        <li>
                            <a href="">Hồ sơ SINH VIÊN 5 TỐT</a>
                        </li>
                        <li class="">
                            <a href="">Ghi nhận tham gia hoạt động</a>
                        </li>
                        <li>
                            <a href="">Tài liệu, Thông tin hoạt động</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="">
                        <i class="fa fa-lg fa-fw fa-cog"></i>
                        <span class="menu-item-parent"> Cài đặt tài khoản </span>
                    </a>
                </li>
                <li>
                    <a href="#" title="Khác">
                        <i class="fa fa-lg fa-fw fa-bars"></i>
                        <span class="menu-item-parent">Khác</span>
                        <b class="collapse-sign">
                            <em class="fa fa-plus-square-o"></em>
                        </b>
                    </a>
                    <ul>
                        <li>
                            <a href=>COVID Khai báo y tế </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a title="Hỗ trợ" href="">
                        <i class="fa fa-lg fa-fw fa-phone"></i>
                        <span class="menu-item-parent">Hỗ trợ</span>
                    </a>
                </li>
                <li>
                    <a  href="/logout"  title="Đăng xuất" href="">
                        <i class="fa fa-arrow-circle-left hit"></i>
                        <span class="menu-item-parent">Đăng xuất</span>
                    </a>
                </li>
            </ul>
        </nav>
        <span class="minifyme">
                <i class="fa fa-arrow-circle-left hit"></i>
        </span>
    </aside>

    <div id="main" role="main">
        <div id="ribbon">
            <span class="ribbon-button-alignment">
                <span class="btn btn-ribbon" id="refresh" placement="bottom">
                    <i class="fa fa-refresh"></i>
                </span>
            </span>
            <ol class="breadcrumb">
                <li class="ng-star-inserted">
                    <a>Thời khoá biểu</a>
                </li>
            </ol>
        </div>
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
                                    $startOfWeek = Carbon::now()->startOfWeek();
                                    $endOfWeek = Carbon::now()->endOfWeek();
                                    $nextweek1 = $startOfWeek;
                                    $nextweek2 = $endOfWeek;
                        ?>
                        <div class="row">
                            <div class="col-xs-2 text-left">

                                <button class="btn btn-primary ">
                                    <i aria-hidden="true" class="fa fa-chevron-left"></i>
                                </button>
                            </div>
                            <div class="col-xs-8 text-center">

                                <label class="text-filter"> Từ ngày
                                    <strong><?php echo $startOfWeek->format('d-m').'-'.$startOfWeek->format('Y'); ?>
                                    </strong> đến <strong><?php echo $endOfWeek->format('d-m').'-'.$endOfWeek->format('Y'); ?></strong>
                                </label>
                            </div>
                            <div class="col-xs-2 text-right">
                                <a href="" type="button" class="btn btn-primary ">
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
                                    <strong>Chú ý:</strong> Sinh viên thường xuyên theo dõi thời khoá biểu để sớm cập nhật thông tin mới nhất!
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
                                                    .STT
                                                    {
                                                        text-align: center;
                                                    }
                                                </style>
                                                <th title="MMH-TM">Mã Môn học - Tên môn</th>
                                                <th title="TTTH">Thông tin tiết học</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach($getallsubject as $subjectlist)
                                            <?php
                                                $dateparse = Carbon::parse($subjectlist->NgayDay);
                                            ?>

                                                @if($dateparse->between($startOfWeek, $endOfWeek))


                                            <?php
                                            //Chuyển từ string sang datetime để so sánh

                                                $dayOfWeek = $dateparse->format('l');
                                                $daysOfWeek = [
                                                                'Sunday' => 'Chủ nhật',
                                                                'Monday' => 'Thứ hai',
                                                                'Tuesday' => 'Thứ ba',
                                                                'Wednesday' => 'Thứ tư',
                                                                'Thursday' => 'Thứ năm',
                                                                'Friday' => 'Thứ sáu',
                                                                'Saturday' => 'Thứ bảy'
                                                            ];

                                            ?>

                                            <tr>
                                                <th colspan="4" class="bagroud">
                                                <?php
                                                    echo $daysOfWeek[$dayOfWeek].','.$dateparse->format('d-m').'-'.$dateparse->format('Y');
                                                ?>

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
                                                            <h4 style="text-decoration: underline;">Thông tin chi tiết</h4>
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
                                                    <strong class="title-red"> {{$subjectlist->MaTietHoc}}</strong>
                                                </td>
                                                <td class=" pt-4">
                                                <?php
                                                    $subjectname = DB::table('mon_hoc')
                                                    ->where('MaTTMH',$subjectlist->MaTTMH)
                                                    ->first();
                                                    if(session()->exists('teacherid'))
                                                    {
                                                        echo '<a href='.'/danh-sach-lop?'.'lop='.$subjectlist->MaTTMH.' >'.$subjectname->MaMH.' - '.$subjectname->TenMH.'</a>';
                                                    }
                                                    else{
                                                        echo '<p>'.$subjectname->MaMH.' - '.$subjectname->TenMH.'</p>';
                                                    }

                                                ?>
                                                </td>
                                                <td class=" pt-4">
                                                    <p>
                                                        {{-- <span> Phòng: <strong> E1-09.01 - </strong>
                                                        </span> --}}

                                                        <span> Lớp: {{$subjectlist->MaLop}} </span>

                                                    </p>
                                                </td>
                                                <td class=" pt-4">
                                                    @if(session()->exists('teacherid'))
                                                        <a href="/buoi?num={{$subjectlist->MaBuoi}}">ĐIỂM DANH</a>
                                                    @endif
                                                </td>
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
                                            <div _ngcontent-c8="" class="chasing-dots-spinner">
                                            <div _ngcontent-c8="" class="dot1 ng-star-inserted" style="background-color: red;"></div>
                                            <div _ngcontent-c8="" class="dot2 ng-star-inserted" style="background-color: red;"></div>
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
        </div>
    </div>
</body>
</html>
