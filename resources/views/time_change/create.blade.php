@extends('layouts.main')
@section('title')
Time Change
@endsection
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Time Change Form</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">Time Change</li>
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
                    <form action="@isset($time_change_route)
                        {{route('time_change_route.update', $time_change_route->id)}}
                    @endisset @empty($time_change_route) {{route('time_change_route.store')}} @endempty"
                        method="POST">
                        @csrf
                        @isset($time_change_route)
                        @method('PUT')
                        @endisset
                        <div class="form-group">
                            <label>Rute Armada</label>
                            <select id="" class="select2 form-control" name="fleet_route_id" required>
                                <option value="">PILIH</option>
                                @foreach ($fleet_routes as $fleet_route)
                                <option value="{{$fleet_route->id}}" 
                                    @isset($time_change_route) 
                                    @if($time_change_route->fleet_route_id == $fleet_route->id) selected @endif
                                    @endisset>
                                    {{$fleet_route->fleet_detail?->fleet?->name}}/{{$fleet_route->fleet_detail?->fleet?->fleetclass?->name}}
                                    ({{$fleet_route->fleet_detail?->nickname}})
                                    ({{$fleet_route->route?->name}})
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-row">
                            <div class="col-2">
                                <div class="form-group">
                                    <label>Tanggal Berlaku</label>
                                    <input type="date" class="form-control" name="date"
                                        value="{{isset($time_change_route) ? $time_change_route->date : ''}}" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Waktu Keberangkatan</label>
                                    <select id="" class="select2 form-control" name="time_classification_id" required>
                                        <option value="">PILIH</option>
                                        @foreach ($time_classifications as $time_classification)
                                        <option value="{{$time_classification->id}}" 
                                            @isset($time_change_route) 
                                            @if($time_change_route->fleet_route_id == $time_classification->id) selected @endif
                                            @endisset>{{ $time_classification->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <a href="{{URL::previous()}}" class="btn btn-secondary">Batal</a>
                        <input onclick="return confirm('Apakah Anda Yakin (Proses yang terjadi tidak dapat di kembalikan)?? ')" type="submit" value="Submit" class="btn btn-success float-right">
                    </form>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>
</section>
@endsection