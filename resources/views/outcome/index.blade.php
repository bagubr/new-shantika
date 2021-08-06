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
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Customer</th>
                                    <th>Kode Order</th>
                                    <th>Rute</th>
                                    <th>Armada</th>
                                    <th>Total Harga</th>
                                    <th>Status</th>
                                    <th>Tanggal Pengeluaran</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $order)
                                <tr>
                                    <td>{{$order->user->name ?? $order->order_detail[0]->name}}</td>
                                    <td>{{$order->code_order}}</td>
                                    <td>
                                        <a href="{{route('routes.show',$order->route?->id)}}" target="_blank">
                                            {{$order->route?->name}}
                                        </a>
                                    </td>
                                    <td>
                                        {{$order->route?->fleet?->name}}/{{$order->route?->fleet?->fleetclass?->name}}
                                    </td>
                                    <td>
                                        Rp. {{number_format($order->price,2)}}
                                    </td>
                                    <td>{{$order->status}}</td>
                                    <td>{{date('Y-m-d',strtotime($order->reserve_at))}}</td>
                                    <td>
                                        <a class="badge badge-primary" href="{{route('order.show',$order->id)}}"
                                            target="_blank">Detail
                                            Pengeluaran</a>
                                        <form action="{{route('order.destroy',$order->id)}}" class="d-inline"
                                            method="POST">
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
                        {{$orders->links("pagination::bootstrap-4")}}
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