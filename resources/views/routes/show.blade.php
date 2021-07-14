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
                        <label>Route Name</label>
                        <input type="text" class="form-control" name="name" placeholder="Enter Name"
                            value="{{$route->name}}" disabled>
                    </div>
                    <div class="form-group">
                        <label>Fleet</label>
                        <input type="text" class="form-control" value="{{$route->fleet->name}}" disabled>
                    </div>
                    <div class="form-row">
                        <div class="col">
                            <div class="form-group">
                                <label>Departure at</label>
                                <input type="time" name="departure_at" class="form-control"
                                    value="{{$route->departure_at}}" disabled>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label>Arrived at</label>
                                <input type="time" name="arrived_at" class="form-control" disabled
                                    value="{{$route->arrived_at}}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Price</label>
                        <input type="number" name="price" class="form-control" placeholder="Enter Price"
                            value="{{$route->price}}" disabled>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>
</section>
@endsection