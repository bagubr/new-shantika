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
<<<<<<< HEAD
                                <select name="area_id" class="form-control">
                                    <option value="">--PILIH AREA--</option>
                                    @foreach ($areas as $area)
                                    @if (old('area_id') == $area->id)
=======
                                <select name="area_id" class="form-control" {{($area_id)?'disabled':''}}>
                                    <option value="">--PILIH AREA--</option>
                                    @foreach ($areas as $area)
                                    @if ($area_id == $area->id)
>>>>>>> rilisv1
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
                                            @if (old('agent') == $agency->id)
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
                        @unlessrole('owner')
                        <div class="text-right">
                            <a href="{{route('user_agent.create')}}" class="btn btn-primary btn-sm">Tambah</a>
                        </div>
                        @endunlessrole
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table class="table table-bordered table-striped" id="example1">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Area</th>
                                    <th>Nomor HP</th>
                                    <th>Agen</th>
                                    <th>Email</th>
                                    <th>Gambar</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user_agent)
                                <tr>
                                    <td>{{$user_agent->name_agent}}</td>
                                    <td>{{$user_agent->agencies?->agent?->city?->area?->name}}</td>
                                    <td>{{$user_agent->phone}}</td>
                                    <td>
                                        @if (!empty($user_agent->agencies?->agent?->id))
                                        <a href="{{route('agency.edit',$user_agent->agencies?->agent?->id)}}">
                                            {{$user_agent->agencies?->agent?->name}}/{{$user_agent->agencies?->agent?->city?->name}}
                                        </a>
                                        @endif
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
                                    @if ($user_agent->is_active == 1)
                                    <td data-toggle="modal" data-target="#exampleModal{{$user_agent->id}}"
                                        class="text-success text-bold pointer">
                                        Aktif
                                    </td>
                                    @else
                                    <td data-toggle="modal" data-target="#exampleModal{{$user_agent->id}}"
                                        class="text-danger text-bold">
                                        Non Aktif
                                    </td>
                                    @endif
                                    <td>
                                        <a href="{{route('user_agent.show',$user_agent->id)}}"
                                            class="btn btn-primary btn-xs" target="_blank">
                                            Detail
                                        </a>
                                        @unlessrole('owner')
                                        <a href="{{route('user_agent.edit',$user_agent->id)}}"
                                            class="btn btn-warning btn-xs">Edit</a>
                                        @endunlessrole
                                        {{-- <form action="{{route('user_agent.destroy',$user_agent->id)}}"
                                            class="d-inline" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-xs"
                                                onclick="return confirm('Apakah Anda Yakin  Menghapus Data Ini??')"
                                                type="submit">Delete</button>
                                        </form> --}}
                                    </td>
                                </tr>
                                <div class="modal fade" id="exampleModal{{$user_agent->id}}" tabindex="-1" role="dialog"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Ubah Status
                                                    {{$user_agent->name}}</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="{{route('user_agent.update_status',$user_agent->id)}}"
                                                method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <select class="form-control input" name="is_active">
                                                            @foreach ($statuses as $s => $key)
                                                            <option value="{{$s}}" @if ($s==$user_agent->is_active)
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