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
<<<<<<< HEAD
        <div class="row">
            <div class="col-6">
                <a href="{{route('souvenir.create')}}" class="btn btn-primary mb-2">Buat Souvenir Baru</a>
            </div>
            <div class="col-md-12">
                <table class="table table-striped text-center">
                    <thead>
                        <th>No</th>
                        <th>Gambar</th>
                        <th>Nama</th>
                        <th>Description</th>
                        <th>Point</th>
                        <th>Quantity</th>
                        <th>Action</th>
                    </thead>
                    <tbody>
                        @foreach($data as $each)
                        <tr>
                            <td>{{ ($data ->currentpage()-1) * $data ->perpage() + $loop->index + 1 }}</td>
                            <td><img src='{{url("storage/".$each->image_name)}}' style="max-width: 200px;"
                                    class="img-rounded img-thumbnail" />
                            </td>
                            <td>{{$each->name}}</td>
                            <td>{{$each->description}}</td>
                            <td>{{$each->point}}</td>
                            <td>{{$each->quantity}}</td>
                            <td>
                                <div class="row justify-content-center">
                                    <a href="{{route('souvenir.edit', ['id' => $each->id])}}"
                                        class="btn btn-primary col-md-4 mx-1 my-1">Edit</a>
                                    <input id="{{$each->id}}" class="btn btn-danger button-delete col-md-4 mx-1 my-1"
                                        value="Delete">
=======
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
                            <td><img src='{{$souvenir->image_name}}' style="max-width: 200px;"
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
>>>>>>> rilisv1
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
<<<<<<< HEAD
                {{$data -> links("pagination::bootstrap-4")}}
=======
                <div class="text-right">
                    {{$souvenirs->links()}}
                </div>
>>>>>>> rilisv1
            </div>
        </div>
    </div>
</div>
@endsection
@push('script')
<<<<<<< HEAD
<script>
    $(document).on('click', '.button-delete', function (e) {
        e.preventDefault();
        var id = $(this).attr('id');
        Swal.fire({
            title: 'Apakah Anda Yakin Menghapus Data Ini ?',
            text: "Pastikan Data Yang Akan Anda Hapus Benar",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya',
            cancelButtonText: 'Batal!',
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'souvenir/' + id,
                    type: "POST",
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: id,
                        _method: 'DELETE'
                    },
                    success: function (data) {
                        Swal.fire(
                            'Berhasil',
                            'Data Anda Berhasil Dihapus',
                            'success')
                        location.reload();
                    },
                })
            } else if (
                result.dismiss === Swal.DismissReason.cancel
            ) {
                Swal.fire(
                    'Dibatalkan',
                    'Data anda tidak terhapus',
                    'error'
                )
            };
        });
    });

</script>
=======
>>>>>>> rilisv1
@endpush
