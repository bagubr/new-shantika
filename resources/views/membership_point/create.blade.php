@extends('layouts.main')
@section('title')
Membership Point
@endsection
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Membership Point Form</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">Membership Point</li>
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
                    <form action="{{route('membership_point.store')}}" method="POST">
                        @csrf
                        <input type="hidden" class="form-control" name="membership_id" required value="{{ $membership_id }}">
                        <div class="form-group">
                            <label>Nominal</label><span class="text-danger">*</span>
                            <input type="number" class="form-control" name="value" required>
                        </div>
                        <div class="form-group">
                            <label>Select</label>
                            <select name="status" class="form-control" required>
                                <option value="">Pilih Status</option>
<<<<<<< HEAD
                                <option value="purchase">Tambah</option>
                                <option value="redeem">Kurang</option>
=======
                                <option value="1">Tambah</option>
                                <option value="0">Kurang</option>
>>>>>>> rilisv1
                            </select>
                        </div>
                        <a href="{{URL::previous()}}" class="btn btn-secondary">Batal</a>
                        <input type="submit" value="Submit" class="btn btn-success float-right">
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection