@extends('layouts.main')
@section('title')
Armada
@endsection
@push('css')
<link rel="stylesheet" href="{{asset('css/lightbox.min.css')}}">
@endpush
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Armada</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">Armada</li>
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
                        <h3 class="card-title">Table Armada</h3>
                        <div class="text-right">
                            @unlessrole('owner')

                            <a href="{{route('fleets.create')}}" class="btn btn-primary btn-sm">Tambah</a>
                            @endunlessrole
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Kode Armada</th>
                                    <th>Unit Armada</th>
                                    <th>Kelas Armada</th>
                                    <th>Layout</th>
                                    <th>Gambar</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($fleets as $fleet)
                                <tr>
                                    <td>{{$fleet->name}}</td>
                                    <td>
                                        @foreach ($fleet->fleet_detail as $detail)
<<<<<<< HEAD
                                        <li>
                                            {{$detail->nickname}} ({{$detail->plate_number}})
                                        </li>
=======
                                        
                                        <a href="{{route('fleet_detail.edit',$detail->id)}}" target="_blank">
                                        <li>
                                            {{$detail->nickname}} ({{$detail->plate_number}})
                                        </li>
                                        </a>
>>>>>>> rilisv1
                                        @endforeach
                                    </td>
                                    <td>
                                        <a href="{{route('fleetclass.edit', $fleet->fleet_class_id)}}"
                                            target="_blank">{{$fleet->fleetclass?->name ?? '-'}}</a>
                                    </td>
                                    <td>
                                        <a href="{{route('layouts.edit', $fleet->layout_id)}}"
                                            target="_blank">{{$fleet->layout?->name ?? '-'}}</a>
                                    </td>
                                    <td>
                                        @if ($fleet->image)
                                        <a href="{{$fleet->image}}" data-toggle="lightbox">
                                            <img src="{{$fleet->image}}" height="100px" alt="">
                                            @else
                                            Tidak Ada Gambar
                                        </a>
                                        @endif
                                    </td>
                                    <td>
                                        @unlessrole('owner')
                                        <a href="{{route('fleets.edit',$fleet->id)}}"
                                            class="btn btn-warning btn-xs">Edit</a>
                                        @endunlessrole
                                        <a href="{{route('fleets.show',$fleet->id)}}"
                                            class="btn btn-info btn-xs">Show</a>
                                        @unlessrole('owner')
                                        <form action="{{route('fleets.destroy',$fleet->id)}}" class="d-inline"
                                            method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-xs"
                                                onclick="return confirm('Apakah Anda Yakin  Menghapus Data Ini??')"
                                                type="submit">Delete</button>
                                        </form>
<<<<<<< HEAD
=======
                                        <a href="{{route('fleet_detail.create', ['fleet_id' => $fleet->id])}}"
                                            class="btn btn-outline-primary btn-xs">Tambah Unit</a>
>>>>>>> rilisv1
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
                    url: 'fleets/' + id,
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