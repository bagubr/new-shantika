@extends('layouts.main')
@section('title')
Jadwal Rute {{$area->name}}
@endsection
@push('css')
<link rel="stylesheet" href="{{asset('plugins/fullcalendar/main.css')}}">
@endpush
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Jadwal Rute {{$area->name}}</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">Jadwal Rute {{$area->name}}</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Cari Jadwal Rute {{$area->name}}</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{route('fleet_route_prices.index')}}" method="get">
                            <div class="form-group">
                                <label>Armada</label>
                                <select name="fleet_route_id" class="form-control select2">
                                    <option value="">Pilih Armada</option>
                                    @foreach ($fleets as $fleet)
                                    @if (@$fleet_route_id == $fleet->id)
                                    <option value="{{$fleet->id}}" selected>
                                        {{$fleet->name}}/{{$fleet->fleetclass?->name}}
                                    </option>
                                    @else
                                    <option value="{{$fleet->id}}">
                                        {{$fleet->name}}/{{$fleet->fleetclass?->name}}
                                    </option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Tanggal</label>
                                <input type="date" class="form-control" name="date_search" value="{{@$date_search }}">
                            </div>
                            <div class="form-group">
                                <label>Cari Area</label>
                                <select name="area_id" class="form-control" disabled>
                                    <option value="">--PILIH AREA--</option>
                                    @foreach ($areas as $area)
                                    @if (isset($area_id) && $area_id == $area->id)
                                    <option value="{{$area->id}}" selected>{{$area->name}}</option>
                                    @else
                                    <option value="{{$area->id}}">{{$area->name}}</option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                            <input type="submit" value="Cari" class="btn btn-success float-right">
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Table</h3>
                        @unlessrole('owner')
                        <div class="text-right">
                            <a href="{{route('fleet_route_prices.create', ['area_id' => @$area_id])}}" class="btn btn-primary btn-sm">Tambah</a>
                        </div>
                        @endunlessrole
                    </div>
                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Area</th>
                                    <th>Rute</th>
                                    <th>Tanggal</th>
                                    <th>Perubahan Harga</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($fleet_route_prices as $fleet_route_price)
                                <tr>
                                    <td>
                                        {{$fleet_route_price->fleet_route?->fleet_detail?->fleet?->name}}/{{$fleet_route_price->fleet_route?->fleet_detail?->fleet?->fleetclass?->name}}
                                        @if ($fleet_route_price?->fleet_route->fleet_detail_without_trash)
                                            ({{$fleet_route_price?->fleet_route->fleet_detail?->nickname}}) 
                                            <a href="{{route('fleet_detail.edit',$fleet_route_price?->fleet_route->fleet_detail?->id)}}" class="float-right" target="_blank"><i class="fas fa-edit"></i></a>
                                        @else
                                            <strike>
                                                ({{$fleet_route_price?->fleet_route->fleet_detail?->nickname}}) 
                                            </strike>
                                            <a href="#" class="float-right"><i class="fas fa-trash" style="color: red"></i></a>
                                        @endif
                                    </td>
                                    <td>{{$fleet_route_price->fleet_route?->route?->checkpoints[0]?->agency?->city?->area?->name??'BELUM_ADA'}}</td>
                                    <td>{{$fleet_route_price->fleet_route?->route?->name}}</td>
                                    <td>{{$fleet_route_price->start_at}} - {{$fleet_route_price->end_at}}</td>
                                    <td>Rp. {{number_format($fleet_route_price->deviation_price,2)}}</td>
                                    <td>{{$fleet_route_price->status}}</td>
                                    <td>
                                        @unlessrole('owner')

                                        <a href="{{route('fleet_route_prices.edit',$fleet_route_price->id)}}"
                                            class="btn btn-warning btn-xs">Edit</a>
                                        <form action="{{route('fleet_route_prices.destroy',$fleet_route_price->id)}}"
                                            class="d-inline" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-xs"
                                                onclick="return confirm('Apakah Anda Yakin  Menghapus Data Ini??')"
                                                type="submit">Delete</button>
                                            @endunlessrole
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3>Kalender Harga</h3>
                    </div>
                    <div class="card-body">
                        <div id='calendar'></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('script')
<script src="{{asset('plugins/fullcalendar/main.js')}}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
            eventDidMount: function (info) {
                $(info.el).tooltip({
                    title: info.event.extendedProps.description,
                    container: 'body',
                    delay: {
                        "show": 50,
                        "hide": 50
                    }
                });
            },
            events: [
                @foreach($fleet_route_prices as $fleet_route_price) {
                    title: '{{$fleet_route_price->fleet_route?->fleet_detail?->fleet?->name}}/{{$fleet_route_price->fleet_route?->fleet_detail?->fleet?->fleetclass?->name}}({{$fleet_route_price->fleet_route?->fleet_detail?->nickname}})',
                    start: '{{$fleet_route_price->start_at}}',
                    description : 'Rp. {{number_format($fleet_route_price->true_deviation_price)}} | {{$fleet_route_price->status}}',
                    end: '{{$fleet_route_price->end_at}}',
                    color: '{{$fleet_route_price->color}}'
                },
                @endforeach
            ]
        });

        calendar.render();
    });
</script>
<script>
    $(function () {
      $("#example1").DataTable({
        "responsive": true, "lengthChange": false, "autoWidth": false,
      }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    });
</script>
@endpush