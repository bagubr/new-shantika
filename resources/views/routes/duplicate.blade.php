@extends('layouts.main')
@section('title')
Route
@endsection
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Route {{$route->name}}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">Route</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>
<section class="content">
    @include('partials.error')

    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">{{$route->name}}</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body" style="display: block;">
                    <div class="form-group">
                        <label>Nama Rute</label>
                        <input type="text" class="form-control" name="name" placeholder="Masukkan Nama"
                            value="{{$route->name}}" disabled>
                    </div>
                    <div class="form-group">
                        <label>Area</label>
                        <input type="text" class="form-control"
                            value="{{$route->checkpoints[0]?->agency?->city?->area?->name ?? ''}}" disabled>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <div class="col-md-5">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">Armada Rute Form {{$route->name}}</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body" style="display: block;">
                    <form action="{{route('route.fleet.store')}}" method="POST">
                        @csrf
                        <input type="text" value="{{$route->id}}" hidden name="route_id">
                        <div class="form-group">
                            <label>Armada</label>
                            <select name="fleet_detail_id" class="select2 form-control" required>
                                <option value="">Pilih Armada</option>
                                @foreach ($fleets as $fleet)
                                <option value="{{$fleet->id}}">
                                    {{$fleet->fleet?->name}}/{{$fleet->fleet?->fleetclass?->name}}/{{$fleet->nickname}}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        @unlessrole('owner')

                        <div class="text-right">
                            <button class="btn btn-success" type="submit">Tambah Data</button>
                        </div>
                        @endunlessrole
                    </form>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
        <div class="col-md-7">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">Daftar Armada</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Armada</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($route_fleets as $route_fleet)
                            <tr>
                                <td>{{$route_fleet->fleet_detail?->fleet?->name}}/{{$route_fleet->fleet_detail?->fleet?->fleetclass?->name}}
                                    ({{$route_fleet->fleet_detail?->nickname}})
                                </td>
                                @if ($route_fleet->is_active == 1)
                                <td data-toggle="modal" data-target="#exampleModal{{$route_fleet->id}}"
                                    class="text-success text-bold">
                                    Aktif
                                </td>
                                @else
                                <td data-toggle="modal" data-target="#exampleModal{{$route_fleet->id}}"
                                    class="text-danger text-bold">
                                    Non Aktif
                                </td>
                                @endif
                                <td>
                                    @unlessrole('owner')

                                    <a data-toggle="modal" data-target="#exampleModal{{$route_fleet->id}}"
                                        class="btn btn-warning btn-xs">Edit</a>
                                    <form action="{{route('fleet_route.destroy',$route_fleet->id)}}" class="d-inline"
                                        method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-xs"
                                            onclick="return confirm('Apakah Anda Yakin  Menghapus Data Ini??')"
                                            type="submit">Delete</button>
                                    </form>
                                    @endunlessrole
                                </td>
                            </tr>
                            <div class="modal fade" id="exampleModal{{$route_fleet->id}}" tabindex="-1" role="dialog"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Ubah Data
                                                {{$route_fleet->name}}</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action="{{route('fleet_route.update_status',$route_fleet->id)}}"
                                            method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label>Status</label>
                                                    <select class="form-control input" name="is_active">
                                                        @foreach ($statuses as $s => $key)
                                                        <option value="{{$s}}" @if ($s==$route_fleet->is_active)
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
            </div>
        </div>
        <div class="col-md-5">
            <div class="card card-warning">
                <div class="card-header">
                    <h3 class="card-title">Line Form</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body" style="display: block;">
                    <form action="{{route('checkpoint.store')}}" method="POST">
                        @csrf
                        <input type="hidden" value="{{$route->id}}" name="route_id">
                        <div class="form-group">
                            <label>Agen</label>
                            <select required class="form-control select2" name="agency_id" style="width: 100%;">
                                <option value="">Pilih Agen</option>
                                @foreach ($agencies as $agency)
                                <option value="{{$agency->id}}">
                                    {{$agency->city->name}}/{{$agency->name}}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Urutan</label>
                            <input required type="number" min="1" class="form-control" name="order">
                        </div>
                        @unlessrole('owner')

                        <input type="submit" value="Submit" class="btn btn-success float-right">
                        @endunlessrole
                    </form>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
        <div class="col-md-7">
            <div class="card card-warning">
                <div class="card-header">
                    <h3 class="card-title">Line</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body" style="display: block;">
                    <small class="text-danger"><i class="fas fa-info-circle"></i> Pastikan Urutan Line
                        Sudah Benar</small>
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Urutan</th>
                                <th>Agen</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($checkpoints as $checkpoint)
                            <tr>
                                <td>{{$checkpoint->order}}</td>
                                <td><a
                                        href="{{route('agency.edit',$checkpoint->agency_id)}}">{{$checkpoint->agency?->city?->name}}/{{$checkpoint->agency?->name}}</a>
                                </td>
                                <td>
                                    @unlessrole('owner')

                                    <form action="{{route('checkpoint.destroy',$checkpoint->id)}}" class="d-inline"
                                        method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-xs"
                                            onclick="return confirm('Apakah Anda Yakin  Menghapus Data Ini??')"
                                            type="submit">Delete</button>
                                    </form>
                                    @endunlessrole
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
    </div>

</section>
@endsection
@push('script')
<script>
    $(function () {
        $('.select2').select2()
    })
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })
</script>
<script>
    $(function () {
      $("#example1").DataTable({
        "responsive": true, "lengthChange": false, "autoWidth": false,
      }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    });
</script>
@endpush