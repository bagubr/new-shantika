@extends('layouts.main')
@section('title')
Jadwal Tidak Beroperasi
@endsection
@push('css')
<link rel="stylesheet" href="{{asset('plugins/summernote/summernote-bs4.min.css')}}">
@endpush
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Jadwal Tidak Beroperasi Form</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">Jadwal Tidak Beroperasi</li>
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
                    <form action="@isset($schedule_not_operate)
                        {{route('schedule_not_operate.update', $schedule_not_operate->id)}}
                    @endisset @empty($schedule_not_operate) {{route('schedule_not_operate.store')}} @endempty"
                        method="POST">
                        @csrf
                        @isset($schedule_not_operate)
                        @method('PUT')
                        @endisset
                        <div class="form-group">
                            <label>Rute</label>
                            <select name="route_id" id="" class="form-control select2">
                                <option value="">Pilih Rute</option>
                                @foreach ($routes as $route)
                                <option value="{{$route->id}}">{{$route->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Catatan</label>
                            <textarea name="note" class="form-control"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Tanggal</label>
                            <div id="inputFormRow">
                                <div class="input-group mb-3">
                                    <input type="date" name="schedule_at[]" class="form-control"
                                        placeholder="Masukkan Tanggal">
                                    <div class="input-group-append">
                                        <button id="removeRow" type="button" class="btn btn-danger">Hapus</button>
                                    </div>
                                </div>
                            </div>
                            <div id="newRow"></div>
                            <button id="addRow" type="button" class="btn btn-info">Tambah Tanggal</button>
                        </div>


                        <a href="{{URL::previous()}}" class="btn btn-secondary">Batal</a>
                        <input type="submit" value="Submit" class="btn btn-success float-right">
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
<script type="text/javascript">
    // add row
    $("#addRow").click(function () {
        var html = '';
        html += '<div id="inputFormRow">';
        html += '<div class="input-group mb-3">';
        html += '<input type="date" name="schedule_at[]" class="form-control" placeholder="Masukkan Tanggal">';
        html += '<div class="input-group-append">';
        html += '<button id="removeRow" type="button" class="btn btn-danger">Hapus</button>';
        html += '</div>';
        html += '</div>';

        $('#newRow').append(html);
    });

    // remove row
    $(document).on('click', '#removeRow', function () {
        $(this).closest('#inputFormRow').remove();
    });
</script>
@endpush