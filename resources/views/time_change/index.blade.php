@extends('layouts.main')
@section('title')
Waktu
@endsection
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Time Change Temporary</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">Time Change</li>
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
                        <h3 class="card-title">Table Change Time Temporary</h3>
                        {{-- @unlessrole('owner') --}}
                        <div class="text-right">
                            <a href="{{route('time_change_route.create')}}" class="btn btn-primary btn-sm">Tambah</a>
                        </div>
                        {{-- @endunlessrole --}}
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Armada Rute</th>
                                    <th>Waktu</th>
                                    <th>Date</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($time_change_routes as $time_change_route)
                                <tr>
                                    <td>{{$time_change_route->fleet_route}}</td>
                                    <td>{{$time_change_route->time_classification->name}}</td>
                                    <td>{{$time_change_route->date}}</td>
                                    <td>
                                        {{-- <a href="{{route('time_change_route.edit',$time_change_route->id)}}"
                                            class="btn btn-warning btn-xs">Edit</a> --}}
                                        <form action="{{route('time_change_route.destroy',$time_change_route->id)}}"
                                            class="d-inline" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-xs"
                                                onclick="return confirm('Apakah Anda Yakin Menghapus Data Ini (Hal ini tidak akan mengembalikan Proses yang sudah terjadi)?? ')"
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