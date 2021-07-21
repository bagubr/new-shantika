@extends('layouts.main')
@section('title')
Testimoni
@endsection
@push('css')
<link rel="stylesheet" href="{{asset('plugins/summernote/summernote-bs4.min.css')}}">
@endpush
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Testimoni Form</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">Testimoni</li>
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
                    <form action="@isset($testimoni)
                        {{route('testimoni.update', $testimoni->id)}}
                    @endisset @empty($testimoni) {{route('testimoni.store')}} @endempty" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @isset($testimoni)
                        @method('PUT')
                        @endisset
                        <div class="form-group">
                            <label>Judul</label>
                            <input type="text" name="title" class="form-control" placeholder="Masukkan Judul"
                                value="{{isset($testimoni) ? $testimoni->title : ''}}">
                        </div>
                        <div class="form-group">
                            <label>Pengguna</label>
                            <select name="user_id" class="form-control select2" id="">
                                <option value="">Pilih Pengguna</option>
                                @foreach ($users as $user)
                                <option value="{{$user->id}}" @isset($testimoni) @if ($user->id == $testimoni->user_id)
                                    selected
                                    @endif
                                    @endisset>{{$user->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Gambar</label>
                            <input type="file" class="form-control" name="image" accept="image/*">
                        </div>
                        <div class="form-group">
                            <label>Review</label>
                            <textarea id="summernote" name="review">
                                {{isset($testimoni) ? $testimoni->review : ''}}
                            </textarea>
                        </div>
                        <div class="form-group">
                            <label>Rating</label>
                            <select name="rating" class="form-control" id="">
                                <option value="">Pilih Rating</option>
                                @for ($i = 5; $i > 0; $i--)
                                <option value="{{$i}}" @isset($testimoni) @if ($i==$testimoni->rating)
                                    selected
                                    @endif
                                    @endisset>{{$i}}</option>
                                @endfor

                            </select>
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