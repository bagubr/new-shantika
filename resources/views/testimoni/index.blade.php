@extends('layouts.main')
@section('title')
Testimoni Pengguna
@endsection
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Testimoni Pengguna</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">Testimoni Pengguna</li>
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
                        <h3 class="card-title">Table Testimoni Pengguna</h3>
                        <div class="text-right">
                            <a href="{{route('testimoni.create')}}" class="btn btn-primary btn-sm">Tambah</a>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Judul Testimoni</th>
                                    <th>Nama</th>
                                    <th>Image</th>
                                    <th>Review</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($testimonials as $testimoni)
                                <tr>
                                    <td>{{$testimoni->title}}</td>
                                    <td>{{$testimoni->user?->name}}</td>
                                    <td>
                                        <a href="{{$testimoni->image}}" data-toggle="lightbox">
                                            <img src="{{$testimoni->image}}" height="100px"
                                                alt="{{$testimoni->user?->name}}">
                                        </a>
                                    </td>
                                    <td>{!!$testimoni->review!!}</td>
                                    <td>
                                        <a href="{{route('testimoni.edit',$testimoni->id)}}"
                                            class="btn btn-warning btn-xs">Edit</a>
                                        <form action="{{route('testimoni.destroy',$testimoni->id)}}" class="d-inline"
                                            method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-xs"
                                                onclick="return confirm('Apakah Anda Yakin  Menghapus Data Ini??')"
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