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
                                            @if ($area_id == $area->id)
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
                    <div class="card-header">
                        <h3 class="card-title">Table Armada Rute</h3>
                        <a href="{{route('fleet_route.create')}}" class="btn btn-primary btn-sm float-right">Tambah Armada Rute</a>
                    </div>
                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Armada</th>
                                    <th>Unit Armada</th>
                                    <th>Tujuan</th>
                                    <th>Rute</th>
                                    <th>Status</th>
                                    <th>Jumlah Kursi Diblock</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($fleet_routes as $fleet_route)
                                <tr>
                                    <td>{{$fleet_route->fleet_detail?->fleet?->name}}</td>
                                    <td>
                                        @foreach ($fleet_route->fleet_detail?->fleet?->fleet_detail??[] as $detail)
                                        <li>
                                            {{$detail->nickname}} ({{$detail->plate_number}})
                                        </li>
                                        @endforeach
                                    </td>
                                    <td>
                                        {{@$fleet_route->route?->checkpoints[0]?->agency?->city?->area?->name}}
                                    </td>
                                    <td>
                                        <a href="{{route('routes.show',$fleet_route->route_id)}}">
                                            {{$fleet_route->route?->name}}
                                        </a>
                                    </td>
                                    @if ($fleet_route->is_active == 1)
                                    <td data-toggle="modal" data-target="#exampleModal{{$fleet_route->id}}"
                                        class="text-success text-bold pointer">
                                        Aktif
                                    </td>
                                    @else
                                    <td data-toggle="modal" data-target="#exampleModal{{$fleet_route->id}}"
                                        class="text-danger text-bold pointer">
                                        Non Aktif
                                    </td>
                                    @endif
                                    <td>
                                        {{$fleet_route->blocked_chairs_count}}
                                    </td>
                                    <td>
                                        @unlessrole('owner')

                                        <a href="{{route('fleet_route.blocked_chair', $fleet_route->id)}}"
                                            target="_blank" class="btn btn-primary btn-xs">Kursi Diblock</a>
                                        @endunlessrole
                                        <a href="{{route('fleet_route.show',$fleet_route->id)}}"
                                            class="btn btn-primary btn-xs">Detail</a>
                                        @unlessrole('owner')

                                        <a class="btn btn-danger btn-xs button-delete"
                                            data-id="{{$fleet_route->id}}">Delete</a>
                                        @endunlessrole
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
                    url: 'fleet_route/' + id,
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