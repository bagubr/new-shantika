@extends('layouts.main')
@section('title')
Sketch
@endsection
@push('css')
<style>
    .sticky {
        position: -webkit-sticky;
        /* Safari */
        position: sticky;
        top: 0;
    }

    .bg-chocolate {
        background-color: #603415 !important;
    }

    .bg-chocolate,
    .bg-chocolate>a {
        color: #fff !important;
    }

    .bg-chocolate-light {
        background-color: #ff6a00 !important;
    }

    .bg-chocolate-light,
    .bg-chocolate-light>a {
        color: #fff !important;
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
<div class="content" id="app-sketch" v-scroll="onScroll">
    <div class="container-fluid">
        <div class="card sticky " style="z-index: 1000">
            <div class="row p-2">
                <div class="col-md-12">
                    <div class="row">
                        <div class="form-group col-3">
                            <label>Pilih Area</label>
                            <select v-model="filter.area_id" name="area_id" class="form-control" id="">
                                <option value="" selected>--PILIH--</option>
                                <option v-for="area in data.areas" :key="area.id" :value="area.id">
                                    @{{area.name}}
                                </option>
                            </select>
                        </div>
                        <div class="form-group col-3">
                            <label>Pilih Shift</label>
                            <select v-model="firstLayout.timeClassificationId" name="time_classification"
                                class="form-control" id="">
                                <option value="" selected>--PILIH--</option>
                                <option v-for="time_classification in data.timeClassifications"
                                    :key="time_classification.id" :value="time_classification.id">
                                    @{{time_classification.name}}
                                </option>
                            </select>
                        </div>
                        <div class="form-group col-3">
                            <label>Pilih Armada</label>
                                <v-select label="name" v-model="filter.fleet_id" :reduce="(option) => option.id" :options="data.fleets"
                                    input-class="form-control" id="">
                                    <option value="selected">--PILIH--</option>
                                </v-select>
                        </div>
                        <div class="form-group col-3">
                            <label for="">Tanggal</label>
                            <vuejs-datepicker v-model="firstLayout.date" input-class="form-control bg-white"
                                    format="yyyy-MM-dd" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-right m-2">
                <a class="btn btn-outline-primary">Jumlah Keberangkatan @{{ result.fleet_id }}</a>
                <a class="btn btn-primary" href="{{url('sketch/log')}}">Riwayat Sketch</a>
                <button @click="searchOrders()" class="btn btn-success" type="submit">Cari</button>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12" v-if="result.orders == 0">
                <div class="card p-5">
                    <h3>
                        Data tidak di temukan
                    </h3>
                    <div v-if="result.isLoading" class="w-100 row justify-content-center">
                        <lottie-player src="https://assets7.lottiefiles.com/packages/lf20_Stt1R6.json"
                            background="transparent" speed="1" style="width: 100px; height: 100px;" loop
                            autoplay></lottie-player>
                    </div>
                </div>
            </div>
            <div v-for="order in result.orders" class="col-12 col-sm-6 col-lg-4 col-xl-3 pt-2 pb-2">
                <div class="card h-100 p-2 shadow" data-toggle="modal" data-target="#modal-default"
                    @click="handleChangeFocusFirstLayout(order.fleet_route_id, order.fleet_route.fleet_id, order.time_classification_id)">
                    <div class="card-body">
                        <div class="row m-1">
                            <div class="col-md-3 text-center">
                                <i class="fas fa-bus" style="font-size: 2.5em; color: Mediumslateblue;"></i>
                            </div>
                            <div class="col-md-9">
                                <b>@{{order.fleet_route?.fleet_detail?.fleet?.name}} / @{{order.fleet_route?.fleet_detail?.nickname}} (@{{order.fleet_route?.fleet_detail?.plate_number}})</b>
                                <a href="{{route('fleets.index')}}"><i class="fas fa-eyes"></i></a>
                                <br>
                                <p>@{{order.time_classification.name}}</p>
                                <small>Co driver : @{{order.fleet_route?.fleet_detail?.co_driver}} <p class="edit-co-driver"></p></small>
                                <p>@{{data.date_now}}</p>
                                <span>@{{order.fleet_route?.fleet_detail?.fleet?.fleetclass?.name}}</span>
                                <br>
                                <p style="font-size: 15px;">@{{order.fleet_route?.route?.name}}</p>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-6">
                                <p class="text-center mb-0">
                                    <i class="fas fa-users"></i>
                                    <br>
                                    <span>@{{order.order_detail_count || 0}} /
                                        @{{order.fleet_route?.fleet_detail?.fleet?.layout?.total_chairs}}</span>
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
    @include('sketch.modal')
</div>
@endsection
@push('script')
<script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
{{-- <script src="https://cdn.jsdelivr.net/npm/vue@2"></script> --}}

<script src="https://unpkg.com/vuejs-datepicker"></script>

<script src="https://unpkg.com/vue-select@3.0.0"></script>
<link rel="stylesheet" href="https://unpkg.com/vue-select@3.0.0/dist/vue-select.css">

<script>
    Vue.component('v-select', VueSelect.VueSelect)
    var app = new Vue({
            el: '#app-sketch',
            components: {
                vuejsDatepicker
            },
            data: {
                csrf_token: '{{ csrf_token() }}',
                data: {
                    areas: {!! $areas !!},
                    timeClassifications: {!! $time_classifications !!},
                    fleets: {!! $fleets !!},
                    date_now: "",
                },
                filter: {
                    area_id: {!! $areas->first()->id !!},
                    fleet_id:""
                },
                result: {
                    isLoading: false,
                    orders: [],
                    _orders: [],
                    fleetDetailId:''
                },
                firstLayout: {
                    date: new Date().toDateString(),
                    timeClassificationId: "",
                    fleetId: null,
                    fleetRouteId: null,
                    isLoading: false,
                    isShowInGrid: true,
                    fleet: {},
                    data: {},
                },
                secondLayout: {
                    date:  new Date().toDateString(),
                    timeClassificationId: "",
                    fleetId: null,
                    fleetRouteId: null,
                    isLoading: false,
                    isShowInGrid: true,
                    fleet: {},
                    data: {}
                },

            },
            methods: {
                EditCoDriver(order){
                    fleetDetailId = order.fleet_route.fleet_detail_id
                    this.$router.push({
                        name:'fleet_detail.edit',
                        params:{
                            id:fleetDetailId
                        }
                    })
                },
                searchOrders() {
                    this.result.isLoading = true
                    this.result.orders = []
                    let params = new URLSearchParams({
                        ...this.filter,
                        date: new Date(this.firstLayout.date).toDateString(),
                        time_classification_id: this.firstLayout.timeClassificationId
                    })
                    fetch("{{url('/')}}/sketch/orders?"+params).then(res => res.json()).then(res => {
                        this.result.orders = res.orders
                        this.data.date_now = new Date(this.firstLayout.date).toDateString()
                        this.result.isLoading = false
                    })
                },
                search_orders() {
                    let params = new URLSearchParams({
                        ...this.filter,
                        date: new Date(this.secondLayout.date).toDateString()
                    })
                    fetch("{{url('/')}}/sketch/orders?"+params).then(res => res.json()).then(res => {
                        this.result._orders = res.orders
                    })
                },
                setSelectOptionLayoutText(order) {
                    let fleetName = order.fleet_route.fleet_detail.fleet.name
                    let fleetClass = order.fleet_route.fleet_detail.fleet.fleetclass.name
                    let routeName = order.fleet_route.route.name

                    return `${fleetName} (${fleetClass} | ${routeName})`
                },
                selectOptionFirstLayout(event = null) {
                    this.getFirstLayout(event?.currentTarget?.value)
                },
                selectOptionSecondLayout(event = null) {
                    this.getSecondLayout(event?.currentTarget?.value)
                },
                handleChangeFocusFirstLayout(fleetRouteId, fleetId, timeClassificationId) {
                    this.firstLayout.fleetRouteId = fleetRouteId
                    this.firstLayout.fleetId = fleetId
                    this.firstLayout.timeClassificationId = timeClassificationId
                    this.getFirstLayout()
                    this.handleChangeFocusSecondLayout(fleetRouteId, fleetId, timeClassificationId)
                },
                handleChangeFocusSecondLayout(fleetRouteId, fleetId, timeClassificationId) {
                    this.secondLayout.fleetRouteId = fleetRouteId
                    this.secondLayout.fleetId = fleetId
                    this.secondLayout.timeClassificationId = timeClassificationId
                    this.getSecondLayout()
                },
                handleDateChange(type) {
                    if(type == 'FIRST') {
                        this.getFirstLayout()
                    } else {
                        this.getSecondLayout()
                        this.search_orders()
                    }
                },
                getFirstLayout(fleetRouteId = null) {
                    this.firstLayout.isLoading = true
                    let params = new URLSearchParams({
                        fleet_route_id: fleetRouteId || this.firstLayout.fleetRouteId,
                        time_classification_id: this.firstLayout.timeClassificationId,
                        date: new Date(this.firstLayout.date).toDateString()
                    })
                    fetch("{{url('/')}}/sketch/orders/detail?"+params).then(res => res.json()).then(res => {
                        this.firstLayout.data = res.data
                        this.firstLayout.fleet = res.fleet
                    }).finally(() => {
                        this.firstLayout.isLoading = false
                    })
                },
                getSecondLayout(fleetRouteId = null) {
                    this.secondLayout.isLoading = true
                    let params = new URLSearchParams({
                        fleet_route_id: fleetRouteId || this.secondLayout.fleetRouteId,
                        time_classification_id: this.secondLayout.timeClassificationId,
                        date: new Date(this.secondLayout.date).toDateString()
                    })
                    this.search_orders();
                    fetch("{{url('/')}}/sketch/orders/detail?"+params).then(res => res.json()).then(res => {
                        this.secondLayout.data = res.data
                        this.secondLayout.fleet = res.fleet
                    }).finally(() => {
                        this.secondLayout.isLoading = false
                    })
                },
                getCurrentIndexByRowCol(row, col, which) {
                    if(which == 0) {
                        return (((row - 1) * this.firstLayout.data.col) + col) - 1
                    } else {
                        return (((row - 1) * this.secondLayout.data.col) + col) - 1
                    }
                },
                loadText(row, col, which) {
                    this.whichLayout(which);

                    let chair;
                    if(which == 0) {
                        let index = this.getCurrentIndexByRowCol(row, col, 0)
                        chair = this.firstLayout.data.chairs.filter((e, i) =>  i == index)[0]
                    } else {
                        let index = this.getCurrentIndexByRowCol(row, col, 1)
                        chair = this.secondLayout.data.chairs.filter((e, i) =>  i == index)[0]
                    }

                    if (chair.is_unavailable) {
                        if(chair.is_selected) {
                            return `<p class="text-nowrap">${chair.name} | ${chair.code || ''}</p>`
                        } else if (chair.is_switched) {
                            return `<p class="text-nowrap">${chair.name} | ${chair.code || ''}</p>`
                        }
                        return `<p class="text-nowrap">${chair.name} | ${chair.code || ''}</p>`
                    } else if (chair.is_unavailable_customer){
                        if(chair.is_selected) {
                            return `<p class="text-nowrap">${chair.name} | ${chair.code || ''}</p>`
                        } else if (chair.is_switched) {
                            return `<p class="text-nowrap">${chair.name} | ${chair.code || ''}</p>`
                        }
                        return `<p class="text-nowrap">${chair.name} | ${chair.code || ''}</p>`
                    } else if (chair.is_booking) {
                        return `<p class="text-nowrap">${chair.name} | ${chair.code || ''}</p>`
                    } else if(chair.is_door) {
                        return `<span><i class="fas fa-door-closed"></i></span>`
                    } else if (chair.is_space) {
                        return `<span><i class="fas fa-people-arrows"></i></span>`
                    } else if (chair.is_toilet) {
                        return `<span><i class="fas fa-toilet"></i></span>`
                    } else {
                        return `<span>${chair.name}</span>`
                    }
                },
                loadClass(row, col, which) {
                    this.whichLayout(which);

                    let chair;
                    if(which == 0) {
                        let index = this.getCurrentIndexByRowCol(row, col, 0)
                        chair = this.firstLayout.data.chairs.filter((e, i) =>  i == index)[0]
                    } else {
                        let index = this.getCurrentIndexByRowCol(row, col, 1)
                        chair = this.secondLayout.data.chairs.filter((e, i) =>  i == index)[0]
                    }
                    if(chair.is_selected) {
                        return "btn bg-teal"
                    } else if (chair.is_switched) {
                        return "btn bg-green"
                    } else if (chair.is_unavailable) {
                        return "btn btn-danger"
                    } else if (chair.is_unavailable_customer){
                        return "btn bg-purple"
                    } else if (chair.is_unavailable_not_paid_customer){
                        return "btn bg-chocolate"
                    } else if (chair.is_unavailable_waiting_customer){
                        return "btn bg-chocolate-light"
                    } else if (chair.is_booking) {
                        return "btn bg-orange"
                    } else if(chair.is_door) {
                        return "btn btn-info"
                    } else if (chair.is_space) {
                        return "btn btn-secondary"
                    } else if (chair.is_toilet) {
                        return "btn btn-warning"
                    } else {
                        return "btn btn-primary"
                    }
                },
                whichLayout(which) {
                    switch (which) {
                        case 0:
                            return 0
                            break;
                        case 1:
                            return 1
                            break;
                        default:
                            throw new Error('Unsupported layout');
                            break;
                    }
                },
                selectSeat(row,col,which) {
                    this.whichLayout(which)
                    let index = this.getCurrentIndexByRowCol(row, col,which)
                    if(!this.firstLayout.data.chairs.filter(e => e.index == index)[0].is_unavailable && !this.firstLayout.data.chairs.filter(e => e.index == index)[0].is_unavailable_customer) {
                        return alert("Pilih kursi yang sudah dibeli!");
                    }
                    if(this.firstLayout.data.chairs.filter(e => e.index == index)[0].is_selected != true) {
                        this.firstLayout.data.chairs.filter(e => e.index == index)[0].is_selected = true
                    } else {
                        this.firstLayout.data.chairs.filter(e => e.index == index)[0].is_selected = false
                    }
                    this.$forceUpdate()
                },
                dropSelectedSeat(row,col,which) {
                    this.whichLayout(which)
                    let index = this.getCurrentIndexByRowCol(row, col)
                    if(this.secondLayout.data.chairs.filter(e => e.index == index)[0].is_unavailable) {
                        return alert("Pilih kursi yang belum dibeli!");
                    }
                    if (this.secondLayout.data.chairs.filter(e => e.index == index)[0].is_unavailable_customer) {
                        return alert("Maaf Kursi Yang Anda Pilih Adalah Kursi Customer");
                    }
                    let value = this.firstLayout.data.chairs.filter(e => e.is_selected == true)[0]
                    this.firstLayout.data.chairs.filter(e => e.is_selected  == true)[0].is_switched = true
                    this.firstLayout.data.chairs.filter(e => e.is_selected == true)[0].is_selected = false

                    this.secondLayout.data.chairs.filter((e, i) => i == index)[0].is_unavailable = true
                    this.secondLayout.data.chairs.filter((e, i) => i == index)[0].is_selected = true
                    this.$forceUpdate()
                },
                printFirstLayout() {
                    let query = new URLSearchParams({
                        date: new Date(this.firstLayout.date).toDateString(),
                        fleet_route_id: this.firstLayout.fleetRouteId,
                        area_id: this.filter.area_id
                    });

                    return `{{url('/sketch/export')}}?${query}`
                },
                printSecondLayout() {
                    let query = new URLSearchParams({
                        date: new Date(this.secondLayout.date).toDateString(),
                        fleet_route_id: this.secondLayout.fleetRouteId,
                        area_id: this.filter.area_id
                    });

                    return `{{url('/sketch/export')}}?${query}`
                },
                submit() {
                    let form = {
                        first_fleet_route_id: this.firstLayout.fleetRouteId,
                        second_fleet_route_id: this.secondLayout.fleetRouteId,
                        first_fleet_id: this.firstLayout.fleetId,
                        second_fleet_id: this.secondLayout.fleetId,
                        first_date: this.firstLayout.date,
                        second_date: this.secondLayout.date,
                        data: {
                            from_date: this.firstLayout.date,
                            to_date: this.secondLayout.date,
                            to_time_classification_id: this.firstLayout.timeClassificationId,
                            from_time_classification_id: this.secondLayout.timeClassificationId,
                            from_layout_chair_id: this.firstLayout.data.chairs.filter(e => e.is_switched == true),
                            to_layout_chair_id: this.secondLayout.data.chairs.filter(e => e.is_selected == true),
                        }
                    }

                    fetch("{{url('sketch/store')}}", {
                        method: 'POST',
                        body: JSON.stringify(form),
                        headers: {
                            'X-CSRF-TOKEN': '{{csrf_token()}}',
                            'Content-Type': 'application/json'
                        }
                    }).then(res => res.json()).then(res => {
                        window.location.reload()
                    })
                },
                reset() {
                    this.firstLayout.data.chairs = this.firstLayout.data.chairs.map(e => {
                        e.is_selected = false
                        e.is_switched = false
                        return e
                    })
                    this.secondLayout.data.chairs = this.secondLayout.data.chairs.map(e => {
                        if(e.is_selected) {
                            e.is_selected = false
                            e.is_unavailable = false
                        }
                        return e
                    })
                },
                cancelOrder(orderDetailId, password, reason, isAll) {
                    fetch('{{url("")}}'+`/order/cancelation/${orderDetailId}`, {
                        method: 'PUT',
                        body: JSON.stringify({
                            'password':password,
                            'cancelation_reason':reason,
                            'is_all':isAll || false
                        }),
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{csrf_token()}}'
                        }
                    }).then(res => res.json()).then(res => {
                        window.location.reload()
                    })
                }
            },
            mounted() {
                this.searchOrders()
            }
        });
    // app.components('v-select', VueSelect.VueSelect);
</script>
@endpush
