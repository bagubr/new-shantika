@extends('layouts.main')
@section('title', 'Souvenir')
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Souvenir Redeem</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">Souvenir Redeem</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
<div class="content">
    <div class="container-fluid">
        <div class="row justify-content-start">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Merubah Souvenir Redeem</div>
                    <div class="card-body">
                        <form action="{{route('souvenir_redeem.update', ['souvenir_redeem' => $data->id])}}"
                            method="post" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="form-group mb-3">
                                <label for="name">Nama Member</label>
                                <input type="text" class="form-control"
                                    value="{{ $data->membership->user->name ?? '' }}" id="name">
                            </div>
                            <div class="form-group mb-3">
                                <label for="point">Nama Souvenir</label>
                                <input type="text" class="form-control" value="{{ $data->souvenir_name }}" id="point">
                            </div>
                            <div class="form-group mb-3">
                                <label for="status">Status</label>
                                <select class="form-control" name="status" id="status">
                                    <option value="ON PROCESS">ON PROCESS</option>
                                    <option value="DECLINED">DECLINED</option>
                                    <option value="DELIVERED">DELIVERED</option>
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <label for="note">Masukan Catatan</label>
                                <textarea name="note" class="form-control" id="note" cols="30"
                                    rows="2">{{ $data->note }}</textarea>
                            </div>
                            <div class="row justify-content-center">
                                <input type="submit" value="Update Status" class="btn btn-primary">
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
