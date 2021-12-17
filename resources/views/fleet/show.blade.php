@extends('layouts.main')
@section('title')
Armada
@endsection
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Armada Detail</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">Armada Detail</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>
<section class="content">
    <div class="row">
        <div class="col-md-6">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Detail Armada </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body" style="display: block;">
                    <div class="form-group">
                        <label for="inputName">Kode Armada</label>
                        <input type="text" id="inputName" class="form-control" name="name" placeholder="Masukkan Kode"
                            value="{{isset($fleet) ? $fleet->name : ''}}" readonly>
                    </div>
                    <div class="form-group">
                        <label>Deskripsi</label>
                        <textarea class="form-control" rows="3" name="description" placeholder="Masukkan Deskripsi"
                            readonly>{{isset($fleet) ? $fleet->description : ''}}</textarea>
                    </div>
                    <div class="form-group">
                        <label>Armada Layout</label>
                        <select class="form-control select2" name="layout_id" style="width: 100%;" disabled>
                            <option value="">Pilih Armada Layout</option>
                            @foreach ($layouts as $layout)
                            <option value="{{$layout->id}}" @isset($fleet) @if ($layout->id ===
                                $fleet->layout_id)
                                selected
                                @endif @endisset>{{$layout->name}}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Kelas Armada</label>
                        <select class="form-control select2" name="fleet_class_id" style="width: 100%;" disabled>
                            <option value="">Pilih Kelas Armada</option>
                            @foreach ($fleetclasses as $fleetclass)
                            <option value="{{$fleetclass->id}}" @isset($fleet) @if ($fleetclass->id ===
                                $fleet->fleet_class_id)
                                selected
                                @endif @endisset>{{$fleetclass->name}}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Gambar</label>
                        @if ($fleet->image)
                        <a href="{{$fleet->image}}" data-toggle="lightbox">
                            <img src="{{$fleet->image}}" height="100px" alt="">
                            @else
                            Tidak Ada Gambar
                            @endif
                        </a>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <div class="col-md-6">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Detail Armada </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body" style="display: block;">
                    @include('partials.error')
                    <form action="{{route('fleet_detail.store')}}" method="POST">
                        @csrf
                        <input type="hidden" id="inputName" class="form-control" name="fleet_id" value="{{$fleet->id}}">
                        <div id="dynamic_field">
                            <div class="form-group">
                                <label for="inputName">Nama Julukan</label>
                                <input type="text" id="inputName" class="form-control" name="nickname[]"
                                    placeholder="Masukkan Nama Julukan">
                            </div>
                            <div class="form-group">
                                <label for="inputName">Nomor Plat</label>
                                <input type="text" id="inputName" class="form-control" name="plate_number[]"
                                    placeholder="Masukkan Nomor Plat">
                            </div>
                            <div class="form-group">
                                <label for="">Shift</label>
                                <select name="time_classification_id" class="form-control">
                                    @foreach($time_classifications as $item)
                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Armada</label>
                                <select class="form-control select2" name="fleet_id[]" style="width: 100%;" disabled>
                                    <option value="">Pilih Armada</option>
                                    @foreach ($fleets as $fleet_now)
                                    <option value="{{$fleet_now->id}}" @isset($fleet) @if ($fleet_now->id ===
                                        $fleet->id)
                                        selected
                                        @endif @endisset>{{$fleet_now->name}}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @unlessrole('owner')
                        <button type="button" name="add" id="add" class="btn btn-success d-inline">Add More</button>
                        <input type="submit" value="Submit" class="btn btn-success float-right">
                        @endunlessrole
                    </form>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Table Detail Armada</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Kode Armada</th>
                                <th>Julukan Armada</th>
                                <th>Plat Nomor</th>
                                <th>Shift</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($fleet->fleet_detail as $fleet_detail)
                            <tr>
                                <td>{{$fleet_detail->fleet->name}}</td>
                                <td>{{$fleet_detail->nickname}}</td>
                                <td>{{$fleet_detail->plate_number}}</td>
                                <td>{{$fleet_detail->time_classification?->name ?? '-'}}</td>
                                <td>
                                    @unlessrole('owner')

                                    <a href="{{route('fleet_detail.edit',$fleet_detail->id)}}"
                                        class="btn btn-warning btn-xs">Edit</a>
                                    <form action="{{route('fleet_detail.destroy',$fleet_detail->id)}}" class="d-inline"
                                        method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-xs"
                                            onclick="return confirm('Apakah Anda yakin akan menghapus data armada??')"
                                            type="submit">Delete</button>
                                        @endunlessrole
                                    </form>
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
</section>
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
    $(document).ready(function(){  
     var i=0;  
     $('#add').click(function(){  
          i++;  
          $('#dynamic_field').append(`
                            <div id="row`+i+`">
                            <input type="hidden" id="inputName" class="form-control" name="fleet_id" value="{{$fleet->id}}">
                            <div class="form-group">
                                <label for="inputName">Nama Julukan</label>
                                <input type="text" id="inputName" class="form-control" name="nickname[]"
                                    placeholder="Masukkan Nama Julukan">
                            </div>
                            <div class="form-group">
                                <label for="inputName">Nomor Plat</label>
                                <input type="text" id="inputName" class="form-control" name="plate_number[]"
                                    placeholder="Masukkan Nama Julukan">
                            </div>
                            <div class="form-group">
                                <label>Armada</label>
                                <select class="form-control select2" name="fleet_id[]" style="width: 100%;" disabled>
                                    <option value="">Pilih Armada</option>
                                    @foreach ($fleets as $fleet_now)
                                    <option value="{{$fleet_now->id}}" @isset($fleet) @if ($fleet_now->id ===
                                        $fleet->id)
                                        selected
                                        @endif @endisset>{{$fleet_now->name}}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                            <button type="button" name="remove" id="`+i+`" class="badge bg-danger btn_remove d-inline">Remove</button>
                            </div>
                            </div>
                            `);  
     });  
     $(document).on('click', '.btn_remove', function(){  
         console.log($(this).attr("id"))
          var button_id = $(this).attr("id");   
          $('#row'+button_id+'').remove();  
     });
});  
</script>
@endpush