@extends('layouts.main')
@section('title')
User
@endsection
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">User</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">User</li>
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
                        <h3 class="card-title">Table User</h3>
                        <div class="text-right">
                            @unlessrole('owner')
                            <a href="{{route('user.create')}}" class="btn btn-primary btn-sm">Tambah</a>
                            @endunlessrole
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Nomor HP</th>
                                    <th>Email</th>
                                    <th>TTL</th>
                                    <th>Alamat</th>
                                    <th>Gender</th>
                                    <th>Status</th>
                                    <th>Image</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                <tr>
                                    <td>{{$user->name}}</td>
                                    <td>{{$user->phone}}</td>
                                    <td>{{$user->email}}</td>
                                    <td>{{$user->birth_place ?? '-'}}, {{date('l d F Y', strtotime($user->birth))}}</td>
                                    <td>{{$user->address ?? '-'}}</td>
                                    <td>{{$user->gender ?? '-'}}</td>
                                    @if ($user->is_active == 1)
                                    <td data-toggle="modal" data-target="#exampleModal{{$user->id}}"
                                        class="text-success text-bold pointer">
                                        Aktif
                                    </td>
                                    @else
                                    <td data-toggle="modal" data-target="#exampleModal{{$user->id}}"
                                        class="text-danger text-bold">
                                        Non Aktif
                                    </td>
                                    @endif
                                    <td>
                                        @if ($user->avatar)
                                        <a href="{{$user->avatar_url}}" data-toggle="lightbox">
                                            <img src="{{$user->avatar_url}}" height="100px" alt="">
                                        </a>
                                        @elseif($user->avatar === null || $user->avatar === '')
                                        Tidak Ada Gambar
                                        @endif
                                    </td>
                                    <td>
<<<<<<< HEAD
=======
                                        <a href="{{route('user.show',$user->id)}}"
                                            class="btn btn-primary btn-xs" target="_blank">
                                            Detail
                                        </a>
>>>>>>> rilisv1
                                        @unlessrole('owner')
                                        <a href="{{route('user.edit',$user->id)}}"
                                            class="btn btn-warning btn-xs">Edit</a>
                                        @endunlessrole
                                        {{-- <form action="{{route('user.destroy',$user->id)}}" class="d-inline"
                                            method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-xs"
                                                onclick="return confirm('Apakah Anda Yakin  Menghapus Data Ini??')"
                                                type="submit">Delete</button>
                                        </form> --}}
                                    </td>
                                </tr>
                                <div class="modal fade" id="exampleModal{{$user->id}}" tabindex="-1" role="dialog"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Ubah Status
                                                    {{$user->name}}</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="{{route('user.update_status',$user->id)}}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <select class="form-control input" name="is_active">
                                                            @foreach ($statuses as $s => $key)
                                                            <option value="{{$s}}" @if ($s==$user->is_active)
                                                                selected
                                                                @endif>{{$key}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
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
        "buttons": [
                {
                    "extend": 'pdf',
                    "exportOptions": {
                        "columns": [0,1, 2, 3, 4, 5, 6]
                    }
                },
                {
                    "extend": 'csv',
                    "exportOptions": {
                        "columns": [0,1, 2, 3, 4, 5, 6]
                    }

                },
                {
                    "extend": 'excel',
                    "exportOptions": {
                        "columns": [0,1, 2, 3, 4, 5, 6]
                    }
                },
                {
                    "extend": 'print',
                    "exportOptions": {
                        "columns": [0,1, 2, 3, 4, 5, 6]
                    }
                }
            ],
            "dom": 'Bfrtip',
      }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    });
</script>
@endpush