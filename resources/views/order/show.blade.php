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
                    <div class="form-group">
                        <label for="">Rute</label>
                        <a href="{{route('routes.show',$order->route_id)}}">
                            <p>
                                {{$order->route->name}}
                            </p>
                        </a>
                    </div>
                    <div class="form-row">
                        <div class="col">
                            <div class="form-group">
                                <label>Dipesan Pada Tanggal</label>
                                <p>{{$order->reserve_at}}</p>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label>Tanggal Kadaluarsa</label>
                                <p>{{$order->expired_at}}</p>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Harga</label>
                        <h3>Rp. {{number_format($order->price,2)}}</h3>
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
                    </div>
                    <div class="text-right">
                        <a href="{{route('payment.edit',$order->payment?->id)}}" target="_blank"
                            class="btn btn-primary">Ubah Status</a>
                    </div>
                    @if ($order->payment?->proof)
                    <img src="{{$order->payment?->proof}}" class="" alt="">
                    @endif
                    @else
                    <h5>Belum Ada Transaksi</h5>
                    @endif
                </div>
            </div>
            @if ($order_price_distributions)
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
                                        <button class="badge badge-primary" onclick="return confirm('Are you sure?')"
                                            type="submit">Deposit
                                            Sekarang</button>
                                    </form>
                                    @endif
                                    {{-- <form
                                        action="{{route('order_price_distribution.destroy',$order_price_distributions->id)}}"
                                    class="d-inline" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="badge badge-danger" onclick="return confirm('Are you sure?')"
                                        type="submit">Delete</button>
                                    </form> --}}
                                </td>

                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>
        <div class="col-md-5">
            @foreach ($order_details as $order_detail)
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Detail Penumpang {{$order_detail->name}}</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body" style="display: block;">
                    <div class="form-group">
                        <label>Nama Penumpang</label>
                        <input type="text" class="form-control" name="name" value="{{$order_detail->name}}" disabled>
                    </div>
                    <div class="form-row">
                        <div class="col">
                            <div class="form-group">
                                <label>Nomor Hp</label>
                                <p>{{$order_detail->phone}}</p>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label>Email</label>
                                <p>{{$order_detail->email}}</p>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label>Nomor Kursi</label> <br>
                                <button
                                    class="text-capitalize box btn btn-primary text-center">{{$order_detail->chair->name}}</button>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col">
                            <div class="form-group">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" @if ($order_detail->is_feed == 1)
                                    checked
                                    @endif disabled="">
                                    <label class="form-check-label">Makan</label>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" @if ($order_detail->is_travel == 1)
                                    checked
                                    @endif disabled="">
                                    <label class="form-check-label">Travel</label>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" @if ($order_detail->is_member == 1)
                                    checked
                                    @endif disabled="">
                                    <label class="form-check-label">Member</label>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
            @endforeach
        </div>
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