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
        <div class="col-md-6">
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
                    <div class="form-group">
                        <label>Kode Pesanan</label>
                        <input type="text" class="form-control" value="{{$order->code_order}}" disabled>
                    </div>
                    <div class="form-group">
                        <label>Nama Pemesan</label>
                        <input type="text" class="form-control" value="{{$order->user->name}}" disabled>
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
                    <div class="form-group">
                        <label>Status Pembayaran</label>
                        <div class="row">
                            <div class="col">
                                <p>{{$order->payment->payment_type->name}}</p>
                            </div>
                            <div class="col">
                                @if ($order->payment->status == 'PENDING')
                                <p class="btn btn-warning">{{$order->payment->status}}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <div class="col-md-6">
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