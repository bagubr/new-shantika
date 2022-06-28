@extends('layouts.main')
@section('title')
Route
@endsection
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Route</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">Route</li>
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
                    <form action="{{route('routes.index')}}" method="get">
                        <div class="card-body">
                            <div class="form-row">
                                <div class="col">
                                    <div class="form-group">
                                        <label>Cari Area</label>
                                        <select name="area_id" class="form-control" {{(Auth::user()->area_id)?'disabled':''}}>
                                            <option value="">--PILIH AREA--</option>
                                            @foreach ($areas as $area)
                                            @if ($area_id == $area->id)
                                            <option value="{{$area->id}}" selected>{{$area->name}}</option>
                                            @else
                                            <option value="{{$area->id}}">{{$area->name}}</option>
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
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Table Route</h3>
                        @unlessrole('owner')
                        <div class="text-right">
                        @foreach (\App\Models\Area::get() as $area)
                            <a href="{{route('routes.create', ['area_id' => $area->id])}}" class="btn btn-primary btn-sm">Tambah Rute {{$area->name}}</a>
                        @endforeach
                        </div>
                        @endunlessrole
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Tujuan</th>
                                    <th>Armada</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($routes as $route)
                                <tr>
                                    <td>{{$route->name}}</td>
                                    <td>
                                        @if ($route->checkpoints->count() > 0)
                                        {{$route->checkpoints[0]?->agency?->city?->area?->name}}
                                        @endif
                                    </td>
                                    <td>
                                        @foreach ($route->fleet_routes as $fleet_route )
                                        <li>
                                            {{$fleet_route->fleet_detail?->fleet?->name}}/{{$fleet_route->fleet_detail?->fleet?->fleetclass?->name}}
                                                ({{$fleet_route->fleet_detail?->nickname}})
                                        </li>
                                        @endforeach
                                    </td>
                                    <td>
                                        <a class="btn btn-outline-primary btn-xs" href="{{route('routes.edit',$route->id)}}">Edit</a>
                                        <a class="btn btn-primary btn-xs" href="{{route('routes.show',$route->id)}}">Detail</a>
                                        <a class="btn btn-success btn-xs" href="{{route('routes.duplicate',[$route->id, 'duplicate' => 1])}}">Duplicate</a>
                                        @unlessrole('owner')
                                        <a class="btn btn-danger btn-xs button-delete"
                                            data-id="{{$route->id}}">Delete</a>
                                        @endunlessrole
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
        "responsive": true, "lengthChange": false, "autoWidth": false,
      }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    });
</script>
<script>
    $(document).on('click', '.button-delete', function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        Swal.fire({
            title: 'Apakah Anda Yakin Menghapus Data Ini ?',
            text: "Pastikan Data Yang Akan Anda Hapus Benar",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya',
            cancelButtonText: 'Batal!',
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'routes/' + id,
                    type: "POST",
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: id,
                        _method: 'DELETE'
                    },
                    success: function (data) {
                        Swal.fire(
                            'Berhasil',
                            'Data Anda Berhasil Dihapus',
                            'success')
                        location.reload();
                    },
                })
            } else if (
                result.dismiss === Swal.DismissReason.cancel
            ) {
                Swal.fire(
                    'Dibatalkan',
                    'Data anda tidak terhapus',
                    'error'
                )
            };
        });
    });
            
</script>
@endpush