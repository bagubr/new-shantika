@extends('layouts.main')
@section('title')
Fleet
@endsection
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Kelas Armada Form</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">Kelas Armada</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>
<section class="content">
    <div class="row">
        <div class="col-12 col-md-6">
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
                    <form action="@isset($fleetclass)
                        {{route('fleetclass.update', $fleetclass->id)}}
                    @endisset @empty($fleetclass) {{route('fleetclass.store')}} @endempty" method="POST">
                        @csrf
                        @isset($fleetclass)
                        @method('PUT')
                        @endisset
                        <div class="form-group">
                            <label for="inputName">Nama Kelas Armada</label>
                            <input type="text" id="inputName" class="form-control" name="name"
                                value="{{isset($fleetclass) ? $fleetclass->name : ''}}">
                        </div>
                        <div class="form-group">
                            <label for="">Harga Makanan</label>
                            <input type="number" name="price_food" class="form-control"
                                value="{{isset($fleetclass) ? $fleetclass->price_food : ''}}">
                        </div>
                        <a href="{{URL::previous()}}" class="btn btn-secondary">Batal</a>
                        <input type="submit" value="Tambah" class="btn btn-success float-right">
                    </form>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Form Harga</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    @if(isset($fleetclass))
                    <form method="POST" action="{{route('fleet_class_price.store')}}">
                        @csrf
                        <input type="hidden" name="fleet_class_id" value="{{$fleetclass->id}}">
                        <div class="form-group">
                            <label for="">Area</label>
                            <select name="area_id" class="form-control" id="">
                                @foreach($areas as $area)
                                <option value="{{$area->id}}">{{$area->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Harga</label>
                            <small style="color: red">* Masukan harga setelah di tambahkan harga makan</small>
                            <input type="text" name="price" class="form-control" id="">
                        </div>
                        <div class="form-group">
                            <label for="">Dimulai Tanggal</label>
                            <input type="date" name="start_at" class="form-control" id="">
                        </div>
                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                    
                    <table class="table table-warning table-striped">
                        <thead>
                            <tr>
                                <th>Area</th>
                                <th>Harga Tiket + Makan</th>
                                <th>Harga Tiket</th>
                                <th>Berlaku dari</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($fleetclass->prices as $price)
                            <tr>
                                <td>{{$price->area->name}}</td>
                                <td>Rp. {{number_format($price->price, 2)}}</td>
                                <td>Rp. {{number_format($price->price - $fleetclass->price_food, 2)}}</td>
                                <td>{{date('l, d F Y', strtotime($price->start_at))}}</td>
                                <td>
                                    <form action="{{route('fleet_class_price.destroy', $price->id)}}" method="POST">
                                        @csrf
                                        @method("DELETE")
                                        <button type="submit" class="badge badge-danger border-0">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection