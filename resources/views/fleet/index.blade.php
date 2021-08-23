@extends('layouts.main')
@section('title')
Armada
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
                <h1 class="m-0">Armada</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">Armada</li>
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
                        <h3 class="card-title">Table Armada</h3>
                        <div class="text-right">
                            <a href="{{route('fleets.create')}}" class="btn btn-primary btn-sm">Tambah</a>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Rute</th>
                                    <th>Gambar</th>
                                    <th>Layout</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($fleets as $fleet)
                                <tr>
                                    <td>{{$fleet->name}}</td>
                                    <td>
                                        @foreach ($fleet->fleet_routes as $route)
                                        {{$route->route?->name}},
                                        @endforeach
                                    </td>
                                    <td>
                                        <a href="{{route('fleetclass.edit', $fleet->fleet_class_id)}}"
                                            target="_blank">{{$fleet->fleetclass?->name ?? '-'}}</a>
                                    </td>
                                    <td>
                                        @if ($fleet->image)
                                        <a href="{{$fleet->image}}" data-toggle="lightbox">
                                            <img src="{{$fleet->image}}" height="100px" alt="">
                                            @else
                                            Tidak Ada Gambar
                                            @endif
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{route('layouts.edit', $fleet->layout_id)}}"
                                            target="_blank">{{$fleet->layout?->name ?? '-'}}</a>
                                    </td>
                                    <td>
                                        <a href="{{route('fleets.edit',$fleet->id)}}"
                                            class="btn btn-warning btn-xs">Edit</a>
                                        <form action="{{route('fleets.destroy',$fleet->id)}}" class="d-inline"
                                            method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-xs"
                                                onclick="return confirm('Apakah Anda yakin akan menghapus data armada??')"
                                                type="submit">Delete</button>
                                        </form>
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
@endpush