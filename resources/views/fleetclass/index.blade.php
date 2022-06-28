@extends('layouts.main')
@section('title')
Kelas Armada
@endsection
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Kelas Armada</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">Kelas Armada</li>
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
                        <h3 class="card-title">Table Kelas Armada</h3>
                        @unlessrole('owner')

                        <div class="text-right">
                            <a href="{{route('fleetclass.create')}}" class="btn btn-primary btn-sm">Tambah</a>
                        </div>
                        @endunlessrole
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Harga Makanan</th>
<<<<<<< HEAD
                                    <th>Harga Jawa Kelas Armada</th>
                                    <th>Harga Jabodetabek Kelas Armada</th>
=======
                                    @foreach ( \App\Models\Area::get() as $area )
                                        <th>
                                            Harga Tiket (Harga Total) {{$area->name}}
                                        </th>
                                    @endforeach
>>>>>>> rilisv1
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($fleetclasses as $fleetclass)
                                <tr>
                                    <td>{{$fleetclass->name}}</td>
                                    <td>Rp. {{number_format($fleetclass->price_food)}}</td>
<<<<<<< HEAD
                                    <td>Rp. {{number_format($fleetclass->price_fleet_class1)}}</td>
                                    <td>Rp. {{number_format($fleetclass->price_fleet_class2)}}</td>
=======
                                    @foreach ( \App\Models\Area::get() as $area )
                                        <td>Rp. {{number_format($fleetclass->price_fleet_class($area->id) - $fleetclass->price_food)}} (Rp. {{number_format($fleetclass->price_fleet_class($area->id))}})</td>
                                    @endforeach
>>>>>>> rilisv1
                                    <td>
                                        @unlessrole('owner')

                                        <a href="{{route('fleetclass.edit',$fleetclass->id)}}"
                                            class="btn btn-warning btn-xs">Edit</a>
                                        <a class="btn btn-danger btn-xs button-delete"
                                            data-id="{{$fleetclass->id}}">Delete</a>
                                        @endunlessrole
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
<script>
    $(document).on('click', '.button-delete', function (e) {
        e.preventDefault();
        var id = $(this).data('id');
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
                    url: 'fleetclass/' + id,
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