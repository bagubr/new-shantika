@extends('layouts.main')
@section('title')
Informasi
@endsection
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Informasi Form</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">Informasi</li>
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
                    <form action="@isset($information)
                        {{route('information.update', $information->id)}}
                    @endisset @empty($information) {{route('information.store')}} @endempty" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @isset($information)
                        @method('PUT')
                        @endisset
                        <div class="form-group">
                            <label for="inputName">Judul Informasi</label>
                            <input type="text" id="inputName" class="form-control" name="name"
                                placeholder="Masukkan Nama" value="{{isset($information) ? $information->name : ''}}">
                        </div>
                        <div class="form-group">
                            <label for="inputName">Alamat</label>
                            <input type="text" id="inputName" class="form-control" name="address"
                                placeholder="Masukkan address"
                                value="{{isset($information) ? $information->address : ''}}">
                        </div>
                        <div class="form-group">
                            <label>Deskripsi</label>
                            <textarea class="form-control" rows="3" name="description"
                                placeholder="Masukkan Deskripsi">{{isset($information) ? $information->description : ''}}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="">Gambar</label>
                            <input type="file" accept="image/*" class="form-control" name="image">
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