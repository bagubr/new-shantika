@extends('layouts.main')
@section('title')
Setoran
@endsection
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Setoran</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">Setoran</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-4">
                <div class="card">
                    <form action="{{route('order_price_distribution.search')}}" method="get">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Pilih Tanggal</label>
                                <input type="date" name="date_search" class="form-control"
                                    value="{{old('date_search') }}">
                            </div>
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
                            <div class="form-group">
                                <label>Agen</label>
                                <select name="agency_id" class="form-control select2">
                                    <option value="">--PILIH AGEN--</option>
                                    @foreach ($agencies as $agency)
                                    @if (old('agency_id') == $agency->id)
                                    <option value="{{$agency->id}}" selected>
                                        {{$agency->city?->name}}/{{$agency->name}}
                                    </option>
                                    @else
                                    <option value="{{$agency->id}}">
                                        {{$agency->city?->name}}/{{$agency->name}}
                                    </option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="text-right m-2">
                            <button class="btn btn-success" type="submit">Cari</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-8">
                <div class="row">
                    <div class="col-md-6">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3><sup style="font-size: 20px">Rp</sup> {{number_format($count_income)}}</h3>
                                <p>Total Setoran Agen</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-card"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h3><sup style="font-size: 20px">Rp</sup> {{number_format($count_outcome)}}</h3>
                                <p>Total Pengeluaran</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-card"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="small-box bg-primary">
                            <div class="inner">
                                <h3>{{number_format($count_seat)}} <sup style="font-size: 20px">Kursi</sup>
                                </h3>
                                <p>Total Kursi Terjual</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-man"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3><sup style="font-size: 20px">Rp</sup> {{number_format($count_pendapatan_bersih)}}
                                </h3>
                                <p>Total Pendapatan Bersih</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-stats-bars"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Table Setoran</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Kode Order</th>
                                    <th>Armada</th>
                                    <th>Agen</th>
                                    <th>Jumlah Seat</th>
                                    <th>Rute</th>
                                    <th>Harga Tiket</th>
                                    <th>Dana Agen</th>
                                    <th>Makan</th>
                                    <th>Travel</th>
                                    <th>Member</th>
                                    <th>Agent</th>
                                    <th>Total Owner</th>
                                    <th>Deposit</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order_price_distributions as $order_price_distribution)
                                <tr>
                                    <td>{{date('Y-m-d',strtotime($order_price_distribution->order?->reserve_at))}}</td>
                                    <td>
                                        @if ($order_price_distribution->order)
                                        <a href="{{route('order.show',$order_price_distribution->order?->id)}}">
                                            {{$order_price_distribution->order?->code_order}}
                                        </a>
                                        @endif
                                    </td>
                                    <td>{{$order_price_distribution->order?->fleet_route?->fleet_detail?->fleet?->name}}/{{$order_price_distribution->order?->fleet_route?->fleet_detail?->fleet?->fleetclass?->name}}
                                        ({{$order_price_distribution->order?->fleet_route?->fleet_detail?->nickname}})
                                    </td>
                                    <td>{{$order_price_distribution->order?->agency?->name}}</td>
                                    <td>
                                        {{$order_price_distribution->order?->order_detail?->count()}}
                                        {{-- (
                                @foreach ($order_price_distribution->order?->order_detail as $order_detail)
                                {{$order_detail->chair?->name}}
                                        @if (!$loop->last)
                                        ,
                                        @endif
                                        @endforeach
                                        ) --}}
                                    </td>
                                    <td>
                                        @if ($order_price_distribution->order?->fleet_route)
                                        <a
                                            href="{{route('fleet_route.show',$order_price_distribution->order?->fleet_route_id)}}">
                                            {{$order_price_distribution->order?->fleet_route?->route?->name}}
                                        </a>
                                        @endif
                                    </td>
                                    <td>Rp. {{number_format($order_price_distribution->order?->fleet_route?->price)}}
                                    </td>
                                    <td>Rp.
                                        {{number_format($order_price_distribution->order?->fleet_route?->price * $order_price_distribution->order?->order_detail?->count())}}
                                    </td>
                                    <td>Rp. {{number_format($order_price_distribution->for_food,2)}}</td>
                                    <td>Rp. {{number_format($order_price_distribution->for_travel,2)}}</td>
                                    <td>Rp. {{number_format($order_price_distribution->for_member,2)}}</td>
                                    <td>Rp. {{number_format($order_price_distribution->for_agent,2)}}</td>
                                    <td>Rp. {{number_format($order_price_distribution->for_owner,2)}}</td>
                                    <td>
                                        @if ($order_price_distribution->deposited_at)
                                        {{date('Y-m-d', strtotime($order_price_distribution->deposited_at))}}
                                        @else
                                        Belum Deposit
                                        @endif
                                    </td>
                                    <td>
                                        @if (!$order_price_distribution->deposited_at)
                                        <form
                                            action="{{route('order_price_distribution.update', $order_price_distribution->id)}}"
                                            class="d-inline" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <button class="btn btn-primary btn-xs"
                                                onclick="return confirm('Apakah Anda Yakin Ingin Deposit?')"
                                                type="submit">Deposit
                                                Sekarang</button>
                                        </form>
                                        @endif
                                        <form
                                            action="{{route('order_price_distribution.destroy',$order_price_distribution->id)}}"
                                            class="d-inline" method="POST">
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
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Table Pengeluaran</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="example2" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Tanggal Laporan</th>
                                    <th>Rute/Armada</th>
                                    <th>Nama Pengeluaran</th>
                                    <th>Nominal Pengeluaran</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($outcome_details as $outcome)
                                <tr>
                                    <td>{{date('Y-m-d',strtotime($outcome->outcome?->reported_at))}}</td>
                                    @if ($outcome->outcome->fleet_detail)
                                    <td>{{$outcome->outcome?->fleet_detail?->fleet?->name}}/{{$outcome->outcome?->fleet_detail?->nickname}}
                                    </td>
                                    @else
                                    <td>Tidak Ada</td>
                                    @endif
                                    <td>{{$outcome->name}}</td>
                                    <td>Rp {{number_format($outcome->amount)}}</td>
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
    $(function () {
      $("#example2").DataTable({
        "responsive": true, "lengthChange": false, "autoWidth": false,
      }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    });
</script>
@endpush