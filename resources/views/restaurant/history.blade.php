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
                        <form action="">
                            <div class="form-group">
                                <label>Restoran</label>
                                <select name="restaurant_id" class="form-control select2">
                                    <option value="">Pilih Restoran</option>
                                    @foreach ($restaurants as $restaurant)
                                    <option value="{{$restaurant->id}}">{{$restaurant->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="text-right">
                                <button type="submit" class="btn btn-success">Cari</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Table Riwayat Kupon Makan</h3>
                        <div class="text-right">
                            <a href="{{route('restaurant.create')}}" class="btn btn-primary btn-sm">Tambah</a>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Restoran</th>
                                    <th>Waktu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($food_reddem_histories as $food_reddem_history)
                                <tr>
                                    <td>{{$food_reddem_history->restaurant?->name}}</td>
                                    <td>{{$food_reddem_history->created_at}}</td>
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
        "responsive": true, "lengthChange": false, "autoWidth": false,
      }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    });
</script>
@endpush