@extends('layouts.main')
@section('title')
Rute Agen Temporary
@endsection
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Rute Agen Temporary Form</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">Rute Agen Temporary</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>
<section class="content">
    <div class="row">
        <div class="col">
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
                    <form action="{{route('agency_route.store')}}" method="POST">
                        @csrf
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <input type="hidden" name="route_id" id="" value="{{ $route->id }}">
                                    <label>Rute</label><span class="text-danger">*</span>
                                    <input type="text" name="route_name" id="" value="{{ $route->name }}" disabled class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>Agent</label><span class="text-danger">*</span>
                                    <select name="agency_id" class="form-control select2" id="" required>
                                        <option value="">Pilih Agent</option>
                                        @foreach ($agencies as $agency)
                                        <option value="{{$agency->id}}">{{$agency->city->name}}/{{$agency->name}}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-4">
                                    <label for="">Mulai Dari</label>
                                    <input type="date" name="start_at" class="form-control" id="" required>
                                </div>
                                <div class="form-group col-4">
                                    <label for="">Selesai</label>
                                    <input type="date" name="end_at" class="form-control" id="" required>
                                </div>
                            </div>
                        </div>
                        <a href="{{route('agency_route.index', ['area_id' => $route->checkpoints[0]?->agency?->city?->area?->id])}}" class="btn btn-secondary">Batal</a>
                        <input type="submit" value="Submit" class="btn btn-success float-right">
                    </form>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Table</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body" style="display: block;">
                    <div class="col">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Table Agen</h3>
                                <div class="text-right">
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Agen</th>
                                            <th>Start</th>
                                            <th>End</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($agency_routes as $agency_route)
                                        <tr>
                                            <td>{{$agency_route->agency->name}}</td>
                                            <td>{{ date('Y-m-d', strtotime($agency_route->start_at)) }}</td>
                                            <td>{{ date('Y-m-d', strtotime($agency_route->end_at)) }}</td>
                                            <td>    
                                                <form action="{{route('agency_route.destroy', $agency_route->id)}}" class="d-inline"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-danger btn-xs"
                                                        onclick="return confirm('Apakah Anda yakin akan menghapus data ini?')"
                                                        type="submit">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@push('script')
<script src="{{asset('plugins/summernote/summernote-bs4.min.js')}}"></script>
<script>
    $(function () {
        $('.select2').select2()
    })
    $('.select2bs4').select2({
        theme: 'bootstrap4'
    })

</script>
<script>
$(document).ready(function () {
    $.ajax({
        url: "/agency/all",
        type: "get",
        success: function (response) {
            $("#addRow").removeClass("d-none");
            $("#refresh").removeClass("d-none");
            var options;
            $.each(response.agencies, function (index, value) {
                options = options + '<option value="' + value.id + '">' + '(' + value.city_name + ') ' + value.name + '</option>';
            });
            var skillhtml = '<select class="form-control select2" name="agency_id[]" required>' + options + '</select>';
            $("#container").html(skillhtml);


            $("#addRow").click(function (value) {
                var html = '';
                html += '<div id="inputFormRow">';
                html += '<div class="input-group mb-3">';
                html += '<select name="agency_id[]" class="form-control myselect" required>';
                html += options + '<option value="' + value.id + '">' + '(' + value.city_name + ') ' + value.name + '</option>';
                html += '<select>';
                html += '<div class="input-group-append">';
                html += '<button id="removeRow" type="button" class="btn btn-danger">Hapus</button>';
                html += '</div>';
                html += '</div>';
                $('#container2').append(html);
                if ($('.myselect').length > 0) {
                    $('.myselect').select2();
                };
            });
            $(document).on('click', '#removeRow', function () {
                $(this).closest('#inputFormRow').remove();
            });
            if ($('.select2').length > 0) {
                $('.select2').select2();
            };
        },
        error: function (xhr) {
            console.log(xhr)
        }
    });
});
</script>
@endpush