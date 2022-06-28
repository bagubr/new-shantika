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
<<<<<<< HEAD
            <div class="col-md-6">
=======
            <div class="col-md-12">
>>>>>>> rilisv1
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
<<<<<<< HEAD
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
=======
                                    value="{{ $data->membership->user->name ?? '' }}" id="name" disabled>
                            </div>
                            <div class="form-group mb-3">
                                <label for="point">Nama Souvenir</label>
                                <input type="text" class="form-control" value="{{ $data->souvenir_name }}" id="point" disabled>
                            </div>
                            <div class="form-group mb-3">
                                <label for="">Agent yang dipilih</label>
                                <select name="agency_id" id="" required class="form-control select2">
                                    <option value="">PILIH</option>
                                    @foreach ($agencies as $agency)
                                        <option value="{{$agency->id}} @isset($data)
                                            @if ($agency->id == $data->agency_id)
                                                selected
                                            @endif
                                        @endisset">{{$agency->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <label for="point">Jumlah</label>
                                <input type="text" class="form-control" value="{{ $data->quantity }}" id="point" disabled>
                            </div>
                            <div class="form-group mb-3">
                                <label for="status">Status</label>
                                <select class="form-control" name="status" id="status"  @if ($data->status != "ON PROCESS" && $data->status != "WAITING") disabled @endif>
                                    <option value="WAITING" @if ($data->status == "WAITING") selected @endif>WAITING</option>
                                    <option value="ON_PROCESS" @if ($data->status == "ON_PROCESS") selected @endif>ON PROCESS</option>
                                    <option value="DECLINED" @if ($data->status == "DECLINED") selected @endif>DECLINED</option>
                                    <option value="DELIVERED" @if ($data->status == "DELIVERED") selected @endif>DELIVERED</option>
>>>>>>> rilisv1
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <label for="note">Masukan Catatan</label>
                                <textarea name="note" class="form-control" id="note" cols="30"
                                    rows="2">{{ $data->note }}</textarea>
                            </div>
<<<<<<< HEAD
                            <div class="row justify-content-center">
                                <input type="submit" value="Update Status" class="btn btn-primary">
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
