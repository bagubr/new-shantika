@extends('layouts.main')
@section('title', 'Souvenir')
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Souvenir</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">Souvenir</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
<div class="content">
    <div class="container-fluid">
        <div class="row justify-content-start">
<<<<<<< HEAD
            <div class="col-md-6">
=======
            <div class="col">
>>>>>>> rilisv1
                <div class="card">
                    <div class="card-header">Merubah Souvenir</div>
                    <div class="card-body">
                        <form action="{{route('souvenir.update', ['id' => $data->id])}}" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="form-group mb-3">
                                <label for="name">Masukan Nama Souvenir</label>
                                <input type="text" class="form-control" value="{{ $data->name }}" name="name" id="name">
                            </div>
                            <div class="form-group mb-3">
                                <label for="description">Masukan Deskripsi Souvenir</label>
                                <textarea name="description" class="form-control" id="description" cols="30"
                                    rows="2">{{ $data->description }}</textarea>
                            </div>
                            <div class="form-group mb-3">
                                <label for="point">Masukan batas minimum point untuk menukarkan</label>
                                <input type="number" class="form-control" value="{{ $data->point }}" name="point"
                                    id="point">
                            </div>
                            <div class="form-group mb-3">
<<<<<<< HEAD
                                <label for="quantity">Kuantitas Souvenir</label>
=======
                                <label for="quantity">Kuota</label>
>>>>>>> rilisv1
                                <input type="text" class="form-control" value="{{ $data->quantity }}" name="quantity"
                                    id="quantity">
                            </div>
                            <div class="form-group mb-3">
                                <label for="image_name_thumbnail">Gambar Souvenir</label>
                                <img src="{{ url("storage/" .$data->image_name) }}" alt="" style="max-width: 200px;"
                                name="image_name_thumbnail"
                                class="img-thumbnail">
                            </div>
                            <div class="form-group mb-3">
                                <label for="image_name">Update Gambar Souvenir</label>
                                <input type="file" class="form-control-file" name="image_name" id="image_name"
                                    accept=".jpg,.jpeg,.png">
                            </div>
<<<<<<< HEAD
                            <div class="row justify-content-center">
                                <input type="submit" value="Update Souvenir" class="btn btn-primary">
=======
                            <div class="row float-right">
                                <input type="submit" value="Update" class="btn btn-primary">
>>>>>>> rilisv1
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('script')
@endpush
