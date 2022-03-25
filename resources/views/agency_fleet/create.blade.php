@extends('layouts.main')
@section('title')
Armada Agen
@endsection
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Armada Agen Form</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">Armada Agen</li>
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
                    <form action="@isset($agency_fleet)
                        {{route('agency_fleet.update', $agency_fleet->id)}}
                    @endisset @empty($agency_fleet) {{route('agency_fleet.store')}} @endempty" method="POST">
                        @csrf
                        @isset($agency_fleet)
                        @method('PUT')
                        @endisset
                        <div class="form-group">
                            <label>Agent</label><span class="text-danger">*</span>
                            <select name="agency_id" class="form-control select2" id="">
                                <option value="">Pilih Agent</option>
                                @foreach ($agencies as $agency)
                                <option value="{{$agency->id}}" 
                                    @isset($agency_fleet) 
                                        @if ($agency->id == $agency_fleet->agency_id) selected
                                        @endif
                                    @endisset>{{$agency->city->name}}/{{$agency->name}}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Armada</label><span class="text-danger">*</span>
                            <select name="fleet_id" class="form-control select2" id="">
                                <option value="">Pilih Armada</option>
                                @foreach ($fleets as $fleet)
                                <option value="{{$fleet->id}}" 
                                    @isset($agency_fleet) 
                                        @if ($fleet->id == $agency_fleet->fleet_id) selected
                                        @endif
                                    @endisset>{{$fleet->name}}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <a href="{{URL::previous()}}" class="btn btn-secondary">Batal</a>
                        <input type="submit" value="Submit" class="btn btn-success float-right">
                    </form>
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
@endpush