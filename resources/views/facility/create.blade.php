@extends('layouts.main')
@section('title')
Facility
@endsection
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Facility Form</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">Facility</li>
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
                    <form action="@isset($facility)
                        {{route('facility.update', $facility->id)}}
                        @endisset @empty($facility) {{route('facility.store')}} @endempty" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @isset($facility)
                        @method('PUT')
                        @endisset
                        <div class="form-group">
                            <label>Nama</label>
                            <input type="text" class="form-control" name="name" placeholder="Masukkan Nama" required
                                value="{{isset($facility) ? $facility->name : ''}}">
                        </div>
                        <div class="form-group">
                            <label>Gambar</label>
                            <input type="file" name="image" accept="image/*" class="form-control" alt="">
                            <small class="text-danger"><i class="fas fa-info-circle"></i> Pastikan ukuran gambar
                                412x412(72)dpi, agar hasil maksimal</small>
                            <br>
                            @isset($facility)
                            <a href="{{$facility->image}}" data-toggle="lightbox">
                                <img src="{{isset($facility) ? $facility->image : ''}}" class="img-thumbnail"
                                    style="height: 100px" alt="">
                            </a>
                            @endisset
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
@endpush