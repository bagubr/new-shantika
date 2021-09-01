@extends('layouts.main')
@section('title')
Social Detail
@endsection
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Social Detail Form</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">Social Detail</li>
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
                    <form action="@isset($social_media)
                        {{route('social_media.update', $social_media->id)}}
                    @endisset @empty($social_media) {{route('social_media.store')}} @endempty" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @isset($social_media)
                        @method('PUT')
                        @endisset
                        <div class="form-group">
                            <label>Nama</label>
                            <input type="text" class="form-control" name="name"
                                value="{{isset($social_media) ? $social_media->name : ''}}" placeholder="Masukkan Nama"
                                required>
                        </div>
                        <div class="form-group">
                            <label>Link</label>
                            <input type="text" class="form-control" name="value"
                                value="{{isset($social_media) ? $social_media->value : ''}}" required
                                placeholder="Masukkan Link">
                        </div>
                        <div class="form-group">
                            <label>Icon</label>
                            <input type="file" name="icon" class="form-control" id="" accept="image/*">
                            <small class="text-danger"><i class="fas fa-info-circle"></i> Pastikan ukuran gambar
                                312x312(72)dpi, agar hasil maksimal</small>
                            <br>
                            @isset($social_media)
                            <a href="{{$social_media->icon}}" data-toggle="lightbox">
                                <img src="{{isset($social_media) ? $social_media->icon : ''}}" class="img-thumbnail"
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
@section('script')
<script>
    $(function () {
        $('.select2').select2()
    })
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })
</script>
@endsection