@extends('layouts.main')
@section('title')
    Sketch
@endsection
@push('css')
    <style>
        body {
            transform: scale(1);
            transform-origin: 0 0;
        }

    </style>
@endpush
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Sketch</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Sketch</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="content" id="app-sketch">
        <div class="container-fluid">
            <div class="card">
                <div class="row p-2">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Pilih Area</label>
                            <select v-model="filter.area_id" name="area_id" class="form-control" id="">
                                <option value="">--PILIH--</option>
                                @foreach (\App\Models\Area::get() as $area)
                                    <option value="{{ $area->id }}" selected>{{ $area->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Tanggal</label>
                            <input v-model="filter.date" type="date" name="date" id="" class="form-control"
                                value="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                </div>
                <div class="text-right m-2">
                    <button @click="searchOrders()" class="btn btn-success" type="submit">Cari</button>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card p-5">
                        <h3>
                            Data tidak di temukan
                        </h3>
                    </div>
                </div>
                <div v-for="order in result.orders" class="col-12 col-sm-6 col-lg-4 col-xl-3">
                    <div class="card h-100 p-2 shadow" data-toggle="modal" data-target="#modal-default">
                        <div class="card-body">
                            <div class="row m-1">
                                <div class="col-md-3 text-center">
                                    <i class="fas fa-bus" style="font-size: 2.5em; color: Mediumslateblue;"></i>
                                </div>
                                <div class="col-md-9">
                                    <b>@{{order.fleet_route?.fleet?.name}} (@{{order.fleet_route?.fleet?.fleetclass?.name}})</b>
                                    <br>
                                    <p style="font-size: 15px;">@{{order.fleet_route?.route?.name}}</p>
                                    <small class="font-italic font-weight-bold mt-4 tex-italic" style="font-size: 15px">
                                        @{{order.fleet_route?.route?.departure_at}} - @{{order.fleet_route?.route?.arrived_at}}
                                    </small>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-6">
                                    <p class="text-center mb-0">
                                        <i class="fas fa-users"></i>
                                        <br>
                                        <span>@{{order.order_detail_count || 0}} / @{{order.fleet_route?.fleet?.layout?.total_chairs}}</span>
                                    </p>
                                </div>
                                <div class="col-6">
                                    <p class="text-center mb-0">
                                        <i class="fas fa-money-bill-wave" style="font-size: 1em; color: Dodgerblue;"></i>
                                        <br>
                                        <span>Rp. @{{Intl.NumberFormat('id-ID').format(order.fleet_route?.price)}}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/vue@2"></script> --}}
    <script>
        var app = new Vue({
            el: '#app-sketch',
            data: {
                csrf_token: '{{ csrf_token() }}',
                data: {
                    areas: []
                },
                filter: {
                    date: "",
                    area_id: ""
                },
                result: {
                    orders: []
                }
            },
            methods: {
                searchOrders() {
                    let params = new URLSearchParams(this.filter)
                    fetch('/sketch/orders?'+params).then(res => res.json()).then(res => {
                        this.result.orders = res.orders
                    })
                }
            },
            mounted() {
                this.searchOrders()
            }
        });
    </script>
@endpush
<div class="modal fade" id="modal-default">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Default Modal</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>One fine body&hellip;</p>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>