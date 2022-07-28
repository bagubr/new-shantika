@extends('layouts.main')
@section('title')
Rute Setting
@endsection
@push('css')
<link rel="stylesheet" href="{{asset('css/lightbox.min.css')}}">
@endpush
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Rute Setting</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">Rute Setting</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
<div class="content" id="app">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <form action="{{route('fleet_route.index')}}" method="get">
                        <div class="card-body">
                            <div class="form-row">
                                <div class="col">
                                    <div class="form-group">
                                        <label>Cari Area</label>
                                        <select name="area_id" class="form-control" {{(Auth::user()->area_id)?'disabled':''}}>
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
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label>Cari Armada</label>
                                        <select name="fleet_id" class="form-control select2">
                                            <option value="">--PILIH ARMADA--</option>
                                            @foreach ($fleets as $fleet)
                                            @if ($fleet_id == $fleet->id)
                                            <option value="{{$fleet->id}}" selected>
                                                {{$fleet->name}}/{{$fleet->fleetclass?->name}}</option>
                                            @else
                                            <option value="{{$fleet->id}}">
                                                {{$fleet->name}}/{{$fleet->fleetclass?->name}}</option>
                                            @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="text-right m-2">
                            <button class="btn btn-success" type="submit">Cari</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Armada</th>
                                    <th>Unit Armada</th>
                                    <th>Tujuan</th>
                                    <th>Rute</th>
                                    <th>Rute Temporary</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($fleet_routes as $fleet_route)
                                <tr>
                                    <td>{{$fleet_route->fleet_detail?->fleet?->name}}</td>
                                    <td>
                                        <li>
                                            {{$fleet_route->fleet_detail?->nickname}} ({{$fleet_route->fleet_detail?->plate_number}})
                                        </li>
                                    </td>
                                    <td>
                                        {{@$fleet_route->route?->checkpoints[0]?->agency?->city?->area?->name}}
                                    </td>
                                    <td>
                                        <a href="{{route('routes.show',$fleet_route->route_id)}}">
                                            {{$fleet_route->route?->name}}
                                        </a>
                                    </td>
                                    <td>{!! implode(', ',$fleet_route->route_setting->transform(function ($item)
                                        {
                                            if(strtotime($item->start_at) <= strtotime(date('Y-m-d')) && strtotime($item->end_at) >= strtotime(date('Y-m-d'))){
                                                return $item->agency->name;
                                            }else{
                                                return '<del>'.$item->agency->name.'</del>';
                                            }
                                    })->toArray()) !!}</td>
                                    <td>
                                        @unlessrole('owner')
                                        <a href="{{route('route_setting.edit',$fleet_route->id)}}"
                                            class="btn btn-primary btn-xs">Tambah Rute Temporary</a>
                                        @endunlessrole
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="float-right">
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
    </div>
</div>
@endsection
@push('script')
<script>
    if ($('.select2').length > 0) {
        $('.select2').select2();
    };
</script>
<script>
    $(function () {
      $("#example1").DataTable({
        "responsive": true, "lengthChange": false, "autoWidth": false
      }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    });
</script>
@endpush