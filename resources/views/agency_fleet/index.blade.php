@extends('layouts.main')
@section('title')
Armada Agen
@endsection
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
<<<<<<< HEAD
                <h1 class="m-0">Armada Agen</h1>
=======
                <h1 class="m-0">Armada Agen {{App\Models\Area::find(request()->area_id)->name}}</h1>
>>>>>>> rilisv1
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">Armada Agen</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
<<<<<<< HEAD
                        <h3 class="card-title">Table Armada Agen</h3>
=======
                        <h3 class="card-title">Table Armada Agen {{App\Models\Area::find(request()->area_id)->name}}</h3>
>>>>>>> rilisv1
                        <div class="text-right">
                            {{-- <a href="{{route('agency_fleet.create')}}" class="btn btn-primary btn-sm">Tambah</a> --}}
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Armada</th>
                                    <th>Unit Armada</th>
<<<<<<< HEAD
                                    <th>Area</th>
=======
                                    <th>Rute dan Area</th>
>>>>>>> rilisv1
                                    <th>Agen Permanen</th>
                                    <th>Agen Temporer</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($fleets as $fleet)
                                <tr>
                                    <td>{{$fleet->name}}</td>
                                    <td>
                                        @foreach ($fleet->fleet_detail as $detail)
                                        <li>
                                            {{$detail->nickname}} ({{$detail->plate_number}})
                                        </li>
                                        @endforeach
                                    </td>
                                    <td>
<<<<<<< HEAD
                                        {{ ($fleet->route_fleets)?:'BELUM ADA' }}
=======
                                        @foreach ($fleet->fleet_detail as $detail)
                                            @foreach ($detail->fleet_route??[] as $fleet_routes)
                                            <li>
                                                {{$fleet_routes->route?->name??''}}
                                                (
                                                    {{$fleet_routes->route?->checkpoints[0]?->agency?->city?->area?->name??'BELUM ADA'}}
                                                    )
                                                </li>
                                            @endforeach
                                        @endforeach
>>>>>>> rilisv1
                                    </td>
                                    <td>{{implode(', ',$fleet->agency_fleet_permanent->pluck('agency.name')->toArray())}}</td>
                                    <td>{!! implode(', ',$fleet->agency_fleet->transform(function ($item)
                                        {
                                            if(strtotime($item->start_at) <= strtotime(date('Y-m-d')) && strtotime($item->end_at) >= strtotime(date('Y-m-d'))){
                                                return $item->agency->name;
                                            }else{
                                                return '<del>'.$item->agency->name.'</del>';
                                            }
                                    })->toArray()) !!}</td>
                                    <td>
<<<<<<< HEAD
                                        <a href="
                                        {{-- {{route('agency_fleet_permanent.edit',$fleet->id)}} --}}
                                        "
                                            class="btn btn-primary btn-xs" class="disabled-link">Tambah Agent Permanent (Belum Selesai)</a>
                                        <a href="{{route('agency_fleet.edit',$fleet->id)}}"
=======
                                        <a href="{{route('agency_fleet_permanent.edit',[$fleet->id, 'area_id' => request()->area_id])}}"class="btn btn-primary btn-xs" class="disabled-link">Tambah Agent Permanent</a>
                                        <a href="{{route('agency_fleet.edit',[$fleet->id, 'area_id' => request()->area_id])}}"
>>>>>>> rilisv1
                                            class="btn btn-outline-primary btn-xs">Tambah Agent Temporary</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
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
    $(function () {
      $("#example1").DataTable({
<<<<<<< HEAD
        "responsive": true, "lengthChange": false, "autoWidth": false,
=======
        "responsive": true, "lengthChange": false, "autoWidth": false
>>>>>>> rilisv1
      }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    });
</script>
@endpush