@extends('layouts.main')
@section('title')
Restoran
@endsection
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>{{$restaurant->name}}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">Restoran</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>
<section class="content">
    <div class="row">
        <div class="col-md-6">
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
                    <div class="form-row">
                        <div class="col">
                            <label>Nama</label>
                            <h5>{{$restaurant->name}}</h5>
                        </div>
                        <div class="col">
                            <label>Nomor HP</label>
                            <h5>{{$restaurant->phone}}</h5>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Alamat</label>
                        <h5>{{$restaurant->address}}</h5>
                    </div>
                    <hr>
                    <div class="form-group">
                        <label>Bank</label>
                        <h5>{{$restaurant->bank_name}}</h5>
                    </div>
                    <div class="form-group">
                        <label>Bank Owner</label>
                        <h5>{{$restaurant->bank_owner}}</h5>
                    </div>
                    <div class="form-group">
                        <label>Nomor Rekening</label>
                        <h5>{{$restaurant->bank_account}}</h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Form Restoran Admin</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body" style="display: block;">
                    <form action="{{route('restaurant.assign_user')}}" method="POST">
                        @csrf
                        <input type="text" name="restaurant_id" value="{{$restaurant->id}}" class="d-none">
                        <div class="form-group">
                            <label>Akun User</label>
                            <select class="form-control select2" name="admin_id" required>
                                <option value="">Pilih User</option>
                                @foreach ($admins as $admin)
                                <option value="{{$admin->id}}">{{$admin->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Nomor HP</label>
                            <input type="text" name="phone" class="form-control" required>
                        </div>
                        <div class="text-right">
                            <input class="btn btn-success" type="submit" value="Submit" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Detail Akun {{$restaurant->name}}</h3>
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
                                <th>Nama</th>
                                <th>Nomor HP</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($restaurant->admins as $r)
                            <tr>
                                <td>{{$r->admin->name}}</td>
                                <td>{{$r->phone}}</td>
                                <td>
                                    <a class="btn btn-danger btn-xs button-delete" data-id="{{$r->id}}">Delete</a>
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