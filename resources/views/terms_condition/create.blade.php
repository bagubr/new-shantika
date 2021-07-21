@extends('layouts.main')
@section('title')
Syarat dan Ketentuan
@endsection
@push('css')
<link rel="stylesheet" href="{{asset('plugins/summernote/summernote-bs4.min.css')}}">
@endpush
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Syarat dan Ketentuan Form</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">Syarat dan Ketentuan</li>
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
                    <form action="@isset($terms_condition)
                        {{route('terms_condition.update', $terms_condition->id)}}
                    @endisset @empty($terms_condition) {{route('terms_condition.store')}} @endempty" method="POST">
                        @csrf
                        @isset($terms_condition)
                        @method('PUT')
                        @endisset
                        <div class="form-group">
                            <label>Nama</label>
                            <input type="text" class="form-control" name="name"
                                value="{{isset($terms_condition) ? $terms_condition->name : ''}}"
                                placeholder="Masukkan Nama">
                        </div>
                        <div class="form-group">
                            <label>Konten</label>
                            <textarea id="summernote" name="content">
                                {{isset($terms_condition) ? $terms_condition->content : ''}}
                            </textarea>
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
        $('.select2').select2()
    })
    $('.select2bs4').select2({
        theme: 'bootstrap4'
    })

</script>
<script>
    $(function () {
        $('#summernote').summernote({
            placeholder: 'Masukkan Review',
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