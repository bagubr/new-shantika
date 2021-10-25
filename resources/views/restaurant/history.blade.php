@extends('layouts.main')
@section('title')
Riwayat Kupon Makan
@endsection
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Riwayat Kupon Makan</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">Riwayat Kupon Makan</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Cari Restoran</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{route('r.history_restaurant_search')}}" method="get">
                            <div class="form-group">
                                <label>Restoran</label>
                                <select name="restaurant_id" class="form-control select2">
                                    <option value="">Pilih Restoran</option>
                                    @foreach ($restaurants as $restaurant)
                                    @if (old('restaurant_id') == $restaurant->id)
                                    <option value="{{$restaurant->id}}" selected>{{$restaurant->name}}</option>
                                    @else
                                    <option value="{{$restaurant->id}}">{{$restaurant->name}}</option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-row">
                                <div class="col">
                                    <label>Tanggal Awal</label>
                                    <input type="date" class="form-control" value="{{old('start_date')}}"
                                        name="start_date">
                                </div>
                                <div class="col">
                                    <label>Tanggal Akhir</label>
                                    <input type="date" class="form-control" value="{{old('end_date')}}" name="end_date">
                                </div>
                            </div>
                            <div class="text-right mt-3">
                                <button type="submit" class="btn btn-success">Cari</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{number_format($food_reddem_histories->pluck('price_food')->sum())}} <sup
                                style="font-size: 20px">Rupiah</sup>
                        </h3>
                        <p>Total Yang Harus Dibayar</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Table Riwayat Kupon Makan</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Nama Restoran</th>
                                    <th>Nama Pemesan</th>
                                    <th>Kode Transaksi</th>
                                    <th>Armada</th>
                                    <th>Harga Makanan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($food_reddem_histories as $food_reddem_history)
                                <tr>
                                    <td>{{$food_reddem_history->created_at}}</td>
                                    <td>{{$food_reddem_history->restaurant?->name}}</td>
                                    <td>{{$food_reddem_history->order_detail?->name}}</td>
                                    <td>{{$food_reddem_history->order_detail?->order?->code_order}}</td>
                                    <td>{{$food_reddem_history->order_detail?->order?->fleet_route?->fleet_detail?->fleet?->name}}
                                    <td>Rp.
                                        {{number_format($food_reddem_history->order_detail?->order?->fleet_route?->fleet_detail?->fleet?->fleetclass?->price_food)}}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
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
            "buttons": [
                {
                    "extend": 'pdf',
                    "exportOptions": {
                        "columns": [0,1, 2, 3,4,5]
                    }
                },
                {
                    "extend": 'csv',
                    "exportOptions": {
                        "columns": [0,1, 2, 3,4,5]
                    }

                },
                {
                    "extend": 'excel',
                    "exportOptions": {
                        "columns": [0,1, 2, 3,4,5]
                    }
                },
                {
                    "extend": 'print',
                    "exportOptions": {
                        "columns": [0,1, 2, 3,4,5]
                    }
                }
            ],
            "dom": 'Bfrtip',
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    });
</script>
@endpush