@extends('layouts.main')
@section('title')
Jadwal Rute {{$area->name}}
@endsection
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Jadwal Rute {{$area->name}} Form</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">Jadwal Rute {{$area->name}}</li>
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
                    <form action="@isset($fleet_route_price)
                        {{route('fleet_route_prices.update', $fleet_route_price->id)}}
                    @endisset @empty($fleet_route_price) {{route('fleet_route_prices.store', ['area_id' => @request()->area_id])}} @endempty"
                        method="POST">
                        @csrf
                        @isset($fleet_route_price)
                        @method('PUT')
                        @endisset
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Tanggal Awal</label>
                                    <input type="date" class="form-control" name="start_at" required
                                        value="{{isset($fleet_route_price) ? $fleet_route_price->start_at : ''}}">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>Tanggal Akhir</label>
                                    <input type="date" class="form-control" name="end_at" required
                                        value="{{isset($fleet_route_price) ? $fleet_route_price->end_at : ''}}">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Rute Armada</label>
                            <select id="" class="select2 form-control" @empty($fleet_route_price) multiple
                                name="fleet_route_id[]" @endempty @isset($fleet_route_price) name="fleet_route_id"
                                @endisset required @isset($fleet_route_price) readonly @endisset>
                                @foreach ($fleet_routes as $fleet_route)
                                <option value="{{$fleet_route->id}}" @isset($fleet_route_price) @if($fleet_route_price->
                                    fleet_route_id == $fleet_route->id)
                                    selected
                                    @endif
                                    @endisset>
                                    {{$fleet_route->fleet_detail?->fleet?->name}}/{{$fleet_route->fleet_detail?->fleet?->fleetclass?->name}}
                                    ({{$fleet_route->fleet_detail?->nickname}})
                                    ({{$fleet_route->route?->name}})
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Perubahan Harga</label>
                            <input type="number" name="deviation_price" required class="form-control"
                                value="{{isset($fleet_route_price) ? $fleet_route_price->deviation_price : ''}}"
                                placeholder="Masukkan Harga">
                        </div>
                        <div class="form-group">
                            <label>Deskripsi</label>
                            <textarea name="note" class="form-control"
                                rows="5">{{isset($fleet_route_price) ? $fleet_route_price->note : ''}}</textarea>
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select name="color" class="form-control" required>
                                <option value="">Pilih Tipe Harga</option>
                                <option value="blue" @isset($fleet_route_price) @if ($fleet_route_price->color ==
                                    'blue')
                                    selected
                                    @endif @endisset>Harga Normal</option>
                                <option value="red" @isset($fleet_route_price) @if ($fleet_route_price->color ==
                                    'red')
                                    selected
                                    @endif @endisset>Penurunan Harga
                                </option>
                                <option value="green" @isset($fleet_route_price) @if ($fleet_route_price->color ==
                                    'green')
                                    selected
                                    @endif @endisset>Kenaikan Harga
                                </option>
                            </select>
                        </div>
                        <div class="mt-2">
                            <a href="{{URL::previous()}}" class="btn btn-secondary">Batal</a>
                            <input type="submit" value="Submit" class="btn btn-success float-right">
                        </div>
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
    $('.input-daterange input').each(function () {
        $(this).datepicker('clearDates');
    });
</script>
@endpush