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
        <div class="col-md-6">
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
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Route</label>
                                    <select name="route_id" class="form-control" readonly>
                                        <option value="{{$fleet_route->route_id}}">{{$fleet_route->route?->name}}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>Armada</label>
                                    <select name="fleet_id" class="form-control" readonly>
                                        <option value="{{$fleet_route->fleet_id}}">{{$fleet_route->fleet?->name}}
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Harga</label>
                            <input type="number" name="price" class="form-control" value="{{$fleet_route->price}}">
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
                        <input type="submit" value="Tambahkan" class="btn btn-success float-right">
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Delectus repellat officiis alias nobis praesentium
            expedita amet voluptates iste iure veritatis, similique tenetur odio assumenda, voluptatum nulla sequi nisi,
            ipsum quaerat.
        </div>
        <div class="col-md-12">
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
                    <table></table>
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
@endpush