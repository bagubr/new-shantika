@extends('layouts.main')
@section('title')
Pengeluaran
@endsection
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Pengeluaran</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">Pengeluaran</li>
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
                    <h3 class="card-title">Detail Rute</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body" style="display: block;">
                    <div class="form-group">
                        <label>Tanggal Pembuatan Laporan</label>
                        <input type="date" class="form-control" name="name" value="{{$outcome->reported_at}}" disabled>
                    </div>
                    <div class="form-group">
                        <label>Armada</label>
                        <input type="text" class="form-control" value="{{$outcome->fleet_route?->fleet?->name}}"
                            disabled>
                    </div>
                    <div class="form-group">
                        <label>Kelas Armada</label>
                        <input type="text" class="form-control"
                            value="{{$outcome->fleet_route?->fleet?->fleetclass?->name}}" disabled>
                    </div>
                    <div class="form-group">
                        <label>Rute</label>
                        <input type="text" class="form-control" value="{{$outcome->fleet_route?->route->name}}"
                            disabled>
                    </div>
                    <a class="btn btn-success btn-sm" href="{{route('outcome.export',$id)}}">Export Excel</a>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <div class="col-md-6">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">List Pengeluaran</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body" style="display: block;">
                    <div class="timeline">
                        @foreach ($outcome->outcome_detail as $outcome_detail)
                        <div>
                            <div class="timeline-item">
                                <h3 class="timeline-header">
                                    {{$outcome_detail->name}} | {{$outcome_detail->amount}}
                                </h3>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <!-- /.card-body -->
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
                                    <a href="{{route('routes.show',$order->fleet_route?->route_id)}}">
                                        {{$order->fleet_route?->route->name}}
                                    </a>
                                </td>
                                <td>
                                    {{$order->fleet_route?->fleet?->name}}/{{$order->fleet_route?->fleet?->fleetclass?->name}}
                                </td>
                                <td>
                                    Rp. {{number_format($order->price,2)}}
                                </td>
                                <td>{{$order->status}}</td>
                                <td>{{date('Y-m-d',strtotime($order->reserve_at))}}</td>
                                <!-- <td>
                                    <a class="btn btn-primary btn-xs" href="{{route('order.show',$order->id)}}"
                                        target="_blank">Detail</a>
                                    <form action="{{route('order.destroy',$order->id)}}" class="d-inline"
                                        method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-xs"
                                            onclick="return confirm('Are you sure?')" type="submit">Delete</button>
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
@endpush