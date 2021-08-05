@extends('layouts.main')
@section('title')
Tentang Kita
@endsection
@push('css')
<link rel="stylesheet" href="{{asset('plugins/summernote/summernote-bs4.min.css')}}">
@endpush
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Tentang Kita Form</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">Tentang Kita</li>
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
                    <form action="@isset($about)
                        {{route('about.update', $about->id)}}
                    @endisset @empty($about) {{route('about.store')}} @endempty" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @isset($about)
                        @method('PUT')
                        @endisset
                        <div class="form-group">
                            <label>Deskripsi</label>
                            <textarea name="description" id="summernote"
                                class="form-control">{{isset($about) ? $about->description : ''}}</textarea>
                        </div>
                        <div class="form-group">
                            <label>Alamat</label>
                            <input type="text" class="form-control" name="address" placeholder="Masukkan Alamat"
                                value="{{isset($about) ? $about->address : ''}}">
                        </div>
                        <div class="form-group">
                            <label>Gambar</label>
                            <input type="file" name="image" class="form-control" accept="image/*" id="">
                        </div>
                        <div class="form-group">
                            <a href="{{isset($about) ? $about->image : ''}}" data-toggle="lightbox">
                                <img src="{{isset($about) ? $about->image : ''}}" style="height: 100px" alt="">
                            </a>
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
<script src="{{asset('plugins/summernote/summernote-bs4.min.js')}}"></script>
<script>
    $(function () {
        $('#summernote').summernote({
            placeholder: 'Masukkan Deskripsi',
            height: 120,
            toolbar: [
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['insert', ['link']],
                ['view', ['fullscreen']]
            ]
        })
    })

</script>
@endpush