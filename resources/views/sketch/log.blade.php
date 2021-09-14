@extends('layouts.main')
@section('title')
Sketch Log
@endsection
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Sketch Log</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">Sketch Log</li>
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
                        <h3 class="card-title">Table Sketch Log</h3>
                        <div class="text-right">
                            <a href="{{route('role.create')}}" class="btn btn-primary btn-sm">Tambah</a>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Filter</h3>
                            </div>
                            <div class="card-body">
                                <form action="" method="GET">
                                    @csrf
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="">Nama Admin</label>
                                                <select class="form-control" name="admin_id" id="">
                                                    <option value="" selected>--PILIH ADMIN--</option>
                                                    @foreach ($admins as $admin)
                                                        <option value="{{$admin->id}}">{{$admin->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="">Agen Keberangkatan</label>
                                                <select class="form-control" name="agency_id" id="">
                                                    <option value="" selected>--PILIH AGEN--</option>
                                                    @foreach ($agencies as $agency)
                                                        <option value="{{$agency->id}}">{{$agency->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="">Dari Armada</label>
                                                <select class="form-control" name="from_fleet_id" id="">
                                                    <option value="" selected>--PILIH ARMADA--</option>
                                                    @foreach($fleets as $fleet)
                                                        <option value="{{$fleet->id}}">{{$fleet->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="">Ke Armada</label>
                                                <select class="form-control" name="to_fleet_id" id="">
                                                    <option value="" selected>--PILIH ARMADA--</option>
                                                    @foreach($fleets as $fleet)
                                                        <option value="{{$fleet->id}}">{{$fleet->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="">Dari Tanggal</label>
                                                <input type="datetime-local" name="from_date" class="form-control" id="">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="">Ke Tanggal</label>
                                                <input type="datetime-local" name="to_date" class="form-control" id="">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-success">Cari</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Admin</th>
                                    <th>Waktu Perubahan</th>
                                    <th>Nama Pembeli</th>
                                    <th>Agen Keberangkatan</th>
                                    <th>Dari Armada ke Armada</th>
                                    <th>Dari Kursi ke Kursi</th>
                                    <th>Dari Tanggal ke Tanggal</th>
                                    <th>Dari Shift ke Shift</th>
                                    <th>Jenis</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($logs as $log)
                                <tr>
                                    <td>{{$log->admin?->name}}</td>
                                    <td>{{date('d M Y H:i:s', strtotime($log->created_at))}}</td>
                                    <td>{{$log->order->order_detail[0]->name}}</td>
                                    <td>{{$log->order->agency->name}}</td>
                                    <td>
                                        {{$log->from_fleet_route->fleet_detail->fleet->name}} ->
                                        {{$log->to_fleet_route->fleet_detail->fleet->name}}
                                    </td>
                                    <td>
                                        {{$log->from_layout_chair->name}} -> 
                                        {{$log->to_layout_chair->name}}
                                    </td>
                                    <td>{{date('d M Y H:i:s', strtotime($log->from_date))}} ->
                                        {{date('d M Y H:i:s', strtotime($log->to_date))}}
                                    </td>
                                    <td>
                                        {{$log->from_time_classification->name}} -> 
                                        {{$log->to_time_classification->name}}
                                    </td>
                                    <td>
                                        {{$log->type == 'CHANGE' ? 'Perubahan Kursi' : 'Pembatalan'}}
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
        "responsive": true, "lengthChange": false, "autoWidth": false, "order": [[1, "desc"]]
      }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(1)');
    });
</script>
@endpush