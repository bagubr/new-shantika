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
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{$data -> links("pagination::bootstrap-4")}}
            </div>
        </div>
    </div>
</div>
@endsection
@push('script')

@endpush
