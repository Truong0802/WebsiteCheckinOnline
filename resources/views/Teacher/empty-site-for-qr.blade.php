@extends('layouts.master-teacher')

@section('content')
<br>
<a type="button" href="/tro-ve" class="btn btn-primary" onclick="removeFilterData()">Trở về danh sách</a>
<div class="container text-center">
    <div class="row">
        <div class="col">
            <p class="mb-0">QR ĐIỂM DANH</p>
            <br><br>
            {!! $simple !!}
        </div>
    </div>

</div>
@stop
