@extends('layouts.main')
@section('title')
Menu Pengguna
@endsection
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Menu Pengguna Form</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">Menu Pengguna</li>
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
                    <form action="@isset($customer_menu)
                        {{route('customer_menu.update', $customer_menu->id)}}
                    @endisset @empty($customer_menu) {{route('customer_menu.store')}} @endempty" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @isset($customer_menu)
                        @method('PUT')
                        @endisset
                        <div class="form-group">
                            <label>Menu Pengguna Nama</label>
                            <input type="text" class="form-control" name="name"
                                value="{{isset($customer_menu) ? $customer_menu->name : ''}}">
                        </div>
                        <div class="form-group">
                            <label>Icon</label>
                            <input type="file" class="form-control" name="icon" accept="image/*"
                                value="{{isset($customer_menu) ? $customer_menu->icon : ''}}">
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