@extends('layouts.main')
@section('title')
Admin
@endsection
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Admin </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">Admin</li>
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
                    <h3 class="card-title">Form</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body" style="display: block;">
                    @include('partials.error')
                    <form action="@isset($admin)
                        {{route('admin.update', $admin->id)}}
                    @endisset @empty($admin) {{route('admin.store')}} @endempty" method="POST">
                        @csrf
                        @isset($admin)
                        @method('PUT')
                        @endisset
                        <div class="form-group">
                            <label>Nama</label>
                            <input type="text" class="form-control" name="name"
                                value="{{isset($admin) ? $admin->name : ''}}" required placeholder="Masukkan Nama">
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" class="form-control" name="email" required
                                value="{{isset($admin) ? $admin->email : ''}}" placeholder="Masukkan Email">
                        </div>
                        <div class="form-group">
                            <label>Role</label>
                            <select name="role" class="form-control" required>
                                <option value="">Pilih Role</option>
                                @foreach ($roles as $role)
                                @if (isset($admin) )
                                <option value="{{$role->name}}" {{($role->name == $admin->roles[0]->name)?'selected':''}}>{{$role->name}}</option>
                                @elseif($role->id != 5)
                                <option value="{{$role->name}}">{{$role->name}}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col">
                                    <label>Password</label>
                                    <small style="color: red">isi jika ingin di ubah</small>
                                    <input type="password" name="password" class="form-control" @empty($admin) required
                                    @endempty>
                                </div>
                                <div class="col">
                                    <label>Konfirmasi Password</label>
                                    <input type="password" name="password_confirmation" class="form-control"
                                        @empty($admin) required @endempty>
                                </div>
                            </div>
                        </div>
                        <a href="{{URL::previous()}}" class="btn btn-secondary">Batal</a>
                        <input type="submit" value="Submit" class="btn btn-success float-right">
                    </form>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>
</section>
@endsection