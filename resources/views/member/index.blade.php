@extends('layouts.main')
@section('title')
Member
@endsection
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Member</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">Member</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Import Member</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{url('member/import')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label>Import</label>
                                <input type="file" class="form-control" required name="file">
                            </div>
                            @unlessrole('owner')

                            <div class="text-right">
                                <button class="btn btn-primary" type="submit">Import</button>
                            </div>
                            @endunlessrole
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Cari Member</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{route('member.search')}}" method="get">
                            <div class="form-group">
                                <label>Nama</label>
                                <input type="text" class="form-control" name="name" value="{{ @$name }}">
                            </div>
                            <div class="form-group">
                                <label>Kode</label>
                                <input type="text" class="form-control" name="code_member" value="{{ @$code_member }}">
                            </div>
                            <div class="text-right">
                                <button class="btn btn-success" type="submit">Cari</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Table Member</h3>
                        <div class="text-right">
                            <a href="{{route('member.create')}}" class="btn btn-primary btn-sm">Tambah</a>
                            <a href="#" class="btn btn-outline-primary btn-sm">Duplikasi Data Member {{ $duplicate_member }}</a>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
<<<<<<< HEAD
                        <table class="table table-bordered table-striped">
=======
                        <table class="table table-bordered table-striped table-responsive">
>>>>>>> rilisv1
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Kode Member</th>
                                    <th>Name</th>
                                    <th>Nomor Hp</th>
                                    <th>Name Aplikasi</th>
                                    <th>Nomor Hp Aplikasi</th>
                                    <th>Point</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($members as $member)
                                <tr>
                                    <td>{{$member->code_member}}</td>
                                    <td>{{$member->code_member_stk}}</td>
                                    <td>{{$member->name ?? ''}}</td>
                                    <td>{{$member->phone ?? ''}}</td>
                                    <td>{{$member->user->name ?? 'TIDAK DI KAITKAN APLIKASI'}}</td>
                                    <td>{{$member->user->phone ?? 'TIDAK DI KAITKAN APLIKASI'}}</td>
                                    <td>{{$member->sum_point ?? 0}}</td>
                                    <td>
                                        @unlessrole('owner')
                                        <a href="{{route('member.edit',$member->id)}}"
                                            class="btn btn-warning btn-xs">Edit</a>
                                        @if($member->user?->id)
                                        <a href="{{route('membership_point.index', ['membership_id' => $member->id])}}"
                                            class="btn btn-outline-primary btn-xs">History Point</a>
                                        @endif
<<<<<<< HEAD
                                        <form action="{{route('member.destroy',$member->id)}}" class="d-inline"
=======
                                        {{-- <form action="{{route('member.destroy',$member->id)}}" class="d-inline"
>>>>>>> rilisv1
                                            method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-xs"
                                                onclick="return confirm('Apakah Anda Yakin  Menghapus Data Ini??')"
                                                type="submit">Delete</button>
<<<<<<< HEAD
                                        </form>
=======
                                        </form> --}}
>>>>>>> rilisv1
                                        @endunlessrole
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="text-right">
                            {{$members->links()}}
                        </div>
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
@endpush
