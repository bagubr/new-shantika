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
                        <div class="text-right">
                            <a href="{{route('outcome_type.create')}}" class="btn btn-outline-warning btn-sm">Tambah
                                Tipe Pengeluaran</a>
                            <a href="{{route('outcome.create')}}" class="btn btn-primary btn-sm">Tambah</a>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Tanggal Laporan</th>
                                    <th>Rute</th>
                                    <th>Tipe Pengeluaran</th>
                                    <th>Armada</th>
                                    <th>Kelas Armada</th>
                                    <th>Total Pendapatan</th>
                                    <th>Total Pengeluaran</th>
                                    <th>Total Pendapatan Bersih</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($outcomes as $outcome)
                                <tr>
                                    <td>{{$outcome->reported_at}}</td>
                                    <td>{{$outcome?->fleet_route?->route->name??"Non Rute"}}</td>
                                    <td>{{$outcome->outcome_type?->name??'-'}}</td>
                                    <td>{{$outcome?->fleet_route?->fleet?->name??'Non Rute'}}</td>
                                    <td>{{$outcome?->fleet_route?->fleet?->fleetclass?->name??'Non Rute'}}</td>
                                    <td>Rp. {{number_format($outcome->sum_total_pendapatan,2)}}</td>
                                    <td>Rp. {{number_format($outcome->sum_pengeluaran,2)}}</td>
                                    <td>Rp. {{number_format($outcome->sum_total_pendapatan_bersih,2)}}</td>
                                    <td>
                                        <a href="{{route('outcome.show',$outcome->id)}}"
                                            class="btn btn-info btn-xs">Show</a>
                                        <form action="{{route('outcome.destroy',$outcome->id)}}" class="d-inline"
                                            method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-xs"
                                                onclick="return confirm('Apakah Anda yakin akan menghapus data tipe pengeluaran?')"
                                                type="submit">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{$outcomes->links("pagination::bootstrap-4")}}
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{route('dashboard')}}" method="GET">
                            <div class="form-group">
                                <label>Periode</label>
                                <select name="statistic" class="form-control statistic">
                                    @foreach ($params as $key => $value)
                                    @if (old('statistic') == $key)
                                    <option value="{{$key}}" selected>{{$value}}</option>
                                    @else
                                    <option value="{{$key}}">{{$value}}</option>
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
<script src="{{asset('plugins/chart.js/Chart.min.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $(function(){
        var areaChartData = {
      labels  : [@foreach ($data['labels'] as $d)
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
          data                : [@foreach ($data['now'] as $value){{$value}},@endforeach]
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
          data                : [@foreach ($data['previous'] as $value){{$value}},@endforeach]
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

    window.myChart = new Chart(barChartCanvas, {
      type: 'bar',
      data: barChartData,
      options: barChartOptions
    })
    })
</script>
<script>
    $(document).on('change', '.statistic', function (e) {
        console.log(this.value);
        $.ajax({
            url: "{{url('outcome/statistic')}}",
            type: "POST",
            data: {
                _token: '{{ csrf_token() }}',
                params: this.value,
            },
            success: function (data) {
                window.myChart.data.labels = data['labels'];
                window.myChart.data.datasets[0].data = data['now'];
                window.myChart.data.datasets[1].data = data['previous'];
                window.myChart.update();
                console.log(data['labels'])
            },
        });
    });
</script>
@endpush