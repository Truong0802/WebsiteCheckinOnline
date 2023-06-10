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
                    <a>Quản lý sinh viên</a>
                </li>
            </ol>
        </div>
        <div class="mt-4" id="content">
            <div class="  mx-4">
                <div class="row mb-3">
                    <div class="col-md-6 pr-0">
                        <div>
                            <h1 class="page-title txt-color-blueDark">
                                <i class="fa fa-lg fa-fw fa-user"></i> Sinh viên
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
                                <label for="student-name">Tên sinh viên:</label>
                                <input type="text" class="form-control" id="student-name" name="studentname">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="student-name">Lớp:</label>
                                <input type="text" class="form-control" id="student-name" name="studentname">
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="btn-container">
                        <button type="submit" class="btn btn-primary" onclick="filterData()">Thêm sinh viên</button>
                    </div>
                    
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
                <div class="col-md-12 detail">
                    <table>
                        <thead>
                            <tr>
                                <td>STT</td>
                                <td>MSSV</td>
                                <td>Tên SV</td>
                                <td>Lớp</td>
                                <td></td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>2011060957</td>
                                <td>Hồ Phú Tài</td>
                                <td>20DTHA2</td>
                                <td><a href=""><i class="fa-regular fa-eye"></a></i></td>
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