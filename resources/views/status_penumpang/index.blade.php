@extends('layouts.main')
@section('title')
Status Penumpang
@endsection
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Status Penumpang</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">Status Penumpang</li>
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
                    <form action="" method="get">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Pilih Area</label>
                                        <select name="area_id" class="form-control">
                                            <option value="">--PILIH AREA--</option>
                                            @foreach ($areas as $area)
                                            @if (isset($area_id) && $area_id == $area->id)
                                            <option value="{{$area->id}}" selected>{{$area->name}}</option>
                                            @else
                                            <option value="{{$area->id}}">{{$area->name}}</option>
                                            @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Tanggal Pesan</label>
                                        <input type="date" name="reserve_at" id="" value="{{$reserve_at}}" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="">Code Order</label>
                                    <div class="form-group">
                                        <input type="text" name="code_order" id="" value="{{$code_order}}" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-right m-2">
                            <button class="btn btn-success" type="submit">Cari</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Table Status Penumpang</h3>
                        <div class="text-right">
                            <a href="{{route('status_penumpang.export')}}" class="btn btn-success btn-sm">Export</a>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table class="table table-bordered table-striped table-responsive">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Kode Order</th>
                                    <th>Area Keberangkatan</th>
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
                                    <td>{{$order->agency->city->area->name ?? ""}}
                                    </td>
                                    <td>
                                        @if ($order->fleet_route)
                                        <a href="{{route('fleet_route.show',$order->fleet_route?->id)}}">
                                            {{$order->fleet_route?->route?->name}}
                                        </a>
                                        @endif
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
                                    <td>{{$order?->order_detail[0]?->name??''}}</td>
                                    <td>
                                        @if ($order->payment)
                                        {{$order->payment?->payment_type->name}}
                                        @else
                                        Pemesanan Melalui Agen
                                        @endif
                                    </td>
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
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="row">
                            <div class="ml-auto">
                                <div>
                                    {{$orders->appends(Request::all())->links() }}
                                </div>
                            </div>
                        </div>
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
