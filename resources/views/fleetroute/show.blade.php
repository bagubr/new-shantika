@extends('layouts.main')
@section('title')
Rute Armada
@endsection
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Detail Rute Armada</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active"><a href="{{route('fleet_route.index')}}">Rute Armada</a></li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>
<section class="content">
    <div class="row">
        <div class="col-md-4">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Edit</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body" style="display: block;">
                    @include('partials.error')
                    <form action="@isset($fleet_route)
                        {{route('fleet_route.update', $fleet_route->id)}}
                    @endisset @empty($fleet_route) {{route('fleet_route.store')}} @endempty" method="POST">
                        @csrf
                        @isset($fleet_route)
                        @method('PUT')
                        @endisset
                        <div class="form-group">
                            <label>Route</label>
                            <select name="route_id" class="form-control" readonly>
                                <option value="{{$fleet_route->route_id}}">{{$fleet_route->route?->name}}
                                </option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Armada</label>
                            <select name="fleet_id" class="form-control" readonly>
                                <option value="{{$fleet_route->fleet_detail_id}}">
                                    {{$fleet_route->fleet_detail?->fleet?->name}}/{{$fleet_route->fleet_detail?->fleet?->fleetclass?->name}}
                                    ({{$fleet_route->fleet_detail?->nickname}})
                                </option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Area</label>
                            <input type="text" readonly class="form-control"
                                value="{{$fleet_route->route?->checkpoints[0]?->agency?->city?->area?->name}}">
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select name="is_active" class="form-control">
                                @foreach ($statuses as $status => $key)
                                <option value="{{$status}}" @if ($status==$fleet_route->is_active)
                                    selected
                                    @endif>{{$key}}</option>
                                @endforeach
                            </select>
                        </div>
                        <input type="submit" value="Ubah" class="btn btn-success float-right">
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Pesanan</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Kode Order</th>
                                <th>Tanggal</th>
                                <th>Akun</th>
                                <th>Pemesan</th>
                                <th>Jumlah Pesanan</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order)
                            <tr>
                                <td>
                                    <a href="{{route('order.show',$order->id)}}">
                                        {{$order->code_order}}
                                    </a>
                                </td>
                                <td>{{date('Y-m-d',strtotime($order->reserve_at))}}</td>
                                <td>
                                    @if ($order->user?->agencies)
                                    <a href="{{route('user_agent.show',$order->user_id)}}" target="_blank">
                                        {{$order->user?->name_agent}}
                                    </a>
                                    @elseif ($order->user)
                                    <a href="{{route('user.edit', $order->user_id)}}">
                                        {{$order->user?->name}}
                                    </a>
                                    @else
                                    Tanpa Akun
                                    @endif
                                </td>
                                <td>{{$order->order_detail[0]->name}}</td>
                                <td>{{$order->order_detail->count()}}</td>
                                <td>{{$order->status}}</td>
                                <td>
                                    <a href="{{route('order.show',$order->id)}}" target="_blank"
                                        class="btn btn-primary btn-xs">Detail</a>
                                    <form action="{{route('order.destroy',$order->id)}}" class="d-inline" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-xs"
                                            onclick="return confirm('Apakah Anda yakin akan menghapus data order?')"
                                            type="submit">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@push('script')
<script src="{{asset('plugins/summernote/summernote-bs4.min.js')}}"></script>
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