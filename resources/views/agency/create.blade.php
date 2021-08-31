@extends('layouts.main')
@section('title')
Agen
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
                <h1>Agen Form</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">Agen</li>
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
                    <form action="@isset($agency)
                        {{route('agency.update', $agency->id)}}
                    @endisset @empty($agency) {{route('agency.store')}} @endempty" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @isset($agency)
                        @method('PUT')
                        @endisset
                        <div class="form-group">
                            <label for="inputName">Nama Agen</label>
                            <input type="text" id="inputName" class="form-control" name="name"
                                placeholder="Masukkan Nama" required value="{{isset($agency) ? $agency->name : ''}}">
                        </div>
                        <div class="form-group">
                            <label for="inputName">Kode Agen</label>
                            <input type="text" id="inputName" class="form-control" name="code"
                                placeholder="Masukkan Kode" required value="{{isset($agency) ? $agency->code : ''}}">
                        </div>
                        <div class="form-group">
                            <label>Kota</label>
                            <select class="form-control select2" name="city_id" style="width: 100%;" required>
                                <option value="">Pilih Kota</option>
                                @foreach ($cities as $city)
                                <option value="{{$city->id}}" @isset($agency) @if ($city->id ===
                                    $agency->city_id)
                                    selected
                                    @endif @endisset>{{$city->name}}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Jam Keberangkatan Pagi</label>
                                    <input type="time" class="form-control" name="departure_at[1]" required
                                        value="{{isset($agency) ?$agency->agency_departure_times[1]->departure_at : ''}}">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>Jam Keberangkatan Malam</label>
                                    <input type="time" class="form-control" name="departure_at[0]"
                                        value="{{isset($agency) ?$agency->agency_departure_times[0]->departure_at : ''}}"
                                        required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Phone</label>
                            <input type="tel" name="phone" class="form-control" placeholder="Masukkan Nomor Telp" id=""
                                value="{{isset($agency) ? $agency->phone : ''}}" required>
                        </div>
                        <div class="form-group">
                            <label>Alamat</label>
                            <input type="text" name="address" class="form-control" placeholder="Masukkan Alamat" id=""
                                value="{{isset($agency) ? $agency->address : ''}}" required>
                        </div>
                        <div class="form-group">
                            <div id="mapid"></div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Koordinat Latitude</label>
                                    <input class="form-control" type="text" id="lat" name="lat" readonly
                                        value="{{isset($agency) ? $agency->lat : ''}}">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>Koordinat Longtitude</label>
                                    <input class="form-control" type="text" id="lng" name="lng" readonly
                                        value="{{isset($agency) ? $agency->lng : ''}}">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Avatar</label>
                            <input type="file" class="form-control" name="avatar" accept="image/*">
                            @isset($agency)
                            <a href="{{$agency->avatar_url}}" data-toggle="lightbox">
                                <img src="{{$agency->avatar_url}}" style="height: 100px">
                            </a>
                            @endisset
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