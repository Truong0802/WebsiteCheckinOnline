@extends('layouts.master-admin')

@section('content')
<div id="ribbon">
            <span class="ribbon-button-alignment">
                <span class="btn btn-ribbon" id="refresh" placement="bottom">
                    <i class="fa fa-refresh"></i>
                </span>
            </span>
            <ol class="breadcrumb">
                <li class="ng-star-inserted">
                    <a>Thêm sinh viên</a>
                </li>
            </ol>
        </div>
        <div class="mt-4" id="content">
            <div class="  mx-4">
                <div class="row mb-3">
                    <div class="col-md-6 pr-0">
                        <div>
                            <h1 class="page-title txt-color-blueDark">
                                <i class="fa-fw fa fa-graduation-cap"></i> Thêm sinh viên
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
                                <label for="student-id">MSSV:</label>
                                <input type="text" class="form-control" id="student-id" name="mssv">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="student-name">Họ tên:</label>
                                <input type="text" class="form-control" id="student-name" name="studentname">
                            </div>
                        </div>
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
                    <button type="submit" class="btn btn-primary" onclick="filterData()">Thêm sinh viên</button>
                    @csrf
                </form>
                <div class="col-md-12 detail">
                <style>
                    .detail
                    {
                        grid-template-columns: 15fr
                    }
                </style>
                <div class="class-list">
                    <span><strong>HỌC PHẦN:</strong> Lập trình ứng dụng với Java <strong> (CMP3025) </strong> - Nhóm 2 - Số tín chỉ: 3</span>
                    <br><br>
                    <div class="table">
                        <table>
                            <thead>
                                <tr>
                                    <td>STT</td>
                                    <td>Mã SV</td>
                                    <td>Họ tên</td>
                                    <td>Tên lớp</td>
                                    <td></td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>20111061224</td>
                                    <td>Trần Nguyên Trường</td>
                                    <td>20DTHA2</td>
                                    <td>Chỉnh sửa</td>
                                    <td>Xóa sinh viên</td>
                                </tr>
                            </tbody>
                            <tbody>
                                <tr>
                                    <td>2</td>
                                    <td>2011060957</td>
                                    <td>Hồ Phú Tài</td>
                                    <td>20DTHA2</td>
                                    <td>Chỉnh sửa</td>
                                    <td>Xóa sinh viên</td>
                                </tr>
                            </tbody>
                            <tbody>
                                <tr>
                                    <td>3</td>
                                    <td>2011062236</td>
                                    <td>Nguyễn Phương Minh</td>
                                    <td>20DTHA2</td>
                                    <td>Chỉnh sửa</td>
                                    <td>Xóa sinh viên</td>
                                </tr>
                            </tbody>
                            <tbody>
                                <tr>
                                    <td>4</td>
                                    <td>20111061224</td>
                                    <td>Trần Nguyên Trường</td>
                                    <td>20DTHA2</td>
                                    <td>Chỉnh sửa</td>
                                    <td>Xóa sinh viên</td>
                                </tr>
                            </tbody>
                            <tbody>
                                <tr>
                                    <td>5</td>
                                    <td>2011060957</td>
                                    <td>Hồ Phú Tài</td>
                                    <td>20DTHA2</td>
                                    <td>Chỉnh sửa</td>
                                    <td>Xóa sinh viên</td>
                        
                                </tr>
                            </tbody>
                            <tbody>
                                <tr>
                                    <td>6</td>
                                    <td>2011062236</td>
                                    <td>Nguyễn Phương Minh</td>
                                    <td>20DTHA2</td>
                                    <td>Chỉnh sửa</td>
                                    <td>Xóa sinh viên</td>
                                </tr>
                            </tbody>
                            <tbody>
                                <tr>
                                    <td>7</td>
                                    <td>20111061224</td>
                                    <td>Trần Nguyên Trường</td>
                                    <td>20DTHA2</td>
                                    <td>Chỉnh sửa</td>
                                    <td>Xóa sinh viên</td>
                                </tr>
                            </tbody>
                            <tbody>
                                <tr>
                                    <td>8</td>
                                    <td>2011060957</td>
                                    <td>Hồ Phú Tài</td>
                                    <td>20DTHA2</td>
                                    <td>Chỉnh sửa</td>
                                    <td>Xóa sinh viên</td>
                        
                                </tr>
                            </tbody>
                            <tbody>
                                <tr>
                                    <td>9</td>
                                    <td>2011062236</td>
                                    <td>Nguyễn Phương Minh</td>
                                    <td>20DTHA2</td>
                                    <td>Chỉnh sửa</td>
                                    <td>Xóa sinh viên</td>
                                </tr>
                            </tbody>
                            <tbody>
                                <tr>
                                    <td>10</td>
                                    <td>20111061224</td>
                                    <td>Trần Nguyên Trường</td>
                                    <td>20DTHA2</td>
                                    <td>Chỉnh sửa</td>
                                    <td>Xóa sinh viên</td>
                                </tr>
                            </tbody>
                            <tbody>
                                <tr>
                                    <td>11</td>
                                    <td>2011060957</td>
                                    <td>Hồ Phú Tài</td>
                                    <td>20DTHA2</td>
                                    <td>Chỉnh sửa</td>
                                    <td>Xóa sinh viên</td>
                        
                                </tr>
                            </tbody>
                            <tbody>
                                <tr>
                                    <td>12</td>
                                    <td>2011062236</td>
                                    <td>Nguyễn Phương Minh</td>
                                    <td>20DTHA2</td>
                                    <td>Chỉnh sửa</td>
                                    <td>Xóa sinh viên</td>
                                </tr>
                            </tbody>
                            <tbody>
                                <tr>
                                    <td>13</td>
                                    <td>20111061224</td>
                                    <td>Trần Nguyên Trường</td>
                                    <td>20DTHA2</td>
                                    <td>Chỉnh sửa</td>
                                    <td>Xóa sinh viên</td>
                                </tr>
                            </tbody>
                            <tbody>
                                <tr>
                                    <td>14</td>
                                    <td>2011060957</td>
                                    <td>Hồ Phú Tài</td>
                                    <td>20DTHA2</td>
                                    <td>Chỉnh sửa</td>
                                    <td>Xóa sinh viên</td>
                        
                                </tr>
                            </tbody>
                            <tbody>
                                <tr>
                                    <td>15</td>
                                    <td>2011062236</td>
                                    <td>Nguyễn Phương Minh</td>
                                    <td>20DTHA2</td>
                                    <td>Chỉnh sửa</td>
                                    <td>Xóa sinh viên</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            </div>
        </div>
</div>

@stop   