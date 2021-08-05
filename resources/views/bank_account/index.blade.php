@extends('layouts.main')
@section('title')
Bank
@endsection
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Bank</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">Bank</li>
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
                        <h3 class="card-title">Table Bank</h3>
                        <div class="text-right">
                            <a href="{{route('bank_account.create')}}" class="btn btn-primary btn-sm">Tambah</a>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Bank</th>
                                    <th>Nomor Rekening</th>
                                    <th>Nama Rekening</th>
                                    <th>Logo</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($bank_accounts as $bank_account)
                                <tr>
                                    <td>{{$bank_account->bank_name}}</td>
                                    <td>{{$bank_account->account_number}}</td>
                                    <td>{{$bank_account->account_name}}</td>
                                    <td>
                                        <a href="{{$bank_account->image_url}}" data-toggle="lightbox">
                                            <img src="{{$bank_account->image_url}}" style="height: 100px" alt="">
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{route('bank_account.edit',$bank_account->id)}}"
                                            class="btn btn-warning btn-xs">Edit</a>
                                        <form action="{{route('bank_account.destroy',$bank_account->id)}}"
                                            class="d-inline" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-xs"
                                                onclick="return confirm('Apakah Anda yakin akan menghapus data kota?')"
                                                type="submit">Delete</button>
                                        </form>
                                    </td>
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
<script>
    $(function () {
      $("#example1").DataTable({
        "responsive": true, "lengthChange": false, "autoWidth": false,
      }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    });
</script>
@endpush