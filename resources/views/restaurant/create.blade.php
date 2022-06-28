@extends('layouts.main')
@section('title')
Restoran
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
                <h1>Restoran Form</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">Restoran</li>
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
                    <form action="@isset($restaurant)
                        {{route('restaurant.update', $restaurant->id)}}
                    @endisset @empty($restaurant) {{route('restaurant.store')}} @endempty" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @isset($restaurant)
                        @method('PUT')
<<<<<<< HEAD
                        @endisset
                        <div class="form-group">
                            <label>Nama Restoran</label>
=======
                        <input type="hidden" name="restaurant_id" value="{{$restaurant->id}}">
                        @endisset
                        <div class="form-group">
                            <label>Nama Restoran</label>
                            <small style="color: red">*</small>
>>>>>>> rilisv1
                            <input type="text" class="form-control" name="name" placeholder="Masukkan Nama"
                                value="{{isset($restaurant) ? $restaurant->name : ''}}" required>
                        </div>
                        <div class="form-group">
<<<<<<< HEAD
                            <label>Phone</label>
=======
                            <label>Username Admin</label>
                            <small style="color: red">*</small>
                            <input type="text" class="form-control" name="username" placeholder="Masukkan Username"
                                value="{{isset($restaurant) ? $restaurant->username : ''}}" required>
                        </div>
                        <div class="form-group">
                            <label>Email Admin</label>
                            <small style="color: red">*</small>
                            <input type="email" class="form-control" name="email" placeholder="Masukkan Email"
                                value="{{isset($restaurant) ? $restaurant->email : ''}}" required>
                        </div>
                        <div class="form-group">
                            <label>Phone</label>
                            <small style="color: red">*</small>
>>>>>>> rilisv1
                            <input type="text" class="form-control" name="phone" placeholder="Masukkan Nomor HP"
                                value="{{isset($restaurant) ? $restaurant->phone : ''}}" required>
                        </div>
                        <div class="form-group">
                            <label>Alamat</label>
<<<<<<< HEAD
                            <input type="text" class="form-control" name="address" placeholder="Masukkan Alamat"
                                value="{{isset($restaurant) ? $restaurant->address : ''}}" required>
                        </div>
=======
                            <small style="color: red">*</small>
                            <input type="text" class="form-control" name="address" placeholder="Masukkan Alamat"
                                value="{{isset($restaurant) ? $restaurant->address : ''}}" required>
                        </div>
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col">
                                    <label>Password</label>
                                    <small style="color: red">*
                                    @isset($restaurant) isi jika ingin di ubah @endisset
                                    </small>
                                    <input type="password" name="password" class="form-control">
                                </div>
                                <div class="col">
                                    <label>Konfirmasi Password</label>
                                    <small style="color: red">*</small>
                                    <input type="password" name="password_confirmation" class="form-control">
                                </div>
                            </div>
                        </div>
>>>>>>> rilisv1
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Koordinat Latitude</label>
<<<<<<< HEAD
                                    <input class="form-control" type="text" id="lat" name="lat"
                                        value="{{isset($restaurant) ? $restaurant->lat : ''}}">
=======
                                    <small style="color: red">*</small>
                                    <input class="form-control" type="text" id="lat" name="lat"
                                    value="{{isset($restaurant) ? $restaurant->lat : ''}}" required>
>>>>>>> rilisv1
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>Koordinat Longtitude</label>
<<<<<<< HEAD
                                    <input class="form-control" type="text" id="lng" name="long"
                                        value="{{isset($restaurant) ? $restaurant->long : ''}}">
=======
                                    <small style="color: red">*</small>
                                    <input class="form-control" type="text" id="lng" name="long"
                                        value="{{isset($restaurant) ? $restaurant->long : ''}}" required>
>>>>>>> rilisv1
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div id="mapid"></div>
                        </div>
                        <div class="form-group">
                            <label>Nama Bank</label>
<<<<<<< HEAD
=======
                            <small style="color: red">*</small>
>>>>>>> rilisv1
                            <input type="text" class="form-control" name="bank_name" placeholder="Masukkan Nama Bank"
                                value="{{isset($restaurant) ? $restaurant->bank_name : ''}}" required>
                        </div>
                        <div class="form-group">
                            <label>Nama Pemilik Bank</label>
<<<<<<< HEAD
=======
                            <small style="color: red">*</small>
>>>>>>> rilisv1
                            <input type="text" class="form-control" name="bank_owner"
                                placeholder="Masukkan Nama Pemilik Bank"
                                value="{{isset($restaurant) ? $restaurant->bank_owner : ''}}" required>
                        </div>
                        <div class="form-group">
                            <label>Nomor Rekening</label>
<<<<<<< HEAD
=======
                            <small style="color: red">*</small>
>>>>>>> rilisv1
                            <input type="number" class="form-control" name="bank_account"
                                placeholder="Masukkan Nomor Rekening"
                                value="{{isset($restaurant) ? $restaurant->bank_account : ''}}" required>
                        </div>
                        <div class="form-group">
                            <label>Gambar</label>
<<<<<<< HEAD
                            <input type="file" name="image" class="form-control" accept="image/*">
=======
                            <small style="color: red">*</small>
                            <input type="file" name="image" class="form-control" accept="image/*" required>
>>>>>>> rilisv1
                            @isset($restaurant)
                            <a href="{{$restaurant->image}}" data-toggle="lightbox">
                                <img src="{{$restaurant->image}}" style="height: 100px">
                            </a>
                            @endisset
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
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
    integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
    crossorigin=""></script>
<script>
    $(function () {
        $('.select2').select2()
    })
    $('.select2bs4').select2({
        theme: 'bootstrap4'
    })

</script>
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
@endpush