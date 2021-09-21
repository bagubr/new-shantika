@extends('layouts.main')
@section('title')
Harga Rute Armada
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
                <h1 class="m-0">Harga Rute Armada</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">Harga Rute Armada</li>
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
                        <h3 class="card-title">Cari Harga Rute Armada</h3>
                    </div>
                    <div class="card-body">
                        <form action="" method="get">
                            <div class="form-group">
                                <label>Armada</label>
                                <select name="fleet_route_id" class="form-control select2">
                                    <option value="">Pilih Armada</option>
                                    @foreach ($fleet_details as $fleet_detail)
                                    <option value="{{$fleet_detail->fleet_route_id}}">
                                        {{$fleet_detail->fleet?->name}}/{{$fleet_detail->fleet?->fleetclass?->name}}
                                        ({{$fleet_detail->nickname}})
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Tanggal</label>
                                <input type="date" class="form-control" name="date">
                            </div>
                            <input type="submit" value="Submit" class="btn btn-success float-right">
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Table</h3>
                        <div class="text-right">
                            <a href="{{route('fleet_route_prices.create')}}" class="btn btn-primary btn-sm">Tambah</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Tanggal</th>
                                    <th>Harga</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($fleet_route_prices as $fleet_route_price)
                                <tr>
                                    <td>
                                        {{$fleet_route_price->fleet_route?->fleet_detail?->fleet?->name}}/{{$fleet_route_price->fleet_route?->fleet_detail?->fleet?->fleetclass?->name}}
                                        ({{$fleet_route_price->fleet_route?->fleet_detail?->nickname}})
                                    </td>
                                    <td>{{$fleet_route_price->start_at}} - {{$fleet_route_price->end_at}}</td>
                                    <td>Rp. {{number_format($fleet_route_price->price,2)}}</td>
                                    <td><a href="{{route('fleet_route_prices.edit',$fleet_route_price->id)}}"
                                            class="btn btn-warning btn-xs">Edit</a>
                                        <a class="btn btn-danger btn-xs button-delete"
                                            data-id="{{$fleet_route_price->id}}">Delete</a>
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
                    description : 'Rp. {{number_format($fleet_route_price->price)}} | {{$fleet_route_price->note}}',
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