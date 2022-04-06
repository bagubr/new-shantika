@extends('layouts.main')
@section('title')
User Agen
@endsection
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Detail User Agen</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">User Agen</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Detail</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body" style="display: block;">
                    <div class="form-group">
                        <label>Nama</label>
                        <input type="text" class="form-control" name="name" placeholder="Masukkan Nama" disabled
                            value="{{isset($user_agent) ? $user_agent->name : old('name')}}">
                    </div>

                    <div class="form-row">
                        <div class="col">
                            <div class="form-group">
                                <label>Agen</label>
                                <p>
                                    <a href="{{route('agency.edit',$user_agent->agencies?->agent?->id)}}"
                                        target="_blank">
                                        {{$user_agent->agencies?->agent?->name}}
                                    </a>
                                </p>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label>Jenis Kelamin</label>
                                <select name="gender" class="form-control" disabled>
                                    <option value="{{$user_agent->gender}}" selected>{{$user_agent->gender}}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col">
                            <div class="form-group">
                                <label>Kota Lahir</label>
                                <input type="text" name="birth_place" class="form-control" disabled
                                    value="{{isset($user_agent) ? $user_agent->birth_place: old('birth_place') }}"
                                    placeholder="Masukkan Kota Kelahiran">
                            </div>
                        </div>
                        <div class="col">
                            <label>Tanggal Lahir</label>
                            <input type="date" class="form-control" name="birth" disabled
                                value="{{isset($user_agent) ? $user_agent->birth : old('birth')}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Phone</label>
                        <input type="text" name="phone" class="form-control" placeholder="Masukkan Nomor Hp" disabled
                            value="{{isset($user_agent) ? $user_agent->phone : old('phone')}}">
                    </div>
                    <div class="form-group">
                        <label for="">Email</label>
                        <input type="email" name="email" class="form-control" name="email" disabled
                            placeholder="Masukkan Email"
                            value="{{isset($user_agent) ? $user_agent->email : old('email')}}">
                    </div>
                    <div class="form-group">
                        <label>Alamat</label>
                        <textarea name="address" disabled
                            class="form-control">{{isset($user_agent) ? $user_agent->address : old('address')}}</textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Riwayat Transaksi</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body" style="display: block;">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Kode Order</th>
                                <th>Rute Armada</th>
                                <th>Total Harga</th>
                                <th>Status</th>
                                <th>Tanggal Pemesanan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order)
                            <tr>
                                <td>
                                    <a href="{{route('order.show',$order->id)}}">
                                        {{$order->code_order}}
                                    </a>
                                </td>
                                <td>
                                    <a href="@{{route('fleet_route.show',$order->fleet_route?->id)}}">
                                        {{$order->fleet_route?->route?->name}}/{{$order->fleet_route?->fleet?->name}}
                                    </a>
                                </td>
                                <td>
                                    Rp. {{number_format($order->price,2)}}
                                </td>
                                <td>{{$order->status}}</td>
                                <td>{{date('Y-m-d',strtotime($order->reserve_at))}}</td>
                                <td>
                                    <a class="btn btn-primary btn-xs" href="{{route('order.show',$order->id)}}">Detail
                                        Pemesanan</a>
                                    <form action="{{route('order.destroy',$order->id)}}" class="d-inline" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-xs"
                                            onclick="return confirm('Apakah Anda Yakin  Menghapus Data Ini??')"
                                            type="submit">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@push('script')
<script src="{{asset('plugins/summernote/summernote-bs4.min.js')}}"></script>
<script>
    $(function () {
        $('.select2').select2()
    })
    $('.select2bs4').select2({
        theme: 'bootstrap4'
    })

</script>
@endpush