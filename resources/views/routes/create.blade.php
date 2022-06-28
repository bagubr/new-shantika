@extends('layouts.main')
@section('title')
<<<<<<< HEAD
Route
=======
Rute
>>>>>>> rilisv1
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
<<<<<<< HEAD
                <h1>Route Form</h1>
=======
                <h1>Rute Form {{\App\Models\Area::find($area_id)->name}}</h1>
>>>>>>> rilisv1
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
<<<<<<< HEAD
                    <li class="breadcrumb-item active">Route</li>
=======
                    <li class="breadcrumb-item active">Rute</li>
>>>>>>> rilisv1
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
<<<<<<< HEAD
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
                                @foreach(\App\Models\Area::get() as $area)
                                <option value="{{ $area->id }}">{{ $area->name }}</option>
                                @endforeach
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
=======
                    <div class="row">
                        <div class="col-md-6">
                            <form action="{{route('routes.store', ['area_id'=>$area_id])}}" method="POST"> @csrf
                                @isset($route)
                                <div class="form-group">
                                    <input type="hidden" class="form-control" name="route_id" value="{{isset($route)? $route->id : ""}}">
                                    <label>Nama Rute</label>
                                    <input type="text" class="form-control" disabled value="{{isset($route)? $route->name : ""}}">
                                </div>
                                @endisset
                                <div class="form-group">
                                    <label>Agen</label>
                                    <select required class="form-control select2" name="agency_id" style="width: 100%;">
                                        <option value="">Pilih Agen</option>
                                        @foreach ($agencies as $agency)
                                        <option value="{{$agency->id}}">
                                            {{$agency->city->name}}/{{$agency->name}}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Urutan</label>
                                    <input required type="number" min="1" class="form-control" name="order">
                                </div>
                                @unlessrole('owner')
    
                                <input type="submit" value="Submit" class="btn btn-success float-right">
                                @endunlessrole
                            </form>
                        </div>
                        <div class="col-md-6">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Urutan</th>
                                        <th>Agen</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @isset($route)
                                    @foreach ($route->checkpoints as $checkpoint)
                                    <tr>
                                        <td>{{$checkpoint->order}}</td>
                                        <td><a
                                            href="{{route('agency.edit',$checkpoint->agency_id)}}">{{$checkpoint->agency?->city?->name}}/{{$checkpoint->agency?->name}}</a>
                                        </td>
                                        <td>
                                            @unlessrole('owner')

                                            <form action="{{route('checkpoint.destroy',$checkpoint->id)}}" class="d-inline"
                                                method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-danger btn-xs"
                                                    onclick="return confirm('Apakah Anda Yakin  Menghapus Data Ini??')"
                                                    type="submit">Delete</button>
                                                </form>
                                            @endunlessrole
                                        </td>
                                    </tr>
                                    @endforeach
                                    @endisset
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{route('routes.index', ['area_id' => $area_id])}}" class="btn btn-secondary">Batal</a>
                    </div>
>>>>>>> rilisv1
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