@extends('layouts.main')
@section('title')
    Dashboard
@endsection
@push('css')
    <link rel="stylesheet" href="https://adminlte.io/themes/AdminLTE/plugins/pace/pace.min.css">
    <style>
        .chartWrapper {
            position: relative;
        }

        .chartWrapper>canvas {
            position: absolute;
            left: 0;
            top: 0;
            pointer-events: none;
        }

        .chartAreaWrapper {
            width: 600px;
            overflow-x: scroll;
        }

    </style>
@endpush
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Dashboard</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="card card-success">
            <div class="card-header">
                <div class="card-title">
                    Jumlah Penjualan
                </div>
            </div>
            <div class="card-body">
                <form class="row" action="{{ route('dashboard') }}" method="GET">
                    @csrf
                    <div class="col-6">
                        <div class="form-group">
                            <label>Tahun</label>
                            <select name="year" class="form-control change-statistic-sum">
                                @for ($x = 0; $x <= 5; $x++)
                                    <option>{{ date('Y', strtotime("-$x years")) }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label>Bulan</label>
                            <select name="month" class="form-control change-statistic-sum" id="">
                                <option value selected>--PILIH BULAN--</option>
                                @for($x=date('m', strtotime('-'.date('m', strtotime('-1 month')).' month'));$x<=12;$x++)
                                    <option value="{{ date('m', strtotime("$x month")) }}">{{ date('F', strtotime("$x month")) }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                </form>
                <div class="row">
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3 id="sum-total-order">{{ $total_order }}</h3>

                                <p>Total Pesanan</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-bag"></i>
                            </div>
                            <a href="{{ route('order.index') }}" class="small-box-footer">Lebih Lanjut <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3 id="sum-count-user">{{ $count_user }}</h3>

                                <p>Pelanggan</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-person"></i>
                            </div>
                            <a href="{{ route('user.index') }}" class="small-box-footer">Lebih Lanjut <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-12">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3 id="sum-count-money">Rp. {{ number_format($orders_money) }}</h3>

                                <p>Total Pemasukan</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-cash"></i>
                            </div>
                            <a href="{{ route('user.index') }}" class="small-box-footer">Lebih Lanjut <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title">Statistik Penjualan Tiket</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('dashboard') }}" method="GET">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Periode</label>
                                <select name="tiket" class="form-control statistic">
                                    @foreach ($data['params'] as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Armada</label>
                                <select name="fleet_detail_id" class="form-control change-statistic-fleet select2">
                                    <option value="">--PILIH ARMADA--</option>
                                    @foreach ($data['fleets'] as $fleet)
                                        <option value="{{ $fleet->id }}">
                                            {{ $fleet->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Agent</label>
                                <select name="tiket" class="form-control change-statistic-agent select2">
                                    <option value="">--PILIH AGEN--</option>
                                    @foreach ($data['agencies'] as $key => $value)
                                        <option value="{{ $value->id }}">{{ $value->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="text-right">
                            <button class="btn btn-success" type="submit">Cari</button>
                        </div> -->
                </form>
                <div class="row">
                    <div class="col">
                        <div class="text-center">
                            <i class="fas fa-arrow-left change-statistic" data-digit="{{ $data['digit'] ?? 0 }}"></i>
                            <p class="label-previous">{{ $data['previous']['data']['label'] }}</p>
                        </div>
                        <div class="chart">
                            <canvas id="ChartPrevious"
                                style="min-height: 250px; height: 500px; max-height: 500px; max-width: 100%;"></canvas>
                        </div>
                    </div>
                    <div class="col">
                        <div class="text-center">
                            <i class="fas fa-arrow-right change-statistic-previous"
                                data-digit="{{ $data['digit'] ?? 0 }}"></i>
                            <p class="label-now">{{ $data['now']['data']['label'] }}</p>
                        </div>
                        <div class="chart">
                            <canvas id="ChartNow"
                                style="min-height: 250px; height: 500px; max-height: 500px; max-width: 100%;"></canvas>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Area</label>
                            <select name="area_id" class="form-control change-statistic-area select2">
                                <option value="">--PILIH AREA--</option>
                                @foreach ($data_agent['area'] as $key => $value)
                                    <option value="{{ $value->id }}">{{ $value->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Tahun</label>
                            <select name="year" class="form-control change-statistic-area">
                                @for ($x = 0; $x <= 5; $x++)
                                    <option>{{ date('Y', strtotime("-$x years")) }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Bulan</label>
                            <select name="month" class="form-control change-statistic-area" id="">
                                <option value selected>--PILIH BULAN--</option>
                                @for($x=date('m', strtotime('-'.date('m', strtotime('-1 month')).' month'));$x<=12;$x++)
                                <option value="{{ date('m', strtotime("$x month")) }}">{{ date('F', strtotime("$x month")) }}</option>
                                    @endfor
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="text-center">
                            {{-- <i class="fas fa-arrow-left change-statistic" data-digit-agent="{{$data['digit-agent']??0}}"></i> --}}
                            <p class="label-agent">{{ @$data_agent['agent']['data']['label'] }}</p>
                            {{-- <i class="fas fa-arrow-right change-statistic-previous" data-digit-agent="{{$data['digit-agent']??0}}"></i> --}}
                        </div>
                        <div class="chart">
                            <canvas id="ChartAgent"
                                style="min-height: 250px; height: 500px; max-height: 5000px; max-width: 100%;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
@endsection
@push('script')
    <!-- ChartJS -->
    <script src="https://adminlte.io/themes/AdminLTE/bower_components/PACE/pace.min.js"></script>
    <script src="{{ asset('plugins/chart.js/Chart.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @include('dashboard.statistic-tiket')
    {{-- @include('dashboard.statistic-agent') --}}
    @include('dashboard.statistic-sum')
    <script>
        $(function() {
            $("#example1").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "pageLength": 5,
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        });
    </script>
@endpush
