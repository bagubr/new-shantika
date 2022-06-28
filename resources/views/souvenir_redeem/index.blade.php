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
                    <li class="breadcrumb-item active">Souvenir</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
<div class="content">
    <div class="container-fluid">
<<<<<<< HEAD
        <div class="row">
            {{-- <div class="col-6">
                <a href="{{route('souvenir.create')}}" class="btn btn-primary mb-2">Buat Souvenir Baru</a>
            </div> --}}
            <div class="col-md-12">
                <table class="table table-striped text-center">
                    <thead>
                        <th>No</th>
                        <th>Nama Member</th>
                        <th>Nama Souvenir</th>
                        <th>Jumlah Tukar</th>
                        <th>Status</th>
                        <th>Catatan</th>
                        <th>Action</th>
                    </thead>
                    <tbody>
                        @foreach($data as $each)
                        <tr>
                            <td>{{ ($data ->currentpage()-1) * $data ->perpage() + $loop->index + 1
                                }}</td>
                            <td>{{ $each->membership->user->name ?? '' }}</td>
                            <td>{{ $each->souvenir_name }}</td>
                            <td>{{ $each->quantity }}</td>
                            <td class="row justify-content-center"><span
                                    class="col w-50 text-center badge bg-{{ $each->status == 'WAITING' ? 'success' : ($each->status == 'ON PROCESS' ? 'warning' : ($each->status == 'DELIVERED' ? 'info' : 'danger'))}}">
                                    {{ $each->status }}
                                </span>
                            </td>
                            <td>{{ $each->note ?? '' }}</td>
                            <td>
                                <a href="{{ route('souvenir_redeem.edit', ['souvenir_redeem' => $each->id]) }}"
                                    class="btn btn-primary shadow border">Update Status</a>
=======
        <div class="card">
            <div class="card-header">
                Pencarian
            </div>
            <div class="card-body">
                <form action="{{route('souvenir_redeem.index')}}" method="get">
                <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Tanggal</label>
                                <input type="date" class="form-control" name="created_at" value="{{@$created_at}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Nama</label>
                                <input type="text" class="form-control" name="name" value="{{@$name}}">
                            </div>
                        </div>
                    </div>
                </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary btn-sm float-right">Cari</button>
            </div>
            </form>
        </div>
        <div class="card">
            <div class="card-body">
                <table class="table table-striped text-center">
                    <thead>
                        <th>Nama Member</th>
                        <th>Agent Pengiriman Souvenir</th>
                        <th>Nama Souvenir</th>
                        <th>Jumlah Tukar</th>
                        <th>Keterangan</th>
                        <th>Status</th>
                        <th>Catatan</th>
                        <th>Tanggal Pengajuan</th>
                        <th>Action</th>
                    </thead>
                    <tbody>
                        @foreach($souvenir_redeems as $souvenir_redeem)
                        <tr>
                            <td>{{ $souvenir_redeem->membership->user->name ?? '' }}</td>
                            <td>({{$souvenir_redeem->agency->city->name??''}}){{ $souvenir_redeem->agency?->name??''}}</td>
                            <td>{{ $souvenir_redeem->souvenir_name }}</td>
                            <td>{{ $souvenir_redeem->quantity }}</td>
                            <td>{{ $souvenir_redeem->message }}</td>
                            <td class="row justify-content-center"><span
                                    class="col w-50 text-center badge bg-{{ $souvenir_redeem->status == 'WAITING' ? 'success' : ($souvenir_redeem->status == 'ON PROCESS' ? 'warning' : ($souvenir_redeem->status == 'DELIVERED' ? 'info' : 'danger'))}}">
                                    {{ $souvenir_redeem->status }}
                                </span>
                            </td>
                            <td>{{ $souvenir_redeem->note ?? '' }}</td>
                            <td>{{$souvenir_redeem->created_at}}</td>
                            <td>
                                <a href="{{ route('souvenir_redeem.edit', ['souvenir_redeem' => $souvenir_redeem->id]) }}"
                                    class="btn btn-primary btn-xs">Update</a>
>>>>>>> rilisv1
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
<<<<<<< HEAD
                {{$data -> links("pagination::bootstrap-4")}}
=======
                <br>
                <div class="float-right">
                    {{$souvenir_redeems->appends(Request::all())->links() }}
                </div>
>>>>>>> rilisv1
            </div>
        </div>
    </div>
</div>
@endsection
@push('script')

@endpush
