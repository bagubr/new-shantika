@extends('layouts.main')
@section('title')
Riwayat Pembayaran
@endsection
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Riwayat Pembayaran</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">Riwayat Pembayaran</li>
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
                        <h3 class="card-title">Table Riwayat Pembayaran</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Kode Order</th>
                                    <th>Metode Pembayaran</th>
                                    <th>Status</th>
                                    <th>Bukti Pembayaran</th>
                                    <th>Tanggal Pembayaran</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($payments as $payment)
                                <tr>
                                    <td><a href="{{route('order.show',$payment->order->id)}}">
                                            {{$payment->order->code_order}}
                                        </a>
                                    </td>
                                    <td>{{$payment->payment_type->name}}</td>
                                    <td>{{$payment->status}}</td>
                                    <td><img src="{{$payment->proof_url}}" class="image-thumbnail" height="100px"
                                            alt=""></td>
                                    <td>
                                        @if ($payment->paid_at)
                                        {{date('Y-m-d',strtotime($payment->paid_at))}}
                                        @else
                                        Tidak Ada Tanggal Pembayaran
                                        @endif
                                    </td>
                                    <td><a href="{{route('payment.edit',$payment->id)}}"
                                            class="btn btn-warning btn-xs">Edit</a>
                                        <form action="{{route('payment.destroy',$payment->id)}}" class="d-inline"
                                            method="POST">
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