@extends('layouts.main')
@section('title')
Armada
@endsection
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Armada Form</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">Armada</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Form</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body" style="display: block;">
                    @include('partials.error')
                    <form action="@isset($fleet)
                        {{route('fleets.update', $fleet->id)}}
                    @endisset @empty($fleet) {{route('fleets.store')}} @endempty" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @isset($fleet)
                        @method('PUT')
                        @endisset
                        <div class="form-group">
                            <label for="inputName">Kode Armada</label>
                            <input type="text" id="inputName" class="form-control" name="name"
                                placeholder="Masukkan Kode" value="{{isset($fleet) ? $fleet->name : ''}}">
                        </div>
                        <div class="form-group">
                            <label>Deskripsi</label>
                            <textarea class="form-control" rows="3" name="description"
                                placeholder="Masukkan Deskripsi">{{isset($fleet) ? $fleet->description : ''}}</textarea>
                        </div>
                        <div class="form-group">
                            <label>Armada Layout</label>
                            <select class="form-control select2" name="layout_id" style="width: 100%;">
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
                            <select class="form-control select2" name="fleet_class_id" style="width: 100%;">
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
<<<<<<< HEAD
                            <label for="">Gambar</label>
=======
                            <label for="">Gambar</label><br>
                            <i style="color: red">Max 2 mb</i>
>>>>>>> rilisv1
                            <input type="file" accept="image/*" class="form-control" name="image">
                        </div>
                        <a href="{{URL::previous()}}" class="btn btn-secondary">Batal</a>
                        <input type="submit" value="Tambah" class="btn btn-success float-right">
                    </form>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
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
@endpush