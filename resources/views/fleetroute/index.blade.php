@extends('layouts.main')
@section('title')
Armada Rute
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
                <h1 class="m-0">Armada Rute</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">Armada Rute</li>
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
                        <h3 class="card-title">Table Armada Rute</h3>
                    </div>
                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Armada</th>
                                    <th>Rute</th>
                                    <th>Harga</th>
                                    <th>Kelas Armada</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($fleet_routes as $fleet_route)
                                <tr>
                                    <td>{{$fleet_route->fleet?->name}}</td>
                                    <td>{{$fleet_route->route?->name}}</td>
                                    <td>Rp. {{number_format($fleet_route->price,2)}}</td>
                                    <td>{{$fleet_route->fleet?->fleetclass?->name}}</td>
                                    @if ($fleet_route->is_active == 1)
                                    <td data-toggle="modal" data-target="#exampleModal{{$fleet_route->id}}"
                                        class="text-success text-bold">
                                        Aktif
                                    </td>
                                    @else
                                    <td data-toggle="modal" data-target="#exampleModal{{$fleet_route->id}}"
                                        class="text-danger text-bold">
                                        Non Aktif
                                    </td>
                                    @endif
                                    <td>
                                        <a href="{{route('fleet_route.show',$fleet_route->id)}}"
                                            class="btn btn-primary btn-xs">Detail</a>
                                        <a href="{{route('fleet_route.edit',$fleet_route->id)}}"
                                            class="btn btn-warning btn-xs">Edit</a>
                                        <form action="{{route('fleet_route.destroy',$fleet_route->id)}}"
                                            class="d-inline" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-xs"
                                                onclick="return confirm('Apakah Anda yakin akan menghapus data armada Rute??')"
                                                type="submit">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                <div class="modal fade" id="exampleModal{{$fleet_route->id}}" tabindex="-1"
                                    role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Ubah Status
                                                    {{$fleet_route->name}}</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="{{route('fleet_route.update_status',$fleet_route->id)}}"
                                                method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <select class="form-control input" name="is_active">
                                                            @foreach ($statuses as $s => $key)
                                                            <option value="{{$s}}" @if ($s==$fleet_route->is_active)
                                                                selected
                                                                @endif>{{$key}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
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
        "responsive": true, "lengthChange": false, "autoWidth": false,
      }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    });
</script>
@endpush