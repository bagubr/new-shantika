@extends('layouts.main')
@section('title')
Artikel
@endsection
@push('css')
<link rel="stylesheet" href="{{asset('plugins/summernote/summernote-bs4.min.css')}}">
@endpush
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Artikel Form</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">Artikel</li>
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
                    <form action="@isset($article)
                        {{route('article.update', $article->id)}}
                    @endisset @empty($article) {{route('article.store')}} @endempty" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @isset($article)
                        @method('PUT')
                        @endisset
                        <div class="form-group">
                            <label for="inputName">Nama Artikel</label>
                            <input type="text" id="inputName" class="form-control" name="name" required
                                placeholder="Masukkan Nama" value="{{isset($article) ? $article->name : ''}}">
                        </div>
                        <div class="form-group">
                            <label>Deskripsi</label>
                            <textarea id="summernote" name="description" required>
                                {{isset($article) ? $article->description : ''}}
                            </textarea>
                        </div>
                        <div class="form-group">
                            <label>Gambar</label>
                            <input type="file" class="form-control" name="image" accept="image/*">
                            <small class="text-danger"><i class="fas fa-info-circle"></i> Pastikan ukuran gambar
                                445 x 445 (72)dpi, agar hasil maksimal</small>
                            <br>
                            @isset($article)
                            <a href="{{$article->image}}" data-toggle="lightbox">
                                <img src="{{isset($article) ? $article->image : ''}}" class="img-thumbnail"
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
<script>
    $(function () {
        $('.select2').select2()
    })
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })
</script>
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