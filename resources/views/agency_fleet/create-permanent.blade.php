@extends('layouts.main')
@section('title')
Armada Agen Permanent
@endsection
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Armada Agen Permanent Form</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">Armada Agen Permanent</li>
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
                    <form action="{{route('agency_fleet_permanent.store')}}" method="POST">
                        @csrf
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <input type="hidden" name="fleet_id" id="" value="{{ $fleet->id }}">
                                    <label>Armada</label><span class="text-danger">*</span>
                                    <input type="text" name="fleet_name" id="" value="{{ $fleet->name }}" disabled class="form-control">
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
                            </div>
                        </div>
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
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($agency_fleet_permanents as $agency_fleet_permanent)
                                        <tr>
                                            <td>{{$agency_fleet_permanent->agency->name}}</td>
                                            <td>    
                                                <form action="{{route('agency_fleet_permanent.destroy', $agency_fleet_permanent->id)}}" class="d-inline"
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