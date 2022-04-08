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
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Table Member</h3>
                        <div class="text-right">
                            <a href="{{route('membership_point.create')}}" class="btn btn-primary btn-sm">Tambah Point</a>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table class="table table-bordered table-striped">
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
                                        @if($member->user->id)
                                        <a href="{{route('member.history',$member->id)}}"
                                            class="btn btn-outline-primary btn-xs">History Point</a>
                                        @endif
                                        <form action="{{route('member.destroy',$member->id)}}" class="d-inline"
                                            method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-xs"
                                                onclick="return confirm('Apakah Anda Yakin  Menghapus Data Ini??')"
                                                type="submit">Delete</button>
                                        </form>
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
