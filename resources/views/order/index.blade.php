@extends('layouts.main')
@section('title')
Pemesanan
@endsection
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Pemesanan</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">Pemesanan</li>
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
                        <h3 class="card-title">Cari Pemesan</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{route('order.search')}}" method="GET">
                            <div class="form-row">
                                <div class="col">
                                    <div class="form-group">
                                        <label>Cari Nama Pelanggan</label>
                                        <input type="text" name="name" class="form-control" value="{{old('name')}}"
                                            placeholder="Cari Nama Pelanggan">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label>Cari Kode Order</label>
                                        <input type="text" name="code_order" class="form-control"
                                            value="{{old('code_order')}}" placeholder="Cari Kode Order">
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                {{-- <div class="col">
                                    <div class="form-group">
                                        <label>Cari Rute</label>
                                        <select name="route_id" class="form-control select2">
                                            <option value="">--Semua Rute--</option>
                                            @foreach ($routes as $route)
                                            @if (old('route_id') == $route->id)
                                            <option value="{{$route->id}}" selected>{{$route->name}}</option>
                                @else
                                <option value="{{$route->id}}">{{$route->name}}</option>
                                @endif
                                @endforeach
                                </select>

                            </div>
                    </div> --}}
                    <div class="col">
                        <div class="form-group">
                            <label for="">Cari Status</label>
                            <select name="status" class="form-control">
                                <option value="">--Semua Status--</option>
                                @foreach ($status as $s)
                                @if (old('status') == $s)
                                <option value="{{$s}}" selected>{{$s}}</option>
                                @else
                                <option value="{{$s}}">{{$s}}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="">Status Pesanan</label>
                            <select name="agent" class="form-control">
                                <option value="">--Semua Pemesan--</option>
                                @foreach ($agent as $a)
                                @if (old('agent') == $a)
                                <option value="{{$a}}" selected>{{$a}}</option>
                                @else
                                <option value="{{$a}}">{{$a}}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col">
                        <div class="form-group">
                            <label>Armada</label>
                            <select name="fleet_detail_id" class="form-control select2">
                                <option value="">--PILIH ARMADA--</option>
                                @foreach ($fleet_details as $fleet_detail)
                                @if (old('fleet_detail_id') == $fleet_detail->id)
                                <option value="{{$fleet_detail->id}}" selected>
                                    {{$fleet_detail->fleet?->name}}/{{$fleet_detail->fleet?->fleetclass?->name}}
                                    ({{$fleet_detail->nickname}})
                                </option>
                                @else
                                <option value="{{$fleet_detail->id}}">
                                    {{$fleet_detail->fleet?->name}}/{{$fleet_detail->fleet?->fleetclass?->name}}
                                    ({{$fleet_detail->nickname}})
                                </option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label>Agen</label>
                            <select class="form-control select2" name="agency_id">
                                <option value="">--PILIH AGEN--</option>
                                @foreach ($agencies as $agency)
                                @if (old('agency_id') == $agency->id)
                                <option value="{{$agency->id}}" selected>
                                    {{$agency->name}}
                                </option>
                                @else
                                <option value="{{$agency->id}}">
                                    {{$agency->name}}
                                </option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col">
                        <div class="form-group">
                            <label>Mulai Dari</label>
                            <input type="date" class="form-control" name="date_from" value="{{old('date_from')}}">
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label>Sampai Dengan</label>
                            <input type="date" class="form-control" name="date_to" value="{{old('date_to')}}">
                        </div>
                    </div>
                </div>

                <div class="text-right">
                    <button class="btn btn-success" type="submit">Cari</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Table Pemesanan</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Pemesan</th>
                            <th>Kode Order</th>
                            <th>Rute</th>
                            <th>Armada</th>
                            <th>Total Harga</th>
                            <th>Status</th>
                            <th>Keberangkatan -> Kedatangan</th>
                            <th>Tanggal Pemesanan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                        <tr>
                            <td>
                                @if ($order->user?->agencies)
                                <a href="{{route('user_agent.show',$order->user?->id)}}">
                                    {{$order->user?->name_agent}}
                                </a>
                                @elseif ($order->user)
                                <a href="{{route('user.edit',$order->user?->id)}}">
                                    {{$order->user?->name}}
                                </a>
                                @else
                                {{$order->order_detail[0]->name}}
                                @endif
                            </td>
                            <td>{{$order->code_order}}</td>
                            <td>
                                @if ($order->fleet_route)
                                <a href="{{route('routes.show',$order->fleet_route?->route_id)}}">
                                    {{$order->fleet_route?->route?->name}}
                                </a>
                                @endif
                            </td>
                            <td>
                                {{$order->fleet_route?->fleet_detail?->fleet?->name}}/{{$order->fleet_route?->fleet_detail?->fleet?->fleetclass?->name}}
                                ({{$order->fleet_route?->fleet_detail?->nickname}})
                            </td>
                            <td>
                                Rp. {{number_format($order->price,2)}}
                            </td>
                            <td>{{$order->status}}</td>
                            <td>{{$order->agency?->name}} -> {{$order->agency_destiny?->name}}</td>
                            <td>{{date('Y-m-d',strtotime($order->reserve_at))}}</td>
                            <td>
                                <a class="btn btn-primary btn-xs" href="{{route('order.show',$order->id)}}">Detail</a>
                                <form action="{{route('order.destroy',$order->id)}}" class="d-inline" method="POST">
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
            "order": [
                [1, "desc"]
            ],
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    });
</script>
@endpush