@extends('layouts.main')
@section('title')
Harga Agen
@endsection
@push('css')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
    integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
    crossorigin="" />
<style>
    #mapid {
        height: 500px;
    }
</style>
@endpush
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Harga Agen Form</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">Harga Agen</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>
<section class="content">
    <div class="row">
        @isset($agency)
        <div class="col-md-6">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Harga Tiket Sebagai Agen Keberangkatan</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body" style="display: block;">
                    <form action="{{route('agency_price.store')}}" method="post" class="row">
                        @csrf
                        <input type="hidden" name="agency_id" value="{{@$agency->id}}">
                        <div class="form-group col-4">
                            <label for="">Harga</label>
                            <input type="number" name="agency_price" class="form-control" id="">
                        </div>
                        <div class="form-group col-4">
                            <label for="">Mulai Dari</label>
                            <input type="datetime-local" name="start_at_agency" class="form-control" id="">
                        </div>
                        <div class="form-group col-4 align-self-center">
                            <button type="submit" class="btn btn-success w-100">Submit</button>
                        </div>
                    </form>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Harga</th>
                                <th>Mulai Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($agency))
                            @foreach($agency_price as $price)
                            <tr>
                                <td>Rp. {{number_format($price->price)}}</td>
                                <td>{{date('d F Y', strtotime($price->start_at))}}</td>
                                <td>
                                    <form action="{{route('agency_price.destroy', $price->id)}}" method="POST">
                                        @method('delete')
                                        @csrf
                                        <button type="submit" class="badge badge-danger border-0">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Harga Tiket Sebagai Rute</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body" style="display: block;">
                    <form action="{{route('route_price.store')}}" method="post" class="row">
                        @csrf
                        <input type="hidden" name="agency_id" value="{{@$agency->id}}">
                        <div class="form-group col-4">
                            <label for="">Harga</label>
                            <input type="number" name="route_price" class="form-control" id="">
                        </div>
                        <div class="form-group col-4">
                            <label for="">Mulai Dari</label>
                            <input type="datetime-local" name="start_at_route" class="form-control" id="">
                        </div>
                        <div class="form-group col-4 align-self-center">
                            <button type="submit" class="btn btn-success w-100">Submit</button>
                        </div>
                    </form>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Harga</th>
                                <th>Mulai Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($agency))
                            @foreach($route_price as $price)
                            <tr>
                                <td>Rp. {{number_format($price->price)}}</td>
                                <td>{{date('d F Y', strtotime($price->start_at))}}</td>
                                <td>
                                    <form action="{{route('route_price.destroy', $price->id)}}" method="POST">
                                        @method('delete')
                                        @csrf
                                        <button type="submit" class="badge badge-danger border-0">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
        @endisset
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
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
    integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
    crossorigin=""></script>
@if (Request::routeIs('agency.create') || $agency->lat == null || $agency->lng == null)
<script>
    var mymap = L.map('mapid').locate({setView: true});
        L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
            maxZoom: 20,
            attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, ' +
                'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
            id: 'mapbox/streets-v11',
            tileSize: 512,
            zoomOffset: -1,
            accessToken: 'pk.eyJ1Ijoic2F0cmlvdG9sIiwiYSI6ImNrc3E1YTh0NzAzdWMyb3BicTUxbnMxY3YifQ.XfiYl1qOEFzjRsPs3TDijw'
        }).addTo(mymap);
        L.marker([document.getElementById("lat").value, document.getElementById("lng").value]).addTo(mymap)
            .bindPopup('Lokasi Agen Anda.')
            .openPopup();
        var popup = L.popup();
        function onMapClick(e) {
            popup
            .setLatLng(e.latlng)
            .setContent("Pastikan Lokasi Anda Benar " + e.latlng.toString())
            .openOn(mymap);
            document.getElementById("lat").value = e.latlng.lat;
            document.getElementById("lng").value = e.latlng.lng;
        }    
        mymap.on('click', onMapClick);
</script>
@else
<script>
    var lat = {{$agency->lat}};
    var lng = {{$agency->lng}}
    console.log(lat,lng);
    var mymap = L.map('mapid').setView([lat, lng], 13);
    L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
        maxZoom: 18,
        id: 'mapbox/streets-v11',
        tileSize: 512,
        zoomOffset: -1,
        accessToken: 'pk.eyJ1Ijoic2F0cmlvdG9sIiwiYSI6ImNrc3E1YTh0NzAzdWMyb3BicTUxbnMxY3YifQ.XfiYl1qOEFzjRsPs3TDijw'
    }).addTo(mymap);
    L.marker([document.getElementById("lat").value, document.getElementById("lng").value]).addTo(mymap)
            .bindPopup('Lokasi Agen Anda.')
            .openPopup();
        var popup = L.popup();
        function onMapClick(e) {
            popup
            .setLatLng(e.latlng)
            .setContent("Pastikan Lokasi Anda Benar " + e.latlng.toString())
            .openOn(mymap);
            document.getElementById("lat").value = e.latlng.lat;
            document.getElementById("lng").value = e.latlng.lng;
        }    
        mymap.on('click', onMapClick);
</script>
@endif

@endpush