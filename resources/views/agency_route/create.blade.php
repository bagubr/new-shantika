@extends('layouts.main')
@section('title')
Rute Agen
@endsection
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Rute Agen Form</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">Rute Agen</li>
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
                    <form action="@isset($agency_route)
                        {{route('agency_route.update', $agency_route->id)}}
                    @endisset @empty($agency_route) {{route('agency_route.store')}} @endempty" method="POST">
                        @csrf
                        @isset($agency_route)
                        @method('PUT')
                        @endisset
                        <div class="form-group">
                            <label>Agent</label><span class="text-danger">*</span>
                            <select name="agency_id" class="form-control select2" id="">
                                <option value="">Pilih Agent</option>
                                @foreach ($agencies as $agency)
                                <option value="{{$agency->id}}" 
                                    @isset($agency_route) 
                                        @if ($agency->id == $agency_route->agency_id) selected
                                        @endif
                                    @endisset>{{$agency->city->name}}/{{$agency->name}}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Rute</label><span class="text-danger">*</span>
                            <select name="route_id" class="form-control select2" id="">
                                <option value="">Pilih Rute</option>
                                @foreach ($routes as $route)
                                <option value="{{$route->id}}" 
                                    @isset($agency_route) 
                                        @if ($route->id == $agency_route->route_id) selected
                                        @endif
                                    @endisset>{{$route->name}}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <a href="{{URL::previous()}}" class="btn btn-secondary">Batal</a>
                        <input type="submit" value="Submit" class="btn btn-success float-right">
                    </form>
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