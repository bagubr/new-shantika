@extends('layouts.main')
@section('title')
Pemesanan
@endsection
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Pemesanan</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">Pemesanan</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Table Pemesanan</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Customer</th>
                                    <th>Kode Order</th>
                                    <th>Rute</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $order)
                                <tr>
                                    <td>{{$order->user?->name}}</td>
                                    <td>
                                        <a href="{{route('order.show',$order->id)}}">
                                            {{$order->code_order}}
                                        </a>
                                    </td>
                                    <td>{{$order->route->name}}</td>
                                    <td>{{$order->status}}</td>
                                    <td>
                                        <form action="{{route('order.destroy',$order->id)}}" class="d-inline"
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
                        {{$orders->links("pagination::bootstrap-4")}}
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Cari Pemesan</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{route('order.search')}}" method="GET">
                            <div class="form-group">
                                <label>Cari Rute</label>
                                <select name="route_id" class="form-control">
                                    <option value="">--Semua Rute--</option>
                                    @foreach ($routes as $route)
                                    @if (old('route_id') == $route->id)
                                    <option value="{{$route->id}}" selected>{{$route->name}}</option>
                                    @else
                                    <option value="{{$route->id}}">{{$route->name}}</option>
                                    @endif
                                    @endforeach
                                </select>

                            </div>
                            <div class="form-group">
                                <label for="">Cari Status</label>
                                <select name="status" class="form-control">
                                    <option value="">--Semua Status--</option>
                                    @foreach ($status as $s)
                                    @if (old('status') == $s)
                                    <option value="{{$s}}" selected>{{$s}}</option>
                                    @else
                                    <option value="{{$s}}">{{$s}}</option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="">Agent</label>
                                <select name="agent" class="form-control">
                                    <option value="">--Semua Pemesan--</option>
                                    @foreach ($agent as $a)
                                    @if (old('agent'))
                                    <option value="{{$a}}" selected>{{$a}}</option>
                                    @else
                                    <option value="{{$a}}">{{$a}}</option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="text-right">
                                <button class="btn btn-success" type="submit">Cari</button>
                            </div>
                        </form>
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
        "responsive": true, 
        "lengthChange": false, 
        "autoWidth": false,        
        "paging":   false,
        "ordering": false,
        "info":     false,
        "searching" : false,
      }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    });
</script>
@endpush