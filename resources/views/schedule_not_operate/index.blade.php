@extends('layouts.main')
@section('title')
Jadwal
@endsection
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Jadwal</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">Jadwal</li>
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
                        <h3 class="card-title">Table Jadwal</h3>
                        <div class="text-right">
                            <a href="{{route('schedule_not_operate.create')}}" class="btn btn-primary btn-sm">Tambah</a>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body" style="display: block;">
                        <form action="{{route('schedule_not_operate.search')}}" method="GET">
                            <div class="form-group">
                                <label>Cari Rute</label>
                                <select name="search" id="" class="form-control select2">
                                    <option value="">Cari Rute</option>
                                    @foreach ($routes as $route)
                                    <option value="{{$route->id}}">{{$route->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="text-right">
                                <button class="btn btn-success" type="submit">Submit</button>
                            </div>
                        </form>
                        @isset($search)
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Catatan</th>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($search as $schedule_not_operate)
                                <tr>
                                    <td>{{$schedule_not_operate->route->name}}</td>
                                    <td>{{$schedule_not_operate->note}}</td>
                                    <td>{{$schedule_not_operate->schedule_at}}</td>
                                    <td>
                                        <form
                                            action="{{route('schedule_not_operate.destroy',$schedule_not_operate->id)}}"
                                            class="d-inline" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-xs"
                                                onclick="return confirm('Are you sure?')" type="submit">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @endisset
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