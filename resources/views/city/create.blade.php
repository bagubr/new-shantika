@extends('layouts.main')
@section('title')
Kota
@endsection
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Kota Form</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">Kota</li>
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
                    <form action="@isset($city)
                        {{route('city.update', $city->id)}}
                    @endisset @empty($city) {{route('city.store')}} @endempty" method="POST">
                        @csrf
                        @isset($city)
                        @method('PUT')
                        @endisset
                        <div class="form-group">
                            <label>Nama</label>
                            <input type="text" class="form-control" name="name" placeholder="Masukkan Nama"
                                value="{{isset($city) ? $city->name : ''}}" required>
                        </div>
                        <div class="form-group">
                            <label>Provinsi</label>
                            <select name="province_id" class="form-control select2" required>
                                <option value="">Pilih Provinsi</option>
                                @foreach ($provinces as $province)
                                <option value="{{$province->id}}" @isset($city) @if ($province->id ===
                                    $city->province_id)
                                    selected
                                    @endif
                                    @endisset>{{$province->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Area</label>
                            <select name="area_id" class="form-control" required>
                                <option value="">Pilih Area</option>
                                @foreach ($areas as $area)
                                <option @isset($city) @if ($area->id == $city->area_id)
                                    selected
                                    @endif @endisset value="{{$area->id}}">{{$area->name}}</option>
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