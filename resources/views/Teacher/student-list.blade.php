@extends('layouts.master-teacher')

@section('content')
    <?php
    if (session()->exists('row16')) {
        session()->forget('row16');
    }
    ?>
    <div id="ribbon">
        <ol class="breadcrumb">
            <li class="ng-star-inserted">
                <a>Danh sách sinh viên</a>
            </li>
        </ol>~
    </div>
    @if (session('errorClassList1'))
        <div class="alert alert-danger text-center">{{ session('errorClassList1') }}</div>
    @endif
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
            @if (session()->exists('teacherid'))
                <form action='/tim-kiem-sinh-vien' method='get'>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="student-name">Họ tên:</label>
                                <input type="text" class="form-control" id="student-name" name="studentname">
                                @error('studentname')
                                    <div class="alert alert-danger">{{ $errors->first('studentname') }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="student-id">MSSV:</label>
                                <input type="text" class="form-control" id="student-id" name="mssv">
                                @error('mssv')
                                    <div class="alert alert-danger">{{ $errors->first('mssv') }}</div>
                                @enderror
                            </div>
                        </div>
                        {{-- <div class="col-md-4">
                                <div class="form-group">
                                    <label for="student-serial">STT:</label>
                                    <input type="text" class="form-control" id="student-serial">
                                </div>
                            </div> --}}
                    </div>
                    {{-- <br>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="class-name">Tên lớp:</label>
                                    <input type="text" class="form-control" id="class-name" name="classname">
                                </div>
                            </div>
                        </div> --}}
                    <br>
                    <button type="submit" class="btn btn-primary" onclick="filterData()">Tìm kiếm</button>
                    <a type="button" href="/xoa-tim-kiem-sv" class="btn btn-danger" onclick="removeFilterData()">Xóa tất cả
                        bộ lọc</a>
                    @csrf
                </form>
            @endif
        </div>
        @foreach ($getinfoclass as $key)
            <?php
            if ($key != null) {
                $findMaTT = DB::table('danh_sach_sinh_vien')
                ->where('MaHK',$key->MaHK)
                ->where('MaTTMH', $key->MaTTMH)->first();
                $listid = substr($findMaTT->MaDanhSach, 0, -1);
                $phanloailop = substr($key->MaDanhSach, 3, 1);
                $classname = DB::table('mon_hoc')
                    ->where('MaTTMH', $key->MaTTMH)
                    ->distinct()
                    ->first();
            }

            ?>
        @endforeach


        @if (isset($listid) && isset($classname))
            @if (session()->exists('teacherid'))
                @if ($phanloailop == '1' || $phanloailop == '2')
                    @if (DB::table('diem_danh')->where('MaDanhSach', 'like', $listid . '%')->where('MaBuoi', 9)->exists())
                        <br>
                        <div class="container">
                            <strong>Hãy lựa chọn cách tính điểm chuyên cần:</strong>
                            <form action="/option-row-14" method="post">
                                <br>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input type="radio" name="divideall" value="{{ $listid }}" id="divideall">
                                        <label>Chia đều 3 điểm cho 9 buổi</label>
                                        <button type="button" id="openModal1">Chi tiết</button>
                                        <div id="myModal1" class="modal1">
                                            <div class="modal-content">
                                                <span class="close1">&times;</span>
                                                <h2>Hướng dẫn</h2>
                                                <p>Chia 3 điểm chuyên cần đều cho 9 buổi ( 0.33 điểm cho từng buổi )</p>
                                                <p>VD: Điểm danh được 9 buổi = 2.97 ( Làm tròn thành 3 ), Điểm danh được 7
                                                    buổi = 2.31 ( Làm tròn thành 2 )</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input type="radio" name="divide3" value="{{ $listid }}" id="divide3">
                                        <label>Chia lấy 3 buổi 1 điểm</label>
                                        <button type="button" id="openModal2">Chi tiết</button>
                                        <div id="myModal2" class="modal2">
                                            <div class="modal-content">
                                                <span class="close2">&times;</span>
                                                <h2>Hướng dẫn</h2>
                                                <p>Chia 9 buổi ra thành 3 buổi nhỏ</p>
                                                <p>Cách tính: 3 buổi nhỏ = 1 điểm, 2 buổi nhỏ = 0.5 điểm, 1 buổi nhỏ = 0
                                                    điểm</p>
                                                <p>VD: Điểm danh được 3 3 3 ( Buổi nhỏ ) = 3 điểm, Điểm danh 3 2 1 ( Buổi
                                                    nhỏ ) = 1.5 điểm </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <button type="submit" class="btn btn-primary">Chọn</button>
                                @csrf
                            </form>
                    @endif
                @elseif($phanloailop == '3')
                    @if (DB::table('diem_danh')->where('MaDanhSach', 'like', $listid . '%')->where('MaBuoi', 6)->exists())
                        <div class="container">
                            <strong>Hãy lựa chọn cách tính điểm chuyên cần:</strong>
                            <form action="/option-row-14" method="post">
                                <br>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input type="radio" name="divideall" value="{{ $listid }}" id="divideall">
                                        <label>Chia đều 3 điểm cho 6 buổi</label>
                                        <button type="button" id="openModal1">Chi tiết</button>
                                        <div id="myModal1" class="modal1">
                                            <div class="modal-content">
                                                <span class="close1">&times;</span>
                                                <h2>Hướng dẫn</h2>
                                                <p>Chia 3 điểm chuyên cần đều cho 6 buổi ( 0.33 điểm cho từng buổi )</p>
                                                <p>VD: Điểm danh được 9 buổi = 2.97 ( Làm tròn thành 3 ), Điểm danh được 7
                                                    buổi = 2.31 ( Làm tròn thành 2 )</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input type="radio" name="divide3" value="{{ $listid }}" id="divide3">
                                        <label>Chia lấy 2 buổi 1 điểm</label>
                                        <button type="button" id="openModal2">Chi tiết</button>
                                        <div id="myModal2" class="modal2">
                                            <div class="modal-content">
                                                <span class="close2">&times;</span>
                                                <h2>Hướng dẫn</h2>
                                                <p>Chia 6 buổi ra thành 3 buổi nhỏ</p>
                                                <p>Cách tính: 2 buổi nhỏ = 1 điểm, 1 buổi nhỏ = 0.5 điểm</p>
                                                <p>VD: Điểm danh được 2 2 2 ( Buổi nhỏ ) = 3 điểm, Điểm danh 2 2 1 ( Buổi
                                                    nhỏ ) = 2.5 điểm </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <button type="submit" class="btn btn-primary">Chọn</button>
                                @csrf
                            </form>
                    @endif
                @endif
            @endif
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

                btn1.addEventListener("click", function() {
                    modal1.style.display = "block";
                });

                btn2.addEventListener("click", function() {
                    modal2.style.display = "block";
                });


                closeBtn1.addEventListener("click", function() {
                    modal1.style.display = "none";
                });

                closeBtn2.addEventListener("click", function() {
                    modal2.style.display = "none";
                });

                window.addEventListener("click", function(event) {
                    if (event.target == modal1 && event.target == modal2) {
                        modal1.style.display = "none";
                        modal2.style.display = "none";
                    }
                });
            </script>
    </div>

    {{-- <br><br> --}}
    <div class="col-md-12 detail bcs-detail">
        <style>
            .detail {
                grid-template-columns: 15fr
            }
        </style>

        <div class="class-list">
            @if (session('error-row16'))
                <div class="alert alert-danger text-center">{{ session('error-row16') }}</div>
            @endif
            <span><strong>HỌC PHẦN:</strong>
                {{-- Lập trình ứng dụng với Java  --}}
                <?php
                echo $classname->TenMH;
                ?>
                <strong> <?php echo $classname->MaMH; ?> </strong> - (Nhóm <?php echo $classname->NhomMH; ?>) - Số tín chỉ: <?php echo $classname->STC; ?></span>
            <br>
            <div class="table">
                <table id="student-table">
                    <thead>
                        <tr>
                            <td>Ban cán sự</td>
                            <td>STT</td>
                            <td>Mã SV</td>
                            <td>Họ</td>
                            <td>Tên</td>
                            <td>Tên lớp</td>

                            @if (session()->exists('teacherid'))
                                <?php
                                $teacherid = session()->get('teacherid');
                                // $check = DB::table('diem_danh')->where('MaDanhSach','like','%'.$listid.'%')->where('MaBuoi',1)->exists();
                                ?>

                                {{-- Buoi1 --}}
                                @if (DB::table('diem_danh')->where('MaDanhSach', 'like', $listid . '%')->where('MaBuoi', 1)->exists())
                                    <td>1</td>
                                @else
                                    <?php
                                    $data = [
                                        'lop' => $classname->MaTTMH,
                                        'HK' => session()->get('HKid'),
                                        'buoi' => 1,
                                    ];
                                    $encryptedData = encrypt($data);
                                    ?>
                                    {{-- <td class="buoi-hoc"><a href="/diem-danh?lop={{$classname->MaTTMH}}&buoi=1">1</a></td> --}}
                                    <td class="buoi-hoc"><a
                                            href="{{ route('diemdanh', ['data' => $encryptedData]) }}">1</a></td>
                                @endif
                                {{-- Buoi2 --}}
                                @if (DB::table('diem_danh')->where('MaDanhSach', 'like', $listid . '%')->where('MaBuoi', 2)->exists())
                                    <td>2</td>
                                @else
                                    <?php
                                    $data = [
                                        'lop' => $classname->MaTTMH,
                                        'HK' => session()->get('HKid'),
                                        'buoi' => 2,
                                    ];
                                    $encryptedData = encrypt($data);
                                    ?>
                                    <td class="buoi-hoc"><a
                                            href="{{ route('diemdanh', ['data' => $encryptedData]) }}">2</a></td>
                                @endif
                                {{-- Buoi3 --}}
                                @if (DB::table('diem_danh')->where('MaDanhSach', 'like', $listid . '%')->where('MaBuoi', 3)->exists())
                                    <td>3</td>
                                @else
                                    <?php
                                    $data = [
                                        'lop' => $classname->MaTTMH,
                                        'HK' => session()->get('HKid'),
                                        'buoi' => 3,
                                    ];
                                    $encryptedData = encrypt($data);
                                    ?>
                                    <td class="buoi-hoc"><a
                                            href="{{ route('diemdanh', ['data' => $encryptedData]) }}">3</a></td>
                                @endif
                                {{-- Buoi4 --}}
                                @if (DB::table('diem_danh')->where('MaDanhSach', 'like', $listid . '%')->where('MaBuoi', 4)->exists())
                                    <td>4</td>
                                @else
                                    <?php
                                    $data = [
                                        'lop' => $classname->MaTTMH,
                                        'HK' => session()->get('HKid'),
                                        'buoi' => 4,
                                    ];
                                    $encryptedData = encrypt($data);
                                    ?>
                                    <td class="buoi-hoc"><a
                                            href="{{ route('diemdanh', ['data' => $encryptedData]) }}">4</a></td>
                                @endif
                                {{-- Buoi5 --}}
                                @if (DB::table('diem_danh')->where('MaDanhSach', 'like', $listid . '%')->where('MaBuoi', 5)->exists())
                                    <td>5</td>
                                @else
                                    <?php
                                    $data = [
                                        'lop' => $classname->MaTTMH,
                                        'HK' => session()->get('HKid'),
                                        'buoi' => 5,
                                    ];
                                    $encryptedData = encrypt($data);
                                    ?>
                                    <td class="buoi-hoc"><a
                                            href="{{ route('diemdanh', ['data' => $encryptedData]) }}">5</a></td>
                                @endif
                                {{-- Buoi6 --}}
                                @if (DB::table('diem_danh')->where('MaDanhSach', 'like', $listid . '%')->where('MaBuoi', 6)->exists())
                                    <td>6</td>
                                @else
                                    <?php
                                    $data = [
                                        'lop' => $classname->MaTTMH,
                                        'HK' => session()->get('HKid'),
                                        'buoi' => 6,
                                    ];
                                    $encryptedData = encrypt($data);
                                    ?>
                                    <td class="buoi-hoc"><a
                                            href="{{ route('diemdanh', ['data' => $encryptedData]) }}">6</a></td>
                                @endif
                                {{-- Buoi7 --}}
                                @if ($phanloailop == '1' || $phanloailop == '2')
                                    @if (DB::table('diem_danh')->where('MaDanhSach', 'like', $listid. '%')->where('MaBuoi', 7)->exists())
                                        <td>7</td>
                                    @else
                                        <?php
                                        $data = [
                                            'lop' => $classname->MaTTMH,
                                            'HK' => session()->get('HKid'),
                                            'buoi' => 7,
                                        ];
                                        $encryptedData = encrypt($data);
                                        ?>
                                        <td class="buoi-hoc"><a
                                                href="{{ route('diemdanh', ['data' => $encryptedData]) }}">7</a></td>
                                    @endif
                                    {{-- Buoi8 --}}
                                    @if (DB::table('diem_danh')->where('MaDanhSach', 'like', $listid . '%')->where('MaBuoi', 8)->exists())
                                        <td>8</td>
                                    @else
                                        <?php
                                        $data = [
                                            'lop' => $classname->MaTTMH,
                                            'HK' => session()->get('HKid'),
                                            'buoi' => 8,
                                        ];
                                        $encryptedData = encrypt($data);
                                        ?>
                                        <td class="buoi-hoc"><a
                                                href="{{ route('diemdanh', ['data' => $encryptedData]) }}">8</a></td>
                                    @endif
                                    {{-- Buoi9 --}}
                                    @if (DB::table('diem_danh')->where('MaDanhSach', 'like', $listid . '%')->where('MaBuoi', 9)->exists())
                                        <td>9</td>
                                    @else
                                        <?php
                                        $data = [
                                            'lop' => $classname->MaTTMH,
                                            'HK' => session()->get('HKid'),
                                            'buoi' => 9,
                                        ];
                                        $encryptedData = encrypt($data);
                                        ?>
                                        <td class="buoi-hoc"><a
                                                href="{{ route('diemdanh', ['data' => $encryptedData]) }}">9</a></td>
                                    @endif
                                @elseif($phanloailop == '3')
                                    <td>7</td>
                                    <td>8</td>
                                    <td>9</td>
                                @endif
                            @elseif(session()->exists('studentid'))
                                <td>1</td>
                                <td>2</td>
                                <td>3</td>
                                <td>4</td>
                                <td>5</td>
                                <td>6</td>
                                <td>7</td>
                                <td>8</td>
                                <td>9</td>
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
                        $stt = 0;
                        ?>
                        @if (session()->exists('teacherid'))
                            {{-- Nothing --}}
                            <?php
                            //Kiểm tra xem lớp có tồn tại ban cán sự hay chưa
                            $CheckLeaderOfClassIsAvailable = DB::table('danh_sach_sinh_vien')
                                ->where('MaTTMH', session()->get('danh-sach-sinh-vien-lop'))
                                ->where('MaHK', session()->get('HKid'))
                                ->where('BanCanSuLop', 1)
                                ->first();

                            ?>
                            @if ($CheckLeaderOfClassIsAvailable != null)
                                <form action="/nhap-diem" method="post">
                                    @if ($phanloailop == '1' || $phanloailop == '2')
                                        @if (DB::table('diem_danh')->where('MaDanhSach', 'like', $listid . '%')->where('MaBuoi', 9)->exists())
                                            <button type="submit" class="btn btn-success">Xác nhận điểm</button>
                                        @endif
                                    @elseif($phanloailop == '3')
                                        @if (DB::table('diem_danh')->where('MaDanhSach', 'like', $listid . '%')->where('MaBuoi', 6)->exists())
                                            <button type="submit" class="btn btn-success">Xác nhận điểm</button>
                                        @endif
                                    @endif
                                @else
                                    <form action="/chon-ban-can-su" method="post">
                                        <button type="submit" class="btn btn-success">Xác nhận ban cán sự</button>
                            @endif
                        @endif
                        {{-- Xuất thông tin danh sách --}}
                        @foreach ($getinfoclass as $allstudentlist)
                            <?php
                            $studentname = DB::table('sinh_vien')
                                ->where('MSSV', $allstudentlist->MSSV)
                                ->first();
                            //Kiểm tra xem sinh viên đó có phải Ban cán sự hay không
                            $CheckLeaderOfClass = DB::table('danh_sach_sinh_vien')
                                ->where('MaTTMH', session()->get('danh-sach-sinh-vien-lop'))
                                ->where('MaHK', session()->get('HKid'))
                                ->where('MSSV', $allstudentlist->MSSV)
                                ->whereNotNull('BanCanSuLop')
                                ->first();

                            //Kiểm tra xem lớp có tồn tại ban cán sự hay không
                            $CheckLeaderOfClassIsAvailable = DB::table('danh_sach_sinh_vien')
                                ->where('MaTTMH', session()->get('danh-sach-sinh-vien-lop'))
                                ->where('MaHK', session()->get('HKid'))
                                ->where('BanCanSuLop', 1)
                                ->first();
                            ?>
                            <tr>
                                <?php
                                //Tạo biến đếm số lượng sinh viên
                                $stt += 1;
                                ?>
                                <td>
                                    {{-- Nếu lớp có tồn tại ban cán sự --}}
                                    @if ($CheckLeaderOfClassIsAvailable != null)
                                        @if ($CheckLeaderOfClass != null)
                                            x
                                        @else
                                            {{-- Sinh viên thường --}}
                                        @endif
                                    @else
                                        {{-- Nếu lớp chưa tồn tại ban cán sự, set điều kiện chỉ cho phép giảng viên click checkbox --}}
                                        @if (session()->exists('teacherid'))
                                            <input type="radio" id="LTnum" name="LTnum"
                                                value="{{ $allstudentlist->MaDanhSach }}">
                                        @else
                                            {{-- Nothing for Student choose --}}
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    {{ $stt }}
                                </td>


                                <td>{{ $studentname->MSSV }}</td>
                                <?php
                                    $spacePosition = strrpos($studentname->HoTenSV,' ');
                                    $Ten = substr($studentname->HoTenSV, $spacePosition + 1);
                                    $Ho = Str::before($studentname->HoTenSV,$Ten);

                                ?>
                                <td>
                                    {{$Ho}}
                                 </td>
                                <td>
                                   {{$Ten}}
                                </td>
                                <td><?php echo $studentname->MaLop; ?></td>

                                @if (DB::table('diem_danh')->where('MaDanhSach', $allstudentlist->MaDanhSach)->where('MaBuoi', 1)->exists())
                                    <td>x</td>
                                @else
                                    <td></td>
                                @endif
                                @if (DB::table('diem_danh')->where('MaDanhSach', $allstudentlist->MaDanhSach)->where('MaBuoi', 2)->exists())
                                    <td>x</td>
                                @else
                                    <td></td>
                                @endif
                                @if (DB::table('diem_danh')->where('MaDanhSach', $allstudentlist->MaDanhSach)->where('MaBuoi', 3)->exists())
                                    <td>x</td>
                                @else
                                    <td></td>
                                @endif
                                @if (DB::table('diem_danh')->where('MaDanhSach', $allstudentlist->MaDanhSach)->where('MaBuoi', 4)->exists())
                                    <td>x</td>
                                @else
                                    <td></td>
                                @endif
                                @if (DB::table('diem_danh')->where('MaDanhSach', $allstudentlist->MaDanhSach)->where('MaBuoi', 5)->exists())
                                    <td>x</td>
                                @else
                                    <td></td>
                                @endif
                                @if (DB::table('diem_danh')->where('MaDanhSach', $allstudentlist->MaDanhSach)->where('MaBuoi', 6)->exists())
                                    <td>x</td>
                                @else
                                    <td></td>
                                @endif
                                @if ($phanloailop == '1' || $phanloailop == '2')
                                    @if (DB::table('diem_danh')->where('MaDanhSach', $allstudentlist->MaDanhSach)->where('MaBuoi', 7)->exists())
                                        <td>x</td>
                                    @else
                                        <td></td>
                                    @endif
                                    @if (DB::table('diem_danh')->where('MaDanhSach', $allstudentlist->MaDanhSach)->where('MaBuoi', 8)->exists())
                                        <td>x</td>
                                    @else
                                        <td></td>
                                    @endif
                                    @if (DB::table('diem_danh')->where('MaDanhSach', $allstudentlist->MaDanhSach)->where('MaBuoi', 9)->exists())
                                        <td>x</td>
                                    @else
                                        <td></td>
                                    @endif
                                @elseif($phanloailop == '3')
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                @endif

                                <td class="score-input"><input type="text"></td>
                                <td class="score-input"><input type="text"></td>
                                <td class="score-input"><input type="text"></td>
                                <td class="score-input"><input type="text"></td>
                                @if ($phanloailop == '1' || $phanloailop == '2')
                                    @if (DB::table('diem_danh')->where('MaDanhSach', 'like', $listid . '%')->where('MaBuoi', 9)->exists())
                                        <!-- Điểm 14 -->
                                        @if ($allstudentlist->Diem14 != null)
                                            <td>{{ $allstudentlist->Diem14 }}</td>
                                        @else
                                            <td></td>
                                        @endif
                                        <td></td>
                                        <!-- Điểm 16 -->
                                        @if (session()->exists('teacherid'))
                                            @if ($allstudentlist->Diem16 != null)
                                                <!-- Cho chỉnh điểm -->
                                                {{-- @if ($allstudentlist->TimeForChangeRow16 != null)
                                                    <td class="score-input"><input type="text" id="row16"
                                                            name="row16[]"
                                                            value="{{ $allstudentlist->Diem16 }}">{{ session()->push('row16', $allstudentlist->MaDanhSach . '/' . $allstudentlist->MSSV) }}
                                                    </td>
                                                @else --}}
                                                    <td>{{ $allstudentlist->Diem16 }}</td>
                                                {{-- @endif --}}
                                            @else
                                                {{-- Yêu cầu phải đi học hơn 70% số buổi --}}
                                                @if (DB::table('diem_danh')->where('MaDanhSach', $allstudentlist->MaDanhSach)->distinct()->count('MaBuoi') >= 7)
                                                    {{-- Nhập điểm --}}
                                                    <td class="score-input" id="score-input"><input type="text" id="row16"
                                                            name="row16[]">{{ session()->push('row16', $allstudentlist->MaDanhSach . '/' . $allstudentlist->MSSV) }}
                                                    </td>
                                                @else
                                                    <td></td>
                                                @endif
                                            @endif
                                        @elseif(session()->exists('studentid'))
                                            @if ($allstudentlist->Diem16 != null)
                                                <td>{{ $allstudentlist->Diem16 }}</td>
                                            @else
                                                <td></td>
                                            @endif
                                        @endif

                                        <?php
                                        $diemqt = DB::table('ket_qua')
                                            ->where('MaKQSV', $allstudentlist->MaKQSV)
                                            ->first();
                                        ?>
                                        @if ($diemqt->DiemQT != null)
                                            <td>
                                                <?php echo $diemqt->DiemQT; ?>
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
                                @endif
                                @if ($phanloailop == '3')
                                    @if (DB::table('diem_danh')->where('MaDanhSach', 'like', $listid . '%')->where('MaBuoi', 6)->exists())
                                        <!-- Điểm 14 -->
                                        @if ($allstudentlist->Diem14 != null)
                                            <td>{{ $allstudentlist->Diem14 }}</td>
                                        @else
                                            <td></td>
                                        @endif
                                        <td></td>
                                        <!-- Điểm 16 -->
                                        @if (session()->exists('teacherid'))
                                            @if ($allstudentlist->Diem16 != null)
                                                <!-- Cho chỉnh sửa điểm -->
                                                {{-- @if ($allstudentlist->TimeForChangeRow16 != null)
                                                    <td class="score-input"><input type="text" id="row16"
                                                            name="row16[]"
                                                            value="{{ $allstudentlist->Diem16 }}">{{ session()->push('row16', $allstudentlist->MaDanhSach . '/' . $allstudentlist->MSSV) }}
                                                    </td>
                                                @else --}}
                                                    <td>{{ $allstudentlist->Diem16 }}</td>
                                                {{-- @endif --}}
                                            @else
                                                {{-- Yêu cầu phải đi học hơn 70% số buổi --}}
                                                @if (DB::table('diem_danh')->where('MaDanhSach', $allstudentlist->MaDanhSach)->distinct()->count('MaBuoi') >= 5)
                                                    {{-- Nhập điểm --}}
                                                    <td class="score-input"><input type="text" id="row16"
                                                            name="row16[]">{{ session()->push('row16', $allstudentlist->MaDanhSach . '/' . $allstudentlist->MSSV) }}
                                                    </td>
                                                @else
                                                    <td></td>
                                                @endif
                                            @endif
                                        @elseif(session()->exists('studentid'))
                                            @if ($allstudentlist->Diem16 != null)
                                                <td>{{ $allstudentlist->Diem16 }}</td>
                                            @else
                                                <td></td>
                                            @endif
                                        @endif
                                        <?php
                                        $diemqt = DB::table('ket_qua')
                                            ->where('MaKQSV', $allstudentlist->MaKQSV)
                                            ->first();
                                        ?>
                                        @if ($diemqt->DiemQT != null)
                                            <td>
                                                <?php echo $diemqt->DiemQT; ?>
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
    <?php
    $isInClass = DB::table('danh_sach_sinh_vien')
        ->where('MaTTMH', session()->get('danh-sach-sinh-vien-lop'))
        ->where('MaHK', session()->get('HKid'))
        ->where('MSSV', session()->get('studentid'))
        ->first();
    ?>
    @if ($isInClass != null || session()->has('teacherid'))
        <div class="col-md-12 detail comment-detail">
            <div class="class-list">
                <?php
                if (session()->exists('teacherid')) {
                    $getInfoFromObject = DB::table('giang_vien')
                        ->where('MSGV', session()->get('teacherid'))
                        ->first();
                    $imgAva = $getInfoFromObject->HinhDaiDienGV;
                } elseif (session()->exists('studentid')) {
                    $getInfoFromObject = DB::table('sinh_vien')
                        ->where('MSSV', session()->get('studentid'))
                        ->first();
                    $imgAva = $getInfoFromObject->HinhDaiDienSV;
                }

                if ($imgAva == null) {
                    $imgAvatar = 'ori-ava.png';
                } else {
                    $imgAvatar = $imgAva;
                }
                ?>
                <?php
                $getComments = DB::table('comments')
                    ->where('MaTTMH', session()->get('danh-sach-sinh-vien-lop'))
                    ->leftJoin('sinh_vien', 'comments.MSSV', '=', 'sinh_vien.MSSV')
                    ->leftJoin('giang_vien', 'comments.MSGV', '=', 'giang_vien.MSGV')
                    ->where('MaTTMH', session()->get('danh-sach-sinh-vien-lop'))
                    ->where('MaHK', session()->get('HKid'))
                    ->orderBy('NgayComment', 'ASC')
                    ->get();

                ?>
                @foreach ($getComments as $comment)
                    <div class="comment-container" id="comment-container">
                        <?php
                        if ($comment->MSSV != null) {
                            $img = $comment->HinhDaiDienSV;
                            $HoTen = $comment->HoTenSV;
                        } elseif ($comment->MSGV != null) {
                            $img = $comment->HinhDaiDienGV;
                            $HoTen = $comment->HoTenGV;
                        }
                        // dd($img);
                        ?>
                        <img src="{{ asset('img/Avatar/' . $img) }}" alt="Avatar" class="online avatar">
                        <div class="comments-list">
                            <span id="userName"><strong>{{ $HoTen }}</strong></span> &ensp;
                            <span id="comments-content" style="font-size: 18px">{{ $comment->NoiDung }}</span>
                        </div>
                    </div>
                @endforeach
                <hr class="solid">
                <form action="/comment" method="POST" class="form-comment-container">
                    <div class="comment-container" id="comment-container">
                        <img src="{{ asset('img/Avatar/' . $imgAvatar) }}" alt="Avatar" class="online avatar">
                        <textarea type="text" name="inputcomments" placeholder="Thêm nhận xét vào lớp học..." id="comment-input"
                            class="comment-input" onfocus="expandContainer(true)" onblur="expandContainer(false)"></textarea>
                        <button alt=""><img id="send-button" addComment()" src="{{ asset('/img/send.png') }}"
                                alt=""></button>
                    </div>
                    @error('inputcomments')
                        <div class="alert alert-danger">{{ $errors->first('inputcomments') }}</div>
                    @enderror
                    @csrf
                </form>
            </div>
        </div>
    @endif


    @if (session()->exists('teacherid'))
        <button style="margin-left: 20px;" id="export-excel" class="btn btn-primary" onclick="exportToExcel()">Xuất
            Excel</button>
    @endif
    <script src="https://unpkg.com/xlsx/dist/xlsx.full.min.js"></script>
    <script>
        function exportToExcel()
        {
            var table = document.getElementById('student-table');
            var workbook = XLSX.utils.table_to_book(table);
            var today = new Date();
            var fileName = 'data_' + today.getFullYear() + '_' + (today.getMonth() + 1) + '_' + today.getDate() + '.xlsx';

            // Chuẩn bị dữ liệu để xuất file Excel
            var wopts =
            {
                bookType: 'xlsx',
                bookSST: false,
                type: 'binary'
            };
            var wbout = XLSX.write(workbook, wopts);

            function s2ab(s)
            {
                var buf = new ArrayBuffer(s.length);
                var view = new Uint8Array(buf);
                for (var i = 0; i < s.length; i++) view[i] = s.charCodeAt(i) & 0xff;
                return buf;
            }

            // Tạo đối tượng blob từ dữ liệu Excel
            var blob = new Blob([s2ab(wbout)],
            {
                type: 'application/octet-stream'
            });

            // Tạo URL tạm thời cho blob
            var url = URL.createObjectURL(blob);

            // Tạo một thẻ a ẩn để kích hoạt việc tải xuống
            var a = document.createElement('a');
            a.href = url;
            a.download = fileName;

            // Simulate click để tải xuống file
            a.click();

            // Xóa URL tạm thời
            setTimeout(function()
            {
                URL.revokeObjectURL(url);
            }, 1000);
        }
    </script>
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
    @if (session()->exists('teacherid'))
        @if ($CheckLeaderOfClassIsAvailable == null)
            <div class="popup-container" id="popup">
                <div class="popup-content" style="max-width: 500px;">
                    <h2>Thông báo</h2>
                    <p>Giảng viên cần xác nhận ban cán sự trước khi nhập điểm</p>
                    <button class="btn-primary" onclick = "closePopup()" id="btn-close-2">Đóng</button>
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
        @endif
    @endif
    </div>
@stop
