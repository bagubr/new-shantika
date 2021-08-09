@extends('layouts.main')
@section('title')
Dashboard
@endsection
{{-- @push('css')
@endpush --}}
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
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
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
            <h3 class="card-title">Statistik Penjualan Tiket</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <form action="{{route('dashboard')}}" method="GET">
                <div class="form-group">
                    <label>Periode</label>
                    <select name="statistic" class="form-control" id="">
                        @foreach ($data_statistic as $data1 => $value)
                        @if (old('statistic') == $data1)
                        <option value="{{$data1}}" selected>{{$value}}</option>
                        @else
                        <option value="{{$data1}}">{{$value}}</option>
                        @endif
                        @endforeach
                    </select>
                </div>
                <div class="text-right">
                    <button class="btn btn-success" type="submit">Cari</button>
                </div>
            </form>
            <div class="chart">
                <canvas id="barChart"
                    style="min-height: 250px; height: 500px; max-height: 500px; max-width: 100%;"></canvas>
            </div>
        </div>
    </div>
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Pemesan</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <form action="{{route('dashboard')}}" method="GET">
                <div class="form-row">
                    <div class="col">
                        <div class="form-group">
                            <label for="">Agen</label>
                            <select name="agency" class="form-control select2">
                                <option value="">-Semua Agen-</option>
                                @foreach ($agencies as $agency)
                                @if (old('agency') == $agency->id)
                                <option value="{{$agency->id}}" selected>{{$agency->city_name}}/{{$agency->name}}
                                </option>
                                @else
                                <option value="{{$agency->id}}">{{$agency->city_name}}/{{$agency->name}}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="">Armada Bus</label>
                            <select name="fleet" class="form-control">
                                <option value="">-Semua Armada-</option>
                                @foreach ($fleets as $fleet)
                                @if (old('fleet') == $fleet->id)
                                <option value="{{$fleet->id}}" selected>{{$fleet->name}}</option>
                                @else
                                <option value="{{$fleet->id}}">{{$fleet->name}}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="">Rute</label>
                            <select name="route" class="form-control select2">
                                <option value="">-Semua Rute-</option>
                                @foreach ($routes as $route)
                                @if (old('route') == $route->id)
                                <option selected value="{{$route->id}}">{{$route->name}}</option>
                                @endif
                                <option value="{{$route->id}}">{{$route->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="text-right">
                    <button class="btn btn-success" type="submit">Cari</button>
                </div>
            </form>
            <table class="table table-bordered table-striped mt-3">
                <thead>
                    <tr>
                        <th>Agen</th>
                        <th>Armada</th>
                        <th>Rute</th>
                        <th>Total Tiket</th>
                        <th>Total Pendapatan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                    <tr>
                        <td>
                            {{$order->agency?->name ?? 'Tidak Menggunakan Agen'}}
                        </td>
                        <td>{{$order->route->fleet->name}}</td>
                        <td>
                            <a href="{{route('routes.show',$order->route->id)}}" target="_blank">
                                {{$order->route->name}}
                            </a>
                        </td>
                        <td>{{count($order->order_detail)}}</td>
                        <td>Rp. {{number_format($order->price,2)}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{$orders->links("pagination::bootstrap-4")}}
        </div>
    </div>
    <div>
        <div class="form-group">
            <label>Periode</label>
            <select name="" class="form-control">
                <option value="">Harian</option>
                <option value="">Mingguan</option>
                <option value="">Bulanan</option>
                <option value="">Tahun</option>
            </select>
        </div>
        <div class="row">
            <div class="col-6">
                <div class="card card-success">
                    <div class="card-header">
                        <h3 class="card-title">Pendapatan Penjualan Tiket Periode ini </h3>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="myChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card card-success">
                    <div class="card-header">
                        <h3 class="card-title">Pendapatan Penjualan Tiket Periode Sebelumnya</h3>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="myChart2"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{count($orders)}}</h3>

                    <p>Total Pesanan</p>
                </div>
                <div class="icon">
                    <i class="ion ion-bag"></i>
                </div>
                <a href="{{route('order.index')}}" class="small-box-footer">Lebih Lanjut <i
                        class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{$count_user}}</h3>

                    <p>Pelanggan</p>
                </div>
                <div class="icon">
                    <i class="ion ion-person"></i>
                </div>
                <a href="{{route('user.index')}}" class="small-box-footer">Lebih Lanjut <i
                        class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-6 col-sm-12">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>Rp. {{number_format($orders_money)}}</h3>

                    <p>Total Pemasukan</p>
                </div>
                <div class="icon">
                    <i class="ion ion-cash"></i>
                </div>
                <a href="{{route('user.index')}}" class="small-box-footer">Lebih Lanjut <i
                        class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>
    <div>
        <div class="form-group">
            <label>Periode</label>
            <select name="" class="form-control">
                <option value="">Harian</option>
                <option value="">Mingguan</option>
                <option value="">Bulanan</option>
                <option value="">Tahun</option>
            </select>
        </div>
        <div class="row">
            <div class="col-6">
                <div class="card card-success">
                    <div class="card-header">
                        <h3 class="card-title">Pendapatan Bersih Periode ini </h3>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="myChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card card-success">
                    <div class="card-header">
                        <h3 class="card-title">Pendapatan Bersih Periode Sebelumnya</h3>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="myChart2"></canvas>
                        </div>
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
<script src="{{asset('plugins/chart.js/Chart.min.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $(function () {
        $('.select2').select2()
    })
    $('.select2bs4').select2({
        theme: 'bootstrap4'
    })

</script>
<script>
    const labels = [
        'January',
        'February',
        'March',
        'April',
        'May',
        'June',
    ];
    const data = {
    labels: labels,
    datasets: [{
        label: 'My First dataset',
        backgroundColor: 'rgb(255, 99, 132)',
        borderColor: 'rgb(255, 99, 132)',
        data: [0, 10, 5, 2, 20, 30, 45],
    }]
    };
    const config = {
        type: 'line',
        data,
        options: {}
    };
    var myChart = new Chart(
        document.getElementById("myChart"),
        config
    );
    var myChart2 = new Chart(
        document.getElementById("myChart2"),
        config
    );
</script>
<script>
    $(function(){
        var areaChartData = {
      labels  : [@foreach ($data['params'] as $d)
      "{{$d}}",
      @endforeach],
      datasets: [
        {
          label               : 'Jawa',
          backgroundColor     : '#17a2b8',
          borderColor         : '#17a2b8',
          pointRadius         : false,
          pointColor          : '#17a2b8',
          pointStrokeColor    : '#c1c7d1',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgba(220,220,220,1)',
          data                : [@foreach ($data['weekly'][0] as $d)
              {{$d}},
          @endforeach]
        },
        {
          label               : 'Jabodetabek',
          backgroundColor     : '#c1c7d1',
          borderColor         : '#c1c7d1',
          pointRadius         : false,
          pointColor          : '#c1c7d1',
          pointStrokeColor    : '#c1c7d1',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgba(220,220,220,1)',
          data                : [@foreach ($data['weekly2'][0] as $d)
              {{$d}},
          @endforeach]
        },
      ]
    }
            //-------------
    //- BAR CHART -
    //-------------
    var barChartCanvas = $('#barChart').get(0).getContext('2d')
    var barChartData = $.extend(true, {}, areaChartData)
    var temp0 = areaChartData.datasets[0]
    // barChartData.datasets[1] = temp0

    var barChartOptions = {
      responsive              : true,
      maintainAspectRatio     : false,
      datasetFill             : false
    }

    new Chart(barChartCanvas, {
      type: 'bar',
      data: barChartData,
      options: barChartOptions
    })
    })
</script>
{{-- <script>
    const DATA_COUNT = 7;
    const NUMBER_CFG = {count: DATA_COUNT, min: -100, max: 100};
    
    const labels = Utils.months({count: 7});
    const data = {
        labels: labels,
        datasets: [
            {
                label: 'Dataset 1',
                data: Utils.numbers(NUMBER_CFG),
                borderColor: Utils.CHART_COLORS.red,
                backgroundColor: Utils.transparentize(Utils.CHART_COLORS.red, 0.5),
            },
            {
                label: 'Dataset 2',
                data: Utils.numbers(NUMBER_CFG),
                borderColor: Utils.CHART_COLORS.blue,
                backgroundColor: Utils.transparentize(Utils.CHART_COLORS.blue, 0.5),
            }
        ]
        };
</script> --}}
@endpush