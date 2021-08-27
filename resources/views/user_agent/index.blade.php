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
                        <h3 class="card-title">Cari</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{route('user_agent.search')}}" method="GET">
                            <div class="form-group">
                                <label>Cari Area</label>
                                <select name="area_id" class="form-control">
                                    <option value="">--PILIH AREA--</option>
                                    @foreach ($areas as $area)
                                    @if (old('area_id') == $area->id)
                                    <option value="{{$area->id}}" selected>{{$area->name}}</option>
                                    @else
                                    <option value="{{$area->id}}">{{$area->name}}</option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-row">
                                <div class="col">
                                    <div class="form-group">
                                        <label>Nama</label>
                                        <input type="text" class="form-control" name="name" value="{{old('name')}}"
                                            placeholder="Cari Nama Agen">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label>Agen</label>
                                        <select name="agent" id="" class="form-control select2">
                                            <option value="">--Pilih Agen--</option>
                                            @foreach ($agencies as $agency)
                                            @if (old('agent' == $agency->id))
                                            <option value="{{$agency->id}}" selected>
                                                {{$agency->city?->name}}/{{$agency->name}}</option>
                                            @else
                                            <option value="{{$agency->id}}">
                                                {{$agency->city?->name}}/{{$agency->name}}
                                            </option>
                                            @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <button type="submit" class="btn btn-success">Cari</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Table User Agent</h3>
                        <div class="text-right">
                            <a href="{{route('user_agent.create')}}" class="btn btn-primary btn-sm">Tambah</a>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table class="table table-bordered table-striped" id="example1">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Nomor HP</th>
                                    <th>Agen</th>
                                    <th>Email</th>
                                    <th>Gambar</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user_agent)
                                <tr>
                                    <td>{{$user_agent->name_agent}}</td>
                                    <td>{{$user_agent->phone}}</td>
                                    <td>
                                        <a href="{{route('agency.edit',$user_agent->agencies?->agent->id)}}"
                                            target="_blank">
                                            {{$user_agent->agencies?->agent?->name}}/{{$user_agent->agencies?->agent?->city?->name}}
                                        </a>
                                    </td>
                                    <td>{{$user_agent->email}}</td>
                                    <td>
                                        @if ($user_agent->avatar)
                                        <a href="{{$user_agent->avatar_url}}" data-toggle="lightbox">
                                            <img src="{{$user_agent->avatar_url}}" height="100px" alt="">
                                        </a>
                                        @elseif($user_agent->avatar === null || $user_agent->avatar === '')
                                        Tidak Ada Gambar
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{route('user_agent.show',$user_agent->id)}}"
                                            class="btn btn-primary btn-xs" target="_blank">
                                            Detail
                                        </a>
                                        <a href="{{route('user_agent.edit',$user_agent->id)}}"
                                            class="btn btn-warning btn-xs">Edit</a>
                                        <form action="{{route('user_agent.destroy',$user_agent->id)}}" class="d-inline"
                                            method="POST">
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
                        {{-- @if (route('user_agent.index'))
                        {{$users->links("pagination::bootstrap-4")}}
                        @endif --}}
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
        $('.select2').select2()
    })
    $('.select2bs4').select2({
        theme: 'bootstrap4'
    })

</script>
<script>
    $(function () {
      $("#example1").DataTable({
        "responsive": true, "lengthChange": false, "autoWidth": false,
      }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    });
</script>
@endpush