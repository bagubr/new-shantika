@extends('layouts.main')
@section('title')
Agen
@endsection
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Agen Form</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">Agen</li>
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
                    <form action="@isset($agency)
                        {{route('agency.update', $agency->id)}}
                    @endisset @empty($agency) {{route('agency.store')}} @endempty" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @isset($agency)
                        @method('PUT')
                        @endisset
                        <div class="form-group">
                            <label for="inputName">Agen Nama</label>
                            <input type="text" id="inputName" class="form-control" name="name"
                                placeholder="Masukkan Nama" value="{{isset($agency) ? $agency->name : ''}}">
                        </div>
                        <div class="form-group">
                            <label>Kota</label>
                            <select class="form-control select2" name="city_id" style="width: 100%;">
                                <option value="">Pilih Kota</option>
                                @foreach ($cities as $city)
                                <option value="{{$city->id}}" @isset($agency) @if ($city->id ===
                                    $agency->city_id)
                                    selected
                                    @endif @endisset>{{$city->name}}
                                </option>
                                @endforeach
                            </select>
                            {{-- <span><a href="{{route('fleets.create')}}">Add Fleet</a></span> --}}
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