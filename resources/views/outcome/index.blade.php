@extends('layouts.main')
@section('title')
Pengeluaran
@endsection
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Pengeluaran</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">Pengeluaran</li>
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
                    <div class="card-header">
                        <h3 class="card-title">Table Pengeluaran</h3>
                        <div class="text-right">
                            <a href="{{route('outcome_type.create')}}" class="btn btn-outline-warning btn-sm">Tambah Tipe Pengeluaran</a>
                            <a href="{{route('outcome.create')}}" class="btn btn-primary btn-sm">Tambah</a>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Tanggal Laporan</th>
                                    <th>Rute</th>
                                    <th>Tipe Pengeluaran</th>
                                    <th>Armada</th>
                                    <th>Kelas Armada</th>
                                    <th>Total Pendapatan</th>
                                    <th>Total Pengeluaran</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($outcomes as $outcome)
                                <tr>
                                    <td>{{$outcome->reported_at}}</td>
                                    <td>{{$outcome?->route?->name??"Non Rute"}}</td>
                                    <td>{{$outcome->outcome_type?->name??'-'}}</td>
                                    <td>{{$outcome?->route?->fleet?->name??'Non Rute'}}</td>
                                    <td>{{$outcome?->route?->fleet?->fleetclass?->name??'Non Rute'}}</td>
                                    <td>Rp. {{number_format($outcome->sum_total_pendapatan,2)}}</td>
                                    <td>Rp. {{number_format($outcome->sum_pengeluaran,2)}}</td>
                                    <td>
                                        <a href="{{route('outcome.show',$outcome->id)}}"
                                        class="btn btn-info btn-xs">Show</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{$outcomes->links("pagination::bootstrap-4")}}
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </div>
    </div>
</div>
@endsection
@push('script')
<script>
    $(function () {
      $("#example1").DataTable({
        "responsive": true, 
        "lengthChange": false, 
        "autoWidth": false,        
        "paging":   false,
        "ordering": false,
        "info":     false,
        "searching" : false,
      }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    });
</script>
@endpush