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
                            @foreach ($restaurant->admin as $admin)
                                <h5>{{$admin->pivot->phone}}</h5>
                            @endforeach
                        </div>
                        <div class="col">
                            <label>Email</label>
                            @foreach ($restaurant->admin as $admin)
                                <h5>{{$admin->email}}</h5>
                            @endforeach
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
                    <h3 class="card-title">Form Tambah Admin</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body" style="display: block;">
                    @include('partials.error')
                    <form action="{{route('restaurant.assign_user')}}" method="POST">
                        @csrf
                        <input type="text" name="restaurant_id" value="{{$restaurant->id}}" class="d-none">
                        <div class="form-group">
                            <label>Username</label>
                            <small style="color: red">*</small>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Nomor HP</label>
                            <small style="color: red">*</small>
                            <input type="text" name="phone" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <small style="color: red">*</small>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col">
                                    <label>Password</label>
                                    <small style="color: red">*</small>
                                    <input type="password" name="password" class="form-control" required>
                                </div>
                                <div class="col">
                                    <label>Konfirmasi Password</label>
                                    <small style="color: red">*</small>
                                    <input type="password" name="password_confirmation" class="form-control" required>
                                </div>
                            </div>
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
                                <th>Email</th>
                                <th>Nomor HP</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($restaurant_admin as $r)
                            <tr>
                                <td>{{$r->name}}</td>
                                <td>{{$r->email}}</td>
                                <td>{{$r->restaurant_admin->phone}}</td>
                                <td>
                                    <form action="{{route('restaurant.destroy_admin',$r->restaurant_admin?->id)}}"
                                        class="d-inline" method="POST">
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
<script>
    $(function () {
      $("#example1").DataTable({
        "responsive": true, "lengthChange": false, "autoWidth": false, "paging":false
      }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    });
</script>
@endpush