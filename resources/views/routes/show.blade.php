@extends('layouts.main')
@section('title')
Route
@endsection
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Route {{$route->name}}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">Route</li>
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
                    <h3 class="card-title">{{$route->name}}</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body" style="display: block;">
                    <div class="form-group">
                        <label>Nama Rute</label>
                        <input type="text" class="form-control" name="name" placeholder="Masukkan Nama"
                            value="{{$route->name}}" disabled>
                    </div>
                    <div class="form-group">
                        <label>Armada</label>
                        <input type="text" class="form-control" value="{{$route->fleet->name}}" disabled>
                    </div>
                    <div class="form-row">
                        <div class="col">
                            <div class="form-group">
                                <label>Keberangkatan</label>
                                <input type="time" name="departure_at" class="form-control"
                                    value="{{$route->departure_at}}" disabled>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label>Kedatangan</label>
                                <input type="time" name="arrived_at" class="form-control" disabled
                                    value="{{$route->arrived_at}}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Harga</label>
                        <input type="number" name="price" class="form-control" placeholder="Masukkan Harga"
                            value="{{$route->price}}" disabled>
                    </div>
                    <div class="form-group">
                        <label>Area</label>
                        <input type="text" class="form-control" value="{{$route->area->name}}" disabled>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <div class="col-md-6">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">{{$route->name}}</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body" style="display: block;">
                    <div class="timeline">
                        <div class="time-label">
                            <span class="bg-warning">Keberangkatan</span>
                        </div>
                        @foreach ($checkpoints as $checkpoint)
                        <div class="time-label">
                            <span class="bg-primary">{{$checkpoint->arrived_at}}</span>
                        </div>
                        <div>
                            <i class="fas fa-bus bg-blue"></i>
                            <div class="timeline-item">
                                <h3 class="timeline-header"><span class="time"><i class="fas fa-clock"></i>
                                        {{$checkpoint->agency->name}} | {{$checkpoint->agency->city->name}}</span>
                                </h3>
                            </div>
                        </div>
                        @endforeach
                        <div class="time-label">
                            <span class="bg-success">Kedatangan</span>
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Checkpoint Form</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body" style="display: block;">
                    @include('partials.error')
                    <form action="{{route('checkpoint.store')}}" method="POST">
                        @csrf
                        <input type="hidden" value="{{$route->id}}" name="route_id">
                        <div class="form-group">
                            <label>Agen</label>
                            <select class="form-control select2" name="agency_id" style="width: 100%;">
                                <option value="">Pilih Agen</option>
                                @foreach ($agencies as $agency)
                                <option value="{{$agency->id}}">
                                    {{$agency->name}}
                                </option>
                                @endforeach
                            </select>
                            <div class="form-group">
                                <label for="">Arrived At</label>
                                <input type="time" class="form-control" name="arrived_at">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">Order</label>
                            <input type="number" class="form-control" name="order">
                        </div>
                        <input type="submit" value="Submit" class="btn btn-success float-right">
                    </form>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Checkpoint</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body" style="display: block;">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Order</th>
                                <th>Agen</th>
                                <th>Arrived At</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($checkpoints as $checkpoint)
                            <tr>
                                <td>{{$checkpoint->order}}</td>
                                <td><a
                                        href="{{route('agency.edit',$checkpoint->agency_id)}}">{{$checkpoint->agency->name}}/{{$checkpoint->agency->city->name}}</a>
                                </td>
                                <td>{{$checkpoint->arrived_at}}</td>
                                <td>
                                    <form action="{{route('checkpoint.destroy',$checkpoint->id)}}" class="d-inline"
                                        method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-xs" onclick="return confirm('Are you sure?')"
                                            type="submit">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
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