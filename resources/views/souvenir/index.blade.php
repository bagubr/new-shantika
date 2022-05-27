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
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{$data -> links("pagination::bootstrap-4")}}
            </div>
        </div>
    </div>
</div>
@endsection
@push('script')
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
@endpush
