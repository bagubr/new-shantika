@extends('layouts.main')
@section('title')
Edit Detail Armada
@endsection
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit Detail Armada Form</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">Edit Detail Armada</li>
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
                    <h3 class="card-title">Edit Form</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body" style="display: block;">
                    @include('partials.error')
                    <form action="{{route('fleet_detail.update', $fleet_detail->id)}}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="inputName" class="form-control" name="fleet_id"
                            value="{{$fleet_detail->fleet_id}}">
                        <div class="form-group">
                            <label for="inputName">Nama Julukan</label>
                            <input type="text" id="inputName" class="form-control" name="nickname"
                                placeholder="Masukkan Nama Julukan" value="{{$fleet_detail->nickname}}">
                        </div>
                        <div class="form-group">
                            <label for="inputName">Nomor Plat</label>
                            <input type="text" id="inputName" class="form-control" name="plate_number"
                                placeholder="Masukkan Nomor Plat" value="{{$fleet_detail->plate_number}}">
                        </div>
                        <div class="form-group">
                            <label for="inputCoDriver">Co Driver</label>
                            <input type="text" id="inputCoDriver" class="form-control" name="co_driver"
                                placeholder="Masukkan Co Driver" value="{{$fleet_detail->co_driver}}">
                        </div>
                        <div class="form-group">
                            <label for="inputName">Shift</label>
                            <select name="time_classification_id" class="form-control" id="">
                                @foreach ($time_classifications as $item)
                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Armada</label>
                            <select class="form-control select2" name="fleet_id" style="width: 100%;" disabled>
                                <option value="">Pilih Armada</option>
                                @foreach ($fleets as $fleet_now)
                                <option value="{{$fleet_now->id}}" @isset($fleet_detail) @if ($fleet_now->id ===
                                    $fleet_detail->fleet?->id)
                                    selected
                                    @endif @endisset>{{$fleet_now->name}}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <a href="{{URL::previous()}}" class="btn btn-secondary">Batal</a>
                        <input type="submit" value="Update" class="btn btn-success float-right">
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