@extends('layouts.main')
@section('title')
Promo
@endsection
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Promo</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">Promo</li>
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
                        <h3 class="card-title">Table Promo</h3>
                        <div class="text-right">
                            <a href="{{route('promo.create')}}" class="btn btn-primary btn-sm">Tambah</a>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Code</th>
                                    <th>User</th>
                                    <th>Start - End</th>
                                    <th>Quota</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($promos as $promo)
                                <tr>
                                    <td>{{$promo->name}}</td>
                                    <td>{{$promo->code}}</td>
                                    @if($promo->is_public)
                                    <td>Global</td>
                                    @else
                                    <td>{{ $promo->user->name }}</td>
                                    @endif
                                    @if($promo->is_scheduless)
                                    <td>Tidak terjadwal</td>
                                    @else
                                    <td>{{ date('Y-m-d' ,strtotime($promo->start_at)) }} - {{ date('Y-m-d' ,strtotime($promo->end_at)) }}</td>
                                    @endif
                                    <td>
                                        {{ count($promo->promo_histories) }}/
                                        @if($promo->is_quotaless)
                                        Tak Terhingga
                                        @else
                                        {{ $promo->quota }}
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{route('promo.edit',$promo->id)}}"
                                            class="btn btn-warning btn-xs">Edit</a>
                                        <form action="{{route('promo.destroy',$promo->id)}}" class="d-inline"
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