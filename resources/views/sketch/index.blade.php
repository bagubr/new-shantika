@extends('layouts.main')
@section('title')
Sketch
@endsection
@push('css')
<style>
    body {
        transform: scale(1);
        transform-origin: 0 0;
    }
</style>
@endpush
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Sketch</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">Sketch</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
<div class="content">
    <div class="container-fluid">
    <form action="{{route('sketch.index')}}" method="GET">
        <div class="card">
            <div class="row p-2">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Pilih Area</label>
                        <select name="area_id" class="form-control" id="">
                            <option value="">--PILIH--</option>
                            @foreach(\App\Models\Area::get() as $area)
                            <option value="{{$area->id}}">{{$area->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">Tanggal</label>
                            <input type="date" name="date" id="" class="form-control" value="{{(@$date)?$date:date('Y-m-d')}}">
                    </div>
                </div>
            </div>
            <div class="text-right m-2">
                <button class="btn btn-success" type="submit">Cari</button>
            </div>
        </div>
    </form>
        <div class="row">
            @if(!empty($data['sketchs']))
            <div class="col-md-12">
                <div class="card p-5">
                    <center>
                        <h3>
                            Data tidak di temukan
                        </h3> 
                    </center>
                </div>
            </div>
            @endif
            @foreach($data['sketchs'] as $route_id => $sketch)
            <div class="col-3 m-0,5">
                <div class="card h-100 p-2" style="border-radius:10px" data-toggle="modal" data-target="#modal-default">
                    <div class="card-body">
                        <div class="row m-1">
                            <div class="col-md-3">
                                <i class="fas fa-bus" style="font-size: 3em; color: Mediumslateblue;"></i>
                            </div>
                            <div class="col-md-9">
                                <b>{{\App\Models\Route::find($route_id)->fleet->name}}</b>
                                <small class="float-right text-blue">Rp. {{number_format(\App\Models\Route::find($route_id)->price,2)}}</small>
                                <br>
                                <small style="font-size: 15px;">{{\App\Models\Route::find($route_id)->name}}</small>
                            </div>
                        </div>
                        <div class="row m-1">
                            <div class="col-md-3">
                                <i class="fas fa-money-bill-wave" style="font-size: 2em; color: Dodgerblue;"></i>
                            </div>
                            <div class="col-md-9">
                                @php
                                    $for_owner = 0;
                                @endphp
                                @foreach($sketch as $order)
                                    @php
                                    $for_owner += $order->distribution->for_owner;
                                    @endphp
                                @endforeach
                                <td>
                                    <b>
                                        Rp. {{number_format($for_owner, 2)}}
                                    </b>
                                </td>
                            </div>
                            <div class="col-md-5 float-right">
                                @php
                                    $chair = 0;
                                @endphp
                                @foreach($sketch as $order)
                                    @php
                                    $chair += count($order->order_detail);
                                    @endphp
                                @endforeach
                                <td>{{$chair}} / {{\App\Models\Route::find($route_id)->fleet->layout->total_chairs}}</td>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
            @endforeach
        </div>
    </div>
</div>
@endsection
@push('script')
<script>
    $('body').addClass('sidebar-collapse');
</script>
@endpush
<div class="modal fade" id="modal-default">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Default Modal</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <p>One fine body&hellip;</p>
        </div>
        <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->