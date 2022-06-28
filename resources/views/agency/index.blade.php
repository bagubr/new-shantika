@extends('layouts.main')
@section('title')
Agen
@endsection
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Agen</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">Agen</li>
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
                    <form action="{{route('agency.search')}}" method="get">
                        <div class="card-body">
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
                            <div class="form-group">
                                <label>Search</label>
                                <input type="text" name="search" id="search" class="form-control" value="{{ @$search }}">
                            </div>
                        </div>
                        <div class="text-right m-2">
                            <button class="btn btn-success" type="submit">Cari</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Table Agen</h3>
                        <div class="text-right">
                            @unlessrole('owner')
                            <a href="{{route('agency.create')}}" class="btn btn-primary btn-sm">Tambah</a>
                            @endunlessrole
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
<<<<<<< HEAD
                        <table id="example1" class="table table-bordered table-striped">
=======
                        <table id="example1" class="table table-striped table-responsive">
>>>>>>> rilisv1
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Kode</th>
                                    <th>Kota</th>
                                    <th>Area</th>
                                    <th>Alamat</th>
                                    <th>No. Telepon</th>
                                    <th>Jam {{ implode(' | ', \App\Models\TimeClassification::name()->toArray()) }}</th>
                                    <th>Status</th>
                                    <th>Avatar</th>
                                    <th>Harga Sbg Agent</th>
                                    <th>Harga Sbg Rute</th>
                                    <th>Agent</th>
                                    <th>Rute</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($agencies as $agency)
                                <tr>
                                    <td>{{$agency->name}}</td>
                                    <td>{{$agency->code}}</td>
                                    <td>{{$agency->city_name}}</td>
                                    <td>{{$agency->area_name}}</td>
                                    <td>{{$agency->address}}</td>
                                    <td>{{$agency->phone ?? '-'}}</td>
                                    <td>
                                        {{ implode(' | ', $agency->time_group->toArray()) }}
                                    </td>
                                    @if ($agency->is_active == 1)
                                    <td data-toggle="modal" data-target="#exampleModal{{$agency->id}}"
                                        class="text-success text-bold pointer">
                                        Aktif
                                    </td>
                                    @else
                                    <td data-toggle="modal" data-target="#exampleModal{{$agency->id}}"
                                        class="text-danger text-bold">
                                        Non Aktif
                                    </td>
                                    @endif
                                    <td>
                                        <a href="{{$agency->avatar_url}}" data-toggle="lightbox">
                                            <img src="{{$agency->avatar_url}}" style="height: 100px">
                                        </a>
                                    </td>
                                    <td>
                                        {{ number_format(@$agency->price_agency) ?: 'Belum di set' }}
                                    </td>
                                    <td>
                                        {{ number_format(@$agency->price_route) ?: 'Belum di set' }}
                                    </td>
                                    @if($agency->is_agent)
                                        <td>Ya</td>
                                    @else
<<<<<<< HEAD
                                        <td>Tidak Tidak</td>
=======
                                        <td>Tidak</td>
>>>>>>> rilisv1
                                    @endif
                                    @if($agency->is_route)
                                        <td>Ya</td>
                                    @else
                                        <td>Tidak</td>
                                    @endif
                                    <td>
                                        @unlessrole('owner')
                                        <a href="{{route('agency.edit',$agency->id)}}"
                                            class="btn btn-warning btn-xs">Edit</a>
                                        <a href="{{route('agency_price.show',$agency->id)}}"
                                            class="btn btn-primary btn-xs">Edit Harga</a>
                                        <a class="btn btn-danger btn-xs button-delete"
                                            data-id="{{$agency->id}}">Delete</a>
                                        @endunlessrole
                                    </td>
                                </tr>
                                <div class="modal fade" id="exampleModal{{$agency->id}}" tabindex="-1" role="dialog"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Ubah Status
                                                    {{$agency->name}}</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="{{route('agency.update_status',$agency->id)}}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <select class="form-control input" name="is_active">
                                                            @foreach ($statuses as $s => $key)
                                                            <option value="{{$s}}" @if ($s==$agency->is_active)
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
<<<<<<< HEAD
                        {{$agencies->appends(request()->query())->links("pagination::bootstrap-4")}}
=======
                        <div class="float-right mt-3">
                            {{$agencies->appends(request()->query())->links("pagination::bootstrap-4")}}
                        </div>
>>>>>>> rilisv1
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
<<<<<<< HEAD
        "searching": false, "responsive": true, "lengthChange": false, "autoWidth": false,
=======
        "responsive": true, "lengthChange": false, "autoWidth": false, "paging":false, "searching":false
>>>>>>> rilisv1
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
                    url: 'agency/' + id,
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