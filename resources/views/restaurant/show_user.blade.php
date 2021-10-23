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
    </div>
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
                    <div class="row">
                        <div class="col-md-6">
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
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Gambar</label>
                                <img src="{{$restaurant->image}}" class="img-fluid" style="height: 100px">
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>
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
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Nomor HP</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($restaurant->admins as $admin)
                            <tr>
                                <td>{{$admin->admin?->name}}</td>
                                <td>{{$admin->phone}}</td>
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
        "responsive": true, "lengthChange": false, "autoWidth": false,
      }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    });
</script>
@endpush