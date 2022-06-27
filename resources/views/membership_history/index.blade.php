@extends('layouts.main')
@section('title')
Membership History
@endsection
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Membership History</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">Membership History</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Cari Membership History</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{route('membership_histories.index')}}" method="get">
                            <diV class="row">
                                <div class="form-group col-6">
                                    <label>Awal Tanggal</label>
                                    <input type="date" class="form-control" name="start_date" value="{{@$start_date}}">
                                </div>
                                <div class="form-group col-6">
                                    <label>Akhir Tanggal</label>
                                    <input type="date" class="form-control" name="end_date" value="{{@$end_date}}">
                                </div>
                            </diV>
                            <div class="text-right">
                                <button class="btn btn-success" type="submit">Cari</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <form action="{{route('membership_histories.index')}}">
                            @csrf
                            @unlessrole('owner')

                            <div class="text-right">
                                <button class="btn btn-primary btn-sm" type="submit" name="export" value="1">Export</button>
                                <a href="#" class="btn btn-outline-primary btn-sm">Total Data Membership History {{ $total }}</a>
                            </div>
                            @endunlessrole
                        </form>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Kode Order</th>
                                    <th>Kode Member</th>
                                    <th>Name</th>
                                    <th>Nomor Hp</th>
                                    <th>Email</th>
                                    <th>Agent</th>
                                    <th>Tanggal Penggunaan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($membership_histories as $membership_history)
                                <tr>
                                    @if ($membership_history->order_id)
                                        
                                    <td><a href="{{url('order')}}/{{$membership_history->order_id}}">{{$membership_history->code_order}}</a></td>
                                    @else
                                    <td>Tidak di temukan</td>
                                    @endif
                                    <td>{{$membership_history->membership->code_member ?? ''}}</td>
                                    <td>{{$membership_history->customer->name ?? ''}}</td>
                                    <td>{{$membership_history->customer->phone ?? ''}}</td>
                                    <td>{{$membership_history->customer->email ?? ''}}</td>
                                    <td>{{$membership_history->agency->name ?? ''}}</td>
                                    <td>{{$membership_history->created_at ?? ''}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="text-right">
                            {{$membership_histories->links()}}
                        </div>
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
