@extends('layouts.main')
@section('title')
Pesanan
@endsection
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Pesanan {{$order->code_order}}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">Pesanan</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>
<section class="content">
    <div class="row">
        <div class="col-md-7">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Pesanan</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body" style="display: block;">
                    <div class="form-row">
                        <div class="col">
                            <div class="form-group">
                                <label>Kode Pesanan</label>
                                <input type="text" class="form-control" value="{{$order->code_order}}" disabled>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label>Nama Pemesan</label>
                                <input type="text" class="form-control" value="{{$order->user->name ?? ''}}" disabled>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col">
                            <div class="form-group">
                                <label>Rute</label>
                                @if ($order->fleet_route)
                                <a href="{{route('routes.show',$order->fleet_route?->route_id)}}">
                                    <p>
                                        {{$order->fleet_route?->route?->name}}
                                    </p>
                                </a>
                                @endif
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label>Armada</label>
                                @if ($order->fleet_route)
                                <a href="{{route('fleet_route.show',$order->fleet_route?->id)}}">
                                    <p>
                                        {{$order->fleet_route?->fleet_detail?->fleet?->name}}/{{$order->fleet_route?->fleet_detail?->fleet?->fleetclass?->name}}
                                        ({{$order->fleet_route?->fleet_detail?->nickname}})
                                    </p>
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col">
                            <div class="form-group">
                                <label>Dipesan Pada Tanggal</label>
                                <p>{{$order->created_at}}</p>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label>Tanggal Kadaluarsa</label>
                                <p>{{$order->expired_at}}</p>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label>Tanggal Keberangkatan</label>
                                <p>{{date('Y-m-d', strtotime($order->reserve_at))}}</p>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col">
                            <div class="form-group">
                                <label>Harga</label>
                                <h3>Rp. {{number_format($order->price,2)}}</h3>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label>Sumber Pemesan</label>
                                @if ($order->user?->agencies)
                                <h5>{{$order->user?->agencies?->agent?->name}}</h5>
                                @else
                                <h5>Umum</h5>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="border-bottom"></div>
                    @if ($order->payment)
                    <div class="form-row">
                        <div class="col">
                            <div class="form-group">
                                <label>Tipe Pembayaran</label>
                                <input type="text" class="form-control"
                                    value="{{$order->payment?->payment_type?->name}}" disabled>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label>Status Pembayaran</label>
                                <select name="" id="" class="form-control" disabled>
                                    @foreach ($statuses as $status)
                                    <option value="{{$status}}" @if ($status==$order->payment?->status)
                                        selected
                                        @endif>{{$status}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @if ($order->payment?->status != 'DECLINED')
                        <div class="col">
                            <div class="form-group">
                                <label>Dibayar Pada</label>
                                <input type="date" value="{{date('Y-m-d',strtotime($order->payment?->paid_at))}}"
                                    disabled class="form-control">
                            </div>
                        </div>
                        @endif
                    </div>
                    @if ($order->payment?->status == 'DECLINED')
                    <div class="form-group">
                        <textarea disabled class="form-control">{{$order->payment?->proof_decline_reason}}</textarea>
                    </div>
                    @endif
                    <img src="{{$order->payment?->proof_url}}" class="" style="height:100px" alt="">
                    <div class="text-right">
                        @if ($order->payment?->status == 'WAITING_CONFIRMATION' || $order->payment?->status ==
                        'PENDING')
                        <a href="{{route('payment.edit',$order->payment?->id)}}" class="btn btn-primary">Ubah Status</a>
                        @endif
                    </div>
                    @elseif ($order->payment?->payment_type?->id == 1 )
                    <h5>Pembayaran Otomatis</h5>
                    @endif
                </div>
            </div>

        </div>
        <div class="col-md-5">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Detail Penumpang {{$order->order_detail[0]?->name}}</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body" style="display: block;">
                    <div class="form-group">
                        <label>Nama Penumpang</label>
                        <input type="text" class="form-control" name="name" value="{{$order->order_detail[0]?->name}}"
                            disabled>
                    </div>
                    <div class="form-row">
                        <div class="col">
                            <div class="form-group">
                                <label>Nomor Hp</label>
                                <p>{{$order->order_detail[0]?->phone}}</p>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label>Email</label>
                                <p>{{$order->order_detail[0]?->email}}</p>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label>Nomor Kursi</label> <br>
                                <div class="row">
                                    @foreach ($order_details as $order_detail)
                                    <div class="col mb-1">
                                        <button
                                            class="text-capitalize box btn btn-primary text-center">{{$order_detail->chair->name}}</button>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col">
                            <div class="form-group">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox"
                                        @if($order->order_detail[0]?->is_feed == 1)
                                    checked
                                    @endif disabled="">
                                    <label class="form-check-label">Makan</label>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox"
                                        @if($order->order_detail[0]?->is_travel == 1)
                                    checked
                                    @endif disabled="">
                                    <label class="form-check-label">Travel</label>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox"
                                        @if($order->order_detail[0]?->is_member == 1) checked
                                    @endif disabled="">
                                    <label class="form-check-label">Member</label>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if (!$order->user?->agencies || $order->status != 'FINISHED')
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Reschedule Jadwal</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body" style="display: block;">
                    <form action="{{route('order.update_jadwal',$order->id)}}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label>Tanggal</label>
                            <input type="date" value="{{date('Y-m-d',strtotime($order->reserve_at))}}"
                                class="form-control" required name="reserve_at">
                        </div>
                        <div class="form-group">
                            <label>Masukkan Password Akun Anda</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="text-right">
                            <input type="submit" class="btn btn-primary" value="Ubah Tanggal">
                        </div>
                    </form>
                </div>
            </div>
            @elseif($order->user?->agencies || $order->status != 'FINISHED')
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Batalkan Tiket</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body" style="display: block;">
                    @if ($order->status == 'CANCELED')
                    <h5 class="card-title">Orderan Dibatalkan</h5>
                    <p class="card-text text-bold">{{$order->cancelation_reason}}</p>
                    @else
                    <form action="{{route('order.cancelation',$order->id)}}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label>Alasan Pembatalan</label>
                            <textarea class="form-control" name="cancelation_reason" required></textarea>
                        </div>
                        <div class="form-group">
                            <label>Masukkan Password Akun Anda</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="text-right">
                            <input type="submit" class="btn btn-danger"
                                onclick="return confirm('Apakah Anda Yakin Untuk Membatalkan Orderan Ini?')"
                                value="Batalkan Order">
                        </div>
                    </form>
                    @endif
                </div>
            </div>
            @endif
        </div>
        @if ($order_price_distributions)
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Pembagian Hasil</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body" style="display: block;">
                    <table class="table">
                        <thead>
                            <th>Harga Tiket</th>
                            <th>Makan</th>
                            <th>Travel</th>
                            <th>Member</th>
                            <th>Agent</th>
                            <th>Owner</th>
                            <th>Deposit</th>
                            <th>Aksi</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Rp. {{number_format($order_price_distributions->ticket_only)}}</td>
                                <td>Rp. {{number_format($order_price_distributions->for_food)}}</td>
                                <td>Rp. {{number_format($order_price_distributions->for_travel)}}</td>
                                <td>Rp. {{number_format($order_price_distributions->for_member)}}</td>
                                <td>Rp. {{number_format($order_price_distributions->for_agent)}}</td>
                                <td>Rp. {{number_format($order_price_distributions->for_owner)}}</td>
                                <td>
                                    @if ($order_price_distributions->deposited_at)
                                    {{date('Y-m-d', strtotime($order_price_distributions->deposited_at))}}
                                    @else
                                    Belum Deposit

                                    @endif
                                </td>
                                <td> @if (!$order_price_distributions->deposited_at)
                                    <form
                                        action="{{route('order_price_distribution.update', $order_price_distributions->id)}}"
                                        class="d-inline" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <button class="badge badge-primary"
                                            onclick="return confirm('Apakah Anda Yakin  Menghapus Data Ini??')"
                                            type="submit">Deposit
                                            Sekarang</button>
                                    </form>
                                    @endif
                                    {{-- <form
                                        action="{{route('order_price_distribution.destroy',$order_price_distributions->id)}}"
                                    class="d-inline" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="badge badge-danger"
                                        onclick="return confirm('Apakah Anda Yakin  Menghapus Data Ini??')"
                                        type="submit">Delete</button>
                                    </form> --}}
                                </td>

                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>

</section>
@endsection
@push('script')
<script>
    $(function () {
        $('.select2').select2()
    })
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })
</script>
<script>
    $(function () {
      $("#example1").DataTable({
        "responsive": true, "lengthChange": false, "autoWidth": false,
      }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    });
</script>
@endpush