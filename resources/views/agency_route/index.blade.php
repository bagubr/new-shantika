@extends('layouts.main')
@section('title')
Rute Agen
@endsection
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Rute Agen</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">Rute Agen</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <form action="{{route('agency_route.index')}}" method="get">
                        <div class="card-body">
                            <div class="form-row">
                                <div class="col">
                                    <div class="form-group">
                                        <label>Cari Area</label>
                                        <select name="area_id" class="form-control">
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
                                    <div class="form-group">
                                        <label for="">Nama Rute</label>
                                        <input type="text" name="name" id="" value="{{@$name}}" class="form-control">
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
                    <div class="card-header">
                        <h3 class="card-title">Table Rute Agen</h3>
                        <div class="text-right">
                            {{-- <a href="{{route('agency_route.create')}}" class="btn btn-primary btn-sm">Tambah</a> --}}
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Armada</th>
                                    <th>Rute</th>
                                    <th>Area</th>
                                    <th>Agen Permanen</th>
                                    <th>Agen Temporer</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($routes as $route)
                                <tr>
                                    <td>
                                        @foreach ($route->fleet_details??[] as $fleet_detail)
                                        <li>
                                            {{$fleet_detail->fleet?->name}}
                                            /{{$fleet_detail->fleet?->fleetclass?->name}}
                                            ({{$fleet_detail->nickname}})
                                        </li>
                                        @endforeach
                                    </td>
                                    <td>{{$route->name}}</td>
                                    <td>
                                        @if ($route->checkpoints->count() > 0)
                                        {{$route->checkpoints[0]?->agency?->city?->area?->name}}
                                        @else
                                        BELUM ADA
                                        @endif
                                    </td>
                                    <td>{{implode(', ',$route->agency_route_permanent->where('start_at', null)->where('end_at', null)->pluck('agency.name')->toArray())}}</td>
                                    <td>{!! implode(', ',$route->agency_route_permanent->where('start_at', '!=', null)->where('end_at', '!=', null)->transform(function ($item)
                                        {
                                            if(strtotime($item->start_at) <= strtotime(date('Y-m-d')) && strtotime($item->end_at) >= strtotime(date('Y-m-d'))){
                                                return $item->agency->name;
                                            }else{
                                                return '<del>'.$item->agency->name.'</del>';
                                            }
                                    })->toArray()) !!}</td>
                                    <td>
                                        <a href="{{route('agency_route_permanent.edit',$route->id)}}"class="btn btn-primary btn-xs" class="disabled-link">Tambah Agent Permanent</a>
                                        <a href="{{route('agency_route.edit',$route->id)}}"
                                            class="btn btn-outline-primary btn-xs">Tambah Agent Temporary</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="float-right mt-3">
                            {{$routes->links()}}
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