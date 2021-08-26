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
                            <select v-model="area_id" @input="getData()" class="form-control">
                                <option value="1">JAWA</option>
                                <option value="2">JABODETABEK</option>
                            </select>
                        </div>
                        <div id="dynamicAddRemove2">
                            <div class="t2">
                                <select class="form-control" v-model="agency" name="agency[]">
                                    <option value="">Pilih Area</option>
                                    <option :value="t.id" v-for="(t,index) in test" :key="index">
                                        @{{t.name}}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <button type="button" name="add2" id="dynamic-ar2" class="btn btn-outline-primary">Tambah
                            Armada
                        </button>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Kota Keberangkatan</label>
                                    <select name="departure_city_id" class="form-control select2" required>
                                        <option value="">Pilih Kota Keberangkatan</option>
                                        @foreach ($cities as $city)
                                        <option value="{{$city->id}}" @isset($route) @if ($city->id ==
                                            $route->departure_city_id)
                                            selected
                                            @endif @endisset>{{$city->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>Kota Kedatangan</label>
                                    <select name="destination_city_id" class="form-control select2" required>
                                        <option value="">Pilih Kota Tujuan</option>
                                        @foreach ($cities as $city)
                                        <option @isset($route) @if ($city->id == $route->destination_city_id)
                                            selected
                                            @endif @endisset value="{{$city->id}}">{{$city->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Waktu Berangkat</label>
                                    <input type="time" class="form-control" name="departure_at" @isset($route)
                                        value="{{$route->departure_at}}" @endisset required>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>Waktu Kedatangan</label>
                                    <input type="time" class="form-control" name="arrived_at" @isset($route)
                                        value="{{$route->arrived_at}}" @endisset required>
                                </div>
                            </div>
                        </div>
                        @isset($name)
                        @if ($name == 'routes.create')
                        <div id="dynamicAddRemove">
                            <div class="t">
                                <div class="form-row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label>Armada</label>
                                            <select class="form-control select2" name="fleet_id[]">
                                                @foreach ($fleets as $fleet)
                                                <option value="{{$fleet->id}}">{{$fleet->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label>Harga</label>
                                            <div class="input-group mb-3">
                                                <input type="number" class="form-control" name="price[]" id="jam2"
                                                    placeholder="Masukkan Harga">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="button" name="add" id="dynamic-ar" class="btn btn-outline-primary">
                            Tambah Armada
                        </button>
                        @endif
                        @endisset
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
    var i = 0;
    $("#dynamic-ar").click(function () {
        ++i;
        $("#dynamicAddRemove").append(' <div class="t"> <div class="form-row"> <div class="col"> <div class="form-group"> <label>Armada</label> <select class="form-control select2" name="fleet_id[]"> @foreach ($fleets ?? '' as $fleet) <option value="{{$fleet->id}}">{{$fleet->name}}</option> @endforeach </select> </div> </div> <div class="col"> <div class="form-group"> <label>Harga</label> <div class="input-group mb-3"> <input type="number" class="form-control" name="price[]" id="jam2" placeholder="Masukkan Harga"> <div class="input-group-append"> <button type="button" class="btn btn-outline-danger remove-input-field">Delete</button> </div> </div> </div> </div> </div> </div>');
    });
    $(document).on('click', '.remove-input-field', function () {
        $(this).parents('.t').remove();
    });

</script>
<script>
    var i = 0;
    $("#dynamic-ar2").click(function () {
        ++i;
        $("#dynamicAddRemove2").append('  <div class="t2"> <select class="form-control" v-model="agency"> <option value="">Pilih Area</option> <option :value="t.id" v-for="(t,index) in test" :key="index"> @{{t.name}} </option> </select> </div>');
    });
    $(document).on('click', '.remove-input-field', function () {
        $(this).parents('.t2').remove();
    });
</script>
<script>
    $(function () {
        $('.select2').select2()
    })
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })
</script>
@endpush