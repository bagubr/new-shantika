@extends('layouts.main')
@section('title')
Route
@endsection
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Route Form</h1>
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
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Form</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body" style="display: block;">
                    @include('partials.error')
                    <form action="@isset($route)
                        {{route('routes.update', $route->id)}}
                    @endisset @empty($route) {{route('routes.store')}} @endempty" method="POST">
                        @csrf
                        @isset($route)
                        @method('PUT')
                        @endisset
                        <div class="form-group">
                            <label>Route Name</label>
                            <input type="text" class="form-control" name="name" placeholder="Enter Name"
                                value="{{isset($route) ? $route->name : ''}}">
                        </div>
                        <div class="form-group">
                            <label>Fleet</label>
                            <select class="form-control select2" name="fleet_id" style="width: 100%;">
                                <option value="">Select Fleet</option>
                                @foreach ($fleets as $fleet)
                                <option value="{{$fleet->id}}" @isset($route) @if ($fleet->id ===
                                    $route->fleet_id)
                                    selected
                                    @endif @endisset>{{$fleet->name}}
                                </option>
                                @endforeach
                            </select>
                            <span><a href="{{route('fleets.create')}}">Add Fleet</a></span>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Departure at</label>
                                    <input type="time" name="departure_at" class="form-control"
                                        value="{{isset($route) ? $route->departure_at : ''}}">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>Arrived at</label>
                                    <input type="time" name="arrived_at" class="form-control"
                                        value="{{isset($route) ? $route->arrived_at : ''}}">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Price</label>
                            <input type="number" name="price" class="form-control" placeholder="Enter Price"
                                value="{{isset($route) ? $route->price : ''}}">
                        </div>
                        <a href="{{URL::previous()}}" class="btn btn-secondary">Batal</a>
                        <input type="submit" value="Submit" class="btn btn-success float-right">
                    </form>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
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
@endpush