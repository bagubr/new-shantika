@extends('layouts.main')
@section('title', 'Souvenir')
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Souvenir</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">Souvenir</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
<div class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <a href="{{route('souvenir.create')}}" class="btn btn-primary mb-2 float-right">Tambah</a>
            </div>
            <div class="card-body">
                <table class="table table-striped text-center">
                    <thead>
                        <th>Gambar</th>
                        <th>Nama</th>
                        <th>Deskripsi</th>
                        <th>Poin</th>
                        <th>Kuota</th>
                        <th>Action</th>
                    </thead>
                    <tbody>
                        @foreach($souvenirs as $souvenir)
                        <tr>
                            <td><img src='{{url("storage/".$souvenir->image_name)}}' style="max-width: 200px;"
                                    class="img-rounded img-thumbnail" />
                            </td>
                            <td>{{$souvenir->name}}</td>
                            <td>{{$souvenir->description}}</td>
                            <td>{{$souvenir->point}}</td>
                            <td>{{$souvenir->quantity}}</td>
                            <td>
                                <div class="row justify-content-center">
                                    <a href="{{route('souvenir.edit',$souvenir->id)}}"
                                        class="btn btn-warning btn-xs">Edit</a> &nbsp;
                                    <form action="{{route('souvenir.destroy',$souvenir->id)}}" class="d-inline"
                                        method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-xs"
                                            onclick="return confirm('Apakah Anda Yakin  Menghapus Data Ini??')"
                                            type="submit">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="text-right">
                    {{$souvenirs->links()}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('script')
@endpush
