@extends('layouts.main')
@section('title')
Membership Point History
@endsection
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Membership Point History</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">Membership Point History</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Table Membership Point History {{ $membership->user->name }}</h3>
                        <div class="text-right">
                            <a href="#" class="btn btn-outline-primary btn-sm">Total Point {{ $membership->sum_point }}</a>
                            <a href="{{route('membership_point.create', ['membership_id' => $membership->id])}}" class="btn btn-primary btn-sm">Tambah/Kurang Point</a>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Status</th>
                                    <th>Nominal</th>
                                    <th>Keterangan</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($membership_points as $membership_point)
                                <tr>
                                    @if ($membership_point->status)
                                    <td>Penambahan Point</td>
                                    @else
                                    <td>Pengurangan Point</td>
                                    @endif
                                    <td>{{$membership_point->value}}</td>
                                    <td>{{$membership_point->message ?? ''}}</td>
                                    <td>{{date('d-m-Y H:i:s', strtotime($membership_point->created_at))}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
    </div>
</div>
@endsection
@push('script')
@endpush
