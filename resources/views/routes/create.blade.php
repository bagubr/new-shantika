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
                            <label>Pilih Area</label>
                            <select v-model="area_id" @change="getData" class="form-control">
                                <option value="1">JAWA</option>
                                <option value="2">JABODETABEK</option>
                            </select>
                        </div>
                        <div v-if="test.length > 0">
                            <label>Pilih Line</label>
                            <div class="form-group" v-for="(a, index) in agency" :key="index">
                                <div class="input-group">
                                    <select class="form-control" v-model="agency[index].id" name="agency_id[]" required>
                                        <option value="">Pilih Line</option>
                                        <option :value="t.id" v-for="(t,index) in test" :key="index">
                                            (@{{t.city_name}}) @{{t.name}}
                                        </option>
                                    </select>
                                    <button v-if="index != 0" type="button" class="btn btn-outline-danger"
                                        @click="removeField(index)">Delete
                                    </button>
                                </div>
                            </div>
                            <button type="button" @click="addField" class="btn btn-outline-primary mb-5">
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
    var i = 0;
    $("#dynamic-ar").click(function () {
        ++i;
        $("#dynamicAddRemove").append(' <div class="t"> <div class="form-row"> <div class="col"> <div class="form-group"> <label>Armada</label> <select class="form-control select2" name="fleet_detail_id[]"> @foreach ($fleets as $fleet) <option value="{{$fleet->id}}"> {{$fleet->fleet?->name}}/{{$fleet->fleet?->fleetclass?->name}}/{{$fleet->nickname}} </option> @endforeach </select> </div> </div> <div class="col"> <div class="form-group"> <label>Harga</label> <div class="input-group mb-3"> <input type="number" class="form-control" name="price[]" id="jam2" placeholder="Masukkan Harga"><div class="input-group-append"> <button type="button" class="btn btn-outline-danger remove-input-field">Delete</button></div> </div> </div> </div> </div> </div>');
    });
    $(document).on('click', '.remove-input-field', function () {
        $(this).parents('.t').remove();
    });

</script>
<script>
    $(function () {
        $('.select2').select2();
        $('.select23').select2()
    })
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })
</script>
@endpush