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
                <h1 class="m-0">Riwayat Pembayaran Customer</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">Riwayat Pembayaran Customer</li>
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
                    <form action="">
                        <div class="card-body">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Metode Pembayaran</label>
                                    <select name="payment_type_id" class="form-control select2">
                                        <option value="">--PILIH METODE PEMBAYARAN--</option>
                                        @foreach ($payment_types as $payment_type)
                                        @if (old('payment_type_id') == $payment_type->id)
                                        <option value="{{$payment_type->id}}" selected>
                                            {{$payment_type->name}}
                                        </option>
                                        @else
                                        <option value="{{$payment_type->id}}">
                                            {{$payment_type->name}}
                                        </option>
                                        @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Status</label>
                                    <select name="status" class="form-control select2">
                                        <option value="">--PILIH STATUS--</option>
                                        @foreach ($statuses as $s)
                                        @if (old('status') == $s)
                                        <option value="{{$s}}" selected>
                                            {{$s}}
                                        </option>
                                        @else
                                        <option value="{{$s}}">
                                            {{$s}}
                                        </option>
                                        @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="text-right">
                                <button class="btn btn-success" type="submit">Cari</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Table Riwayat Pembayaran Customer</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="table-responsive">

                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Kode Order</th>
                                        <th>Metode Pembayaran</th>
                                        <th>Nominal</th>
                                        <th>Status</th>
                                        <th>Bukti Pembayaran</th>
                                        <th>Tanggal Pembayaran</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($payments as $payment)
                                    <tr>
                                        <td>
                                            {{$payment->order->code_order}}
                                        </td>
                                        <td>{{$payment->payment_type->name}}</td>
                                        <td>Rp. {{number_format($payment->order?->price)}}</td>
                                        <td>{{$payment->status}}</td>
                                        <td>
                                            @if ($payment->proof)
                                            <a href="{{$payment->proof_url}}" data-toggle="lightbox">
                                                <img src="{{$payment->proof_url}}" class="image-thumbnail"
                                                    height="100px">
                                            </a>
                                            @else
                                            Tidak Ada Bukti Pembayaran
                                            @endif
                                        </td>
                                        <td>
                                            @if ($payment->paid_at)
                                            {{date('Y-m-d',strtotime($payment->paid_at))}}
                                            @else
                                            Tidak Ada Tanggal Pembayaran
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{route('order.show', $payment->order->id)}}"
                                                class="btn btn-primary btn-xs">
                                                Detail
                                            </a>
                                            @if ($payment->status == 'WAITING_CONFIRMATION')
                                            <a href="{{route('payment.edit',$payment->id)}}"
                                                class="btn btn-warning btn-xs">Edit</a>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="ml-auto">
                                <div>
                                    {{$payments->appends(Request::all())->links() }}
                                </div>
                            </div>
                        </div>
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