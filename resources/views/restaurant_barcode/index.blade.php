@extends('layouts.main')
@section('title')
Scan Barcode
@endsection
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Scan Barcode</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">Scan Barcode</li>
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
                        <h3 class="card-title">Table Scan Barcode</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">

                                <div class="form-row">
                                    <div class="col">
                                        <label>Nama</label>
                                        <h5>{{$restaurant->name}}</h5>
                                    </div>
                                    <div class="col">
                                        <label>Nomor HP</label>
                                        <h5>{{$restaurant->phone}}</h5>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Alamat</label>
                                    <h5>{{$restaurant->address}}</h5>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div id="qr-reader" class="mx-auto"></div>
                            </div>
                            <div class="col-md-6">
                                <form action="{{route('restaurant_barcode.store',)}}" method="post">
                                    @csrf
                                    <div class="form-group">
                                        <label>Kode Order</label>
                                        <input type="text" class="form-control" readonly name="code_order"
                                            id="code_order">
                                    </div>
                                    <div class="form-group">
                                        <label>Nama Pemesan</label>
                                        <input type="text" class="form-control" readonly name="order_code"
                                            id="name_order">
                                    </div>
                                    <div class="form-group">
                                        <label>Nomor Kursi</label>
                                        <input type="text" class="form-control" readonly name="layout_chair_name"
                                            id="layout_chair_name">
                                    </div>
                                    <div class="form-group">
                                        <label>Order ID</label>
                                        <input type="text" class="form-control" readonly name="order_id" id="order_id"
                                            required>
                                    </div>
                                    <div class="text-right">
                                        <input type="submit" value="Konfirmasi" class="btn btn-success">
                                    </div>
                                </form>
                            </div>
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
        "responsive": true, "lengthChange": false, "autoWidth": false,
      }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    });
</script>
<script src="https://unpkg.com/html5-qrcode@2.0.9/dist/html5-qrcode.min.js"></script>
<script>
    function onScanSuccess(decodedText, decodedResult) {
        const str = `${decodedText}`;
        const slug = str.split('|');
        $("#code_order").val(slug[0]);
        $("#layout_chair_name").val(slug[1]);
        $.ajax({
            type: "GET",
            url: "order/find/" + slug[0],
            dataType: 'json',
            success: function (data) {
                $("#name_order").val(data.order.order_detail[0].name);
                $("#order_id").val(data.order.order_detail[0].id);
                html5QrcodeScanner.clear()
            },
            error: function (data) {
                toastr.error("Data Tidak Ditemukan")
            }
        }).catch((err) => {
            console.log(err);
        });

    }
    var html5QrcodeScanner = new Html5QrcodeScanner(
        "qr-reader", {
            fps: 10,
            qrbox: 250
        });
    html5QrcodeScanner.render(onScanSuccess);
</script>
@endpush