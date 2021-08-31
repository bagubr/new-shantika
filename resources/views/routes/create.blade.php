@extends('layouts.main')
@section('title')
Route
@endsection
<style>
    select {
        min-width: 300px;
    }
</style>
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
<section class="content" id="app">
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
                        @isset($route)
                        <div class="form-group">
                            <label>Nama Rute</label>
                            <input type="text" class="form-control" disabled
                                value="{{isset($route)? $route->name : ""}}">
                        </div>
                        @endisset
                        <div class="form-group">
                            <label>Pilih Tujuan</label>
                            <select name="area_id" class="form-control myselect" id="area_id">
                                <option value="">--PILIH TUJUAN--</option>
                                <option value="1">JAWA</option>
                                <option value="2">JABODETABEK</option>
                            </select>
                            <small class="text-danger d-none" id="refresh"><i class="fas fa-info-circle"></i> Refresh
                                halaman
                                jika ingin
                                mengganti Tujuan</small>
                        </div>
                        <div>
                            <label>Pilih Line</label>
                            <div id="container" class="mb-3"></div>
                            <div id="container2"></div>
                            <button type="button" class="btn btn-outline-primary mb-5" id="addRow">
                                Tambah Rute
                            </button>
                        </div>
                        <div class="mt-3">
                            <a href="{{URL::previous()}}" class="btn btn-secondary">Batal</a>
                            <input type="submit" value="Submit" class="btn btn-success float-right">
                        </div>
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
<script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="{{asset('js/route/index.js')}}"></script>
<script>
    if ($('.select2').length > 0) {
                    $('.select2').select2();
                };
</script>
@endpush