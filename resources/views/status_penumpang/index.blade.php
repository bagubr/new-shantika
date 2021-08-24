@extends('layouts.main')
@section('title')
Status Pemesanan
@endsection
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Status Pemesanan</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">Status Pemesanan</li>
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
                        <h3 class="card-title">Table Status Pemesanan</h3>
                        <div class="text-right">
                            <a href="{{route('user.create')}}" class="btn btn-primary btn-sm">Tambah</a>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Kode Order</th>
                                    <th>Area</th>
                                    <th>Rute Armada</th>
                                    <th>Akun</th>
                                    <th>Nama</th>
                                    <th>Tipe Pembayaran</th>
                                    <th>Status</th>
                                    <th>Harga</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $order)
                                <tr>
                                    <td>{{date('Y-m-d',strtotime($order->reserve_at))}}</td>
                                    <td>
                                        <a href="{{route('order.show',$order->id)}}">
                                            {{$order->code_order}}
                                        </a>
                                    </td>
                                    <td>{{$order->fleet_route?->route?->departure_city?->area?->name}}</td>
                                    <td>
                                        <a href="{{route('fleet_route.show',$order->fleet_route->id)}}">
                                            {{$order->fleet_route?->route->name}}/{{$order->fleet_route?->fleet->name}}
                                        </a>
                                    </td>
                                    <td>
                                        @if ($order->user?->agencies)
                                        <a href="{{route('user_agent.show',$order->user_id)}}" target="_blank">
                                            {{$order->user?->name_agent}}
                                        </a>
                                        @elseif ($order->user)
                                        <a href="{{route('user.edit', $order->user_id)}}">
                                            {{$order->user?->name}}
                                        </a>
                                        @else
                                        Tanpa Akun
                                        @endif
                                    </td>
                                    <td>{{$order->order_detail[0]->name}}</td>
                                    <td>{{$order->payment?->payment_type->name}}</td>
                                    <td>{{$order->status}}</td>
                                    <td>Rp. {{number_format($order->price)}}</td>
                                    <td>
                                        <a href="{{route('order.show', $order->id)}}" class="btn btn-primary btn-xs">
                                            Detail
                                        </a>
                                        @if ($order->payment?->status == 'WAITING_CONFIRMATION')
                                        <a href="{{route('payment.edit',$order->payment?->id)}}"
                                            class="btn btn-warning btn-xs">Edit</a>
                                        @endif
                                        {{-- <form action="{{route('user.destroy',$order->id)}}" class="d-inline"
                                        method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-xs"
                                            onclick="return confirm('Apakah Anda Yakin  Menghapus Data Ini??')"
                                            type="submit">Delete</button>
                                        </form> --}}
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
        "responsive": true, "lengthChange": false, "autoWidth": false,"order": [[0,"desc"]]
      }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    });
</script>
@endpush