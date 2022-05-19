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
        <div class="row justify-content-left">
            <div class="col">
                <div class="card">
                    <div class="card-header">Menambahkan Souvenir Baru</div>
                    <div class="card-body">
                        <form action="{{route('souvenir.store')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group mb-3">
                                <label for="name">Masukan Nama Souvenir</label>
                                <input type="text" class="form-control" name="name" id="name" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="description">Masukan Deskripsi Souvenir</label>
                                <textarea name="description" class="form-control" id="description" cols="30"
                                    rows="2" required></textarea>
                            </div>
                            <div class="form-group mb-3">
                                <label for="point">Masukan batas minimum point untuk menukarkan</label>
                                <input type="number" class="form-control" name="point" id="point" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="quantity">Kuota</label>
                                <input type="number" class="form-control" name="quantity" id="quantity" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="image_name">Gambar Souvenir</label>
                                <input type="file" class="form-control-file" name="image_name" id="image_name"
                                    accept=".jpg,.jpeg,.png" required>
                            </div>
                            <div class="row float-right">
                                <input type="submit" value="Tambahkan Souvenir" class="btn btn-primary">
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
