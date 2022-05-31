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
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Pilih Armada</h3>
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
                                    <label>Armada</label>
                                    <select name="fleet_detail_id" id="select-armada" class="form-control select2">
                                        <option value="">--PILIH ARMADA--</option>
                                        @foreach ($fleet_details as $fleet_detail)
                                        @if (old('fleet_detail_id') == $fleet_detail->id)
                                        <option value="{{$fleet_detail->id}}" selected>
                                            {{$fleet_detail->fleet?->name}}/{{$fleet_detail->fleet?->fleetclass?->name}}
                                            ({{$fleet_detail->nickname}})
                                        </option>
                                        @elseif (@$fleet_detail_id == $fleet_detail->id)
                                        <option value="{{$fleet_detail->id}}" selected>
                                            {{$fleet_detail->fleet?->name}}/{{$fleet_detail->fleet?->fleetclass?->name}}
                                            ({{$fleet_detail->nickname}})
                                        </option>
                                        @else
                                        <option value="{{$fleet_detail->id}}">
                                            {{$fleet_detail->fleet?->name}}/{{$fleet_detail->fleet?->fleetclass?->name}}
                                            ({{$fleet_detail->nickname}})
                                        </option>
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
                            <button class="btn btn-success" type="submit">Cari</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
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
                        <label for="">Tanggal Pengeluaran</label>
                        <input type="date" class="form-control" name="reported_at"
                                        value="{{((isset($reported_at))?$reported_at:date('Y-m-d'))}}">
                        <input type="hidden" name="fleet_detail_id" value="{{@$fleet_detail_id}}">
                        @include('outcome.form')
                        <br>
                        @if (!empty($orders))
                        <button type="button" name="add" id="dynamic-ar" class="btn btn-info">Tambah
                            Pengeluaran
                        </button>
                            <input type="submit" value="Submit" class="btn btn-success float-right">
                        @endif
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
                    <table id="example1" class="table table-bordered table-striped table-responsive">
                        <thead>
                            <tr>
                                <th>Pemesan</th>
                                <th>Kode Order</th>
                                <th>Rute</th>
                                <th>Armada</th>
                                <th>Total Harga</th>
                                <th>Status</th>
                                <th>Keberangkatan -> Kedatangan</th>
                                <th>Tanggal Pemesanan</th>
                                <!-- <th>Aksi</th> -->
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order)
                            <tr>
                                <td>
                                    @if ($order->user?->agencies)
                                    <a href="{{route('user_agent.show',$order->user?->agencies?->agency_id)}}"
                                        target="_blank">
                                        {{$order->user?->name_agent}}
                                    </a>
                                    @else
                                    {{$order->order_detail[0]->name}}
                                    @endif
                                </td>
                                <td>{{$order->code_order}}</td>
                                <td>
                                    @if ($order->fleet_route)
                                    <a href="{{route('routes.show',$order->fleet_route?->route_id)}}">
                                        {{$order->fleet_route?->route?->name}}
                                    </a>
                                    @endif
                                </td>
                                <td>
                                    {{$order->fleet_route?->fleet_detail?->fleet?->name}}/{{$order->fleet_route?->fleet_detail?->fleet?->fleetclass?->name}}
                                    ({{$order->fleet_route?->fleet_detail?->nickname}})
                                </td>
                                <td>
                                    Rp. {{number_format($order->price,2)}}
                                </td>
                                <td>{{$order->status}}</td>
                                <td>{{$order->agency?->name}} -> {{$order->agency_destiny?->name}}</td>
                                <td>{{date('Y-m-d',strtotime($order->reserve_at))}}</td>
                                <!-- <td>
                                    <a class="btn btn-primary btn-xs" href="{{route('order.show',$order->id)}}"
                                        target="_blank">Detail</a>
                                    <form action="{{route('order.destroy',$order->id)}}" class="d-inline" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-xs"
                                            onclick="return confirm('Apakah Anda Yakin  Menghapus Data Ini??')"
                                            type="submit">Delete</button>
                                    </form>
                                </td> -->
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @if (Request::routeIs('order.index'))
                    {{$orders->links("pagination::bootstrap-4")}}
                    @endif
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
@endpush