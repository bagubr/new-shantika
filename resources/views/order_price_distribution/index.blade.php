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
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label>Pilih Tanggal</label>
                                <input type="date" name="date_search" class="form-control"
                                    value="{{old('date_search')}}">
                            </div>
                            <div class="form-group">
                                <label>Pilih Rute Armada</label>
                                <select name="fleet_route_search" class="form-control select2" id="">
                                    <option value="">Pilih Rute Armada</option>
                                    @foreach ($fleet_routes as $fleet_route)
                                    @if (old('fleet_route_search') == $fleet_route->id)
                                    <option value="{{$fleet_route->id}}" selected>
                                        {{$fleet_route->route?->name}}/{{$fleet_route->fleet?->name}}
                                    </option>
                                    @else
                                    <option value="{{$fleet_route->id}}">
                                        {{$fleet_route->route?->name}}/{{$fleet_route->fleet?->name}}
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
                    <div class="col-6">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3><sup style="font-size: 20px">Rp</sup> {{number_format($count_income)}}</h3>
                                <p>Total Pemasukkan</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-stats-bars"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h3><sup style="font-size: 20px">Rp</sup> {{number_format($count_outcome)}}</h3>
                                <p>Total Pengeluaran</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-stats-bars"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
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
                                    <th>Tanggal Pemesanan</th>
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
                                    <td>{{$order_price_distribution->order?->agency?->name}}</td>
                                    <td>{{$order_price_distribution->order?->order_detail?->count()}}</td>
                                    <td>
                                        <a
                                            href="{{route('fleet_route.show',$order_price_distribution->order?->fleet_route_id)}}">
                                            {{$order_price_distribution->order?->fleet_route?->route?->name}}/{{$order_price_distribution->order?->fleet_route?->fleet?->name}}
                                        </a>
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
                                                onclick="return confirm('Apakah Anda Yakin  Menghapus Data Ini??')"
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
                                    <td>{{$outcome->outcome?->fleet_route?->route?->name}}/{{$outcome->outcome?->fleet_route?->fleet?->name}}
                                    </td>
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
<script>
    $(function () {
        $('.select2').select2()
    })
    $('.select2bs4').select2({
        theme: 'bootstrap4'
    })

</script>
@endpush