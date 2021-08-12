@extends('layouts.main')
@section('title')
Pengeluran
@endsection
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Pengeluran Form</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">Pengeluran</li>
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
                    <h3 class="card-title">Form Pengeluaran</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body" style="display: block;">
                    @include('partials.error')
                    <form action="{{route('outcome.store')}}" method="POST">
                        @csrf
                        <input type="hidden" name="route_id" value="{{@$route_id}}">
                        <input type="hidden" name="reported_at" value="{{((@$reported_at)?$reported_at:date('Y-m-d'))}}">
                        @include('outcome.form')
                        <br>
                        <button type="button" name="add" id="dynamic-ar" class="btn btn-info">Tambah
                            Pengeluaran
                        </button>
                        <input type="submit" value="Submit" class="btn btn-success float-right">
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Cari Pemesan</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{route('outcome.search')}}" method="GET">
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Cari Rute</label>
                                    <select name="route_id" id="select-rute" class="form-control" required>
                                        <option value="WITH_TYPE" {{(@$route_id == 'WITH_TYPE')?'selected':''}}>--Pengeluaran Lain--</option>
                                        @foreach ($routes as $route)
                                        @if (@$route_id == $route->id)
                                        <option value="{{$route->id}}" selected>{{$route->name}}</option>
                                        @else
                                        <option value="{{$route->id}}">{{$route->name}}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>Tanggal</label>
                                    <input type="date" class="form-control" name="reported_at"
                                        value="{{((isset($reported_at))?$reported_at:date('Y-m-d'))}}">
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <button class="btn btn-success d-none" id="button-cari" type="submit">Cari</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Table Pemesanan</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Pemesan</th>
                                <th>Kode Order</th>
                                <th>Rute</th>
                                <th>Armada</th>
                                <th>Total Harga</th>
                                <th>Status</th>
                                <th>Tanggal Pemesanan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order)
                            <tr>
                                <td>
                                    {{$order->order_detail[0]->name}}
                                </td>
                                <td>{{$order->code_order}}</td>
                                <td>
                                    <a href="{{route('routes.show',$order->route?->id)}}" target="_blank">
                                        {{$order->route?->name}}
                                    </a>
                                </td>
                                <td>
                                    {{$order->route?->fleet?->name}}/{{$order->route?->fleet?->fleetclass?->name}}
                                </td>
                                <td>
                                    Rp. {{number_format($order->price,2)}}
                                </td>
                                <td>{{$order->status}}</td>
                                <td>{{date('Y-m-d',strtotime($order->reserve_at))}}</td>
                                <td>
                                    <a class="badge badge-primary" href="{{route('order.show',$order->id)}}"
                                        target="_blank">Detail
                                        Pemesanan</a>
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
    </div>
</section>
@endsection
@push('script')
<script type="text/javascript">
    var i = 0;
    $("#dynamic-ar").click(function () {
        ++i;
        $("#dynamicAddRemove").append(`
        <div class="t">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Pengeluaran Lain</label>
                        <input type="text" class="form-control" name="name[]" placeholder="Masukkan Nama"
                        value="{{isset($name) ? $name : ''}}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Amount</label>
                        <input type="text" class="form-control" name="amount[]" placeholder="Masukkan Amount"
                        value="{{isset($amount) ? $amount : ''}}">
                    </div>
                </div>
            </div>
        <button type="button" class="btn btn-outline-danger remove-input-field">Delete</button>
        </div>`
        );
    });
    $(document).on('click', '.remove-input-field', function () {
        $(this).parents('.t').remove();
    });
</script>
<script>
    $('#select-rute').on('change', function() {
        var rute = this.value;
        if(rute == 'WITH_TYPE'){
            console.log(rute);
            $('#button-cari').addClass('d-none');
        }else{
            $('#button-cari').removeClass('d-none');
        }
    });
</script>
@endpush