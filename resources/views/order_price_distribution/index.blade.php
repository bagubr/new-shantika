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
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Table Setoran</h3>
                        <div class="text-right">
                            <a href="{{route('order_price_distribution.create')}}"
                                class="btn btn-primary btn-sm">Tambah</a>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Kode Order</th>
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
                                    <td><a href="{{route('order.show',$order_price_distribution->order->id)}}"
                                            target="blank">{{$order_price_distribution->order->code_order}}</a>
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
                                                onclick="return confirm('Are you sure?')" type="submit">Deposit
                                                Sekarang</button>
                                        </form>
                                        @endif
                                        <form
                                            action="{{route('order_price_distribution.destroy',$order_price_distribution->id)}}"
                                            class="d-inline" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-xs"
                                                onclick="return confirm('Are you sure?')" type="submit">Delete</button>
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
</script>
@endpush