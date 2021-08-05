@extends('layouts.main')
@section('title')
User Agent
@endsection
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">User Agent</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">User Agent</li>
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
                        <h3 class="card-title">Table User Agent</h3>
                        <div class="text-right">
                            <a href="{{route('user_agent.create')}}" class="btn btn-primary btn-sm">Tambah</a>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Nomor HP</th>
                                    <th>Agen</th>
                                    <th>Email</th>
                                    <th>Image</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user_agent)
                                <tr>
                                    <td>{{$user_agent->name}}</td>
                                    <td>{{$user_agent->phone}}</td>
                                    <td>
                                        <a href="{{route('agency.edit',$user_agent->agencies->agent->id)}}"
                                            target="_blank">
                                            {{$user_agent->agencies->agent->name}}/{{$user_agent->agencies->agent->city->name}}
                                        </a>
                                    </td>
                                    <td>{{$user_agent->email}}</td>
                                    <td>
                                        @if ($user_agent->avatar)
                                        <img src="{{$user_agent->avatar_url}}" height="100px" alt="">
                                        @elseif($user_agent->avatar === null || $user_agent->avatar === '')
                                        Tidak Ada Gambar
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{route('user_agent.edit',$user_agent->id)}}"
                                            class="btn btn-warning btn-xs">Edit</a>
                                        <form action="{{route('user_agent.destroy',$user_agent->id)}}" class="d-inline"
                                            method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-xs"
                                                onclick="return confirm('Are you sure?')" type="submit">Delete</button>
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