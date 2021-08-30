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
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{$order_count}}</h3>

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
                    <select name="statistic" class="form-control">
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
@endpush