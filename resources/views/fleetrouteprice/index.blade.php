@extends('layouts.main')
@section('title')
Harga Rute Armada
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
                <h1 class="m-0">Harga Rute Armada</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">Harga Rute Armada</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Cari Harga</h3>
                        <div class="text-right">
                            <a href="{{route('fleet_route_prices.create')}}" class="btn btn-primary btn-sm">Tambah</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                Lorem ipsum dolor sit, amet consectetur adipisicing elit. Ipsa corporis iste, minus laborum iure ullam
                incidunt? Nobis, voluptate voluptatibus repellendus neque, natus est autem porro officiis minus,
                laboriosam aliquid incidunt.
            </div>
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