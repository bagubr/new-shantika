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
        <div class="card">
            <div class="card-body">
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
                        @foreach($souvenir_reedems as $souvenir_reedem)
                        <tr>
                            <td width="5%">{{$loop->iteration}}</td>
                            <td>{{ $souvenir_reedem->membership->user->name ?? '' }}</td>
                            <td>{{ $souvenir_reedem->souvenir_name }}</td>
                            <td>{{ $souvenir_reedem->quantity }}</td>
                            <td class="row justify-content-center"><span
                                    class="col w-50 text-center badge bg-{{ $souvenir_reedem->status == 'WAITING' ? 'success' : ($souvenir_reedem->status == 'ON PROCESS' ? 'warning' : ($souvenir_reedem->status == 'DELIVERED' ? 'info' : 'danger'))}}">
                                    {{ $souvenir_reedem->status }}
                                </span>
                            </td>
                            <td>{{ $souvenir_reedem->note ?? '' }}</td>
                            <td>
                                <a href="{{ route('souvenir_redeem.edit', ['souvenir_redeem' => $souvenir_reedem->id]) }}"
                                    class="btn btn-primary btn-xs">Update</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <br>
                <div class="float-right">
                    {{$souvenir_reedems->appends(Request::all())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('script')

@endpush
