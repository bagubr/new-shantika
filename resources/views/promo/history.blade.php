@extends('layouts.main')
@section('title')
Promo History
@endsection
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Promo History</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">Promo History</li>
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
                        <div class="text-right">
                            {{-- <button class="btn btn-primary btn-sm" type="submit" name="export" value="1">Export</button> --}}
                            {{-- <a href="#" class="btn btn-outline-primary btn-sm">Total Data Membership History {{ $total }}</a> --}}
                            {{-- <a href="#" class="btn btn-outline-primary btn-sm">Total Potongan Membership Rp. {{number_format($nominal)}}</a> --}}
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Kode Order</th>
                                    <th>Name</th>
                                    <th>Nomor Hp</th>
                                    <th>Email</th>
                                    <th>Promo</th>
                                    <th>Total Potongan</th>
                                    <th>Tanggal Penggunaan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($promo_histories as $promo_history)
                                <tr>
                                    @if ($promo_history->order)
                                        
                                    <td><a href="{{url('order')}}/{{$promo_history->order->id}}">{{$promo_history->order->code_order}}</a></td>
                                    @else
                                    <td>Tidak di temukan</td>
                                    @endif
                                    <td>{{$promo_history->user->name ?? ''}}</td>
                                    <td>{{$promo_history->user->phone ?? ''}}</td>
                                    <td>{{$promo_history->user->email ?? ''}}</td>
                                    <td>{{$promo_history->promo->name ?? ''}}</td>
                                    <td>Rp. {{number_format(@$promo_history->order?->nominal_discount ?? 0)}}</td>
                                    <td>{{$promo_history->created_at ?? ''}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="text-right">
                            {{$promo_histories->links()}}
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
