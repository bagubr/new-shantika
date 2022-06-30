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
<div class="content" id="app-sketch">
    <div class="container-fluid">
        <div class="card sticky " style="z-index: 1000">
            <div class="row p-2">
                <div class="col-md-12">
                    <div class="row">
                        <div class="form-group col-3">
                            <label>Pilih Area</label>
                            <select v-model="filter.area_id" name="area_id" class="form-control" id="" {{(Auth::user()->area_id)?'disabled':''}}>
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
                <a class="btn btn-outline-primary">Jumlah Keberangkatan @{{ result.orders.length }}</a>
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
                <div class="card h-100 p-1 shadow">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3" data-toggle="modal" data-target="#modal-default"
                            @click="handleChangeFocusFirstLayout(order.fleet_route_id, order.fleet_route.fleet_id, order.time_classification_id)">
                                <i class="fas fa-bus" style="font-size: 3em; color: Mediumslateblue;"></i>
                            </div>
                            <div class="col-md-6">
                                <b>@{{order.fleet_route?.fleet_detail?.fleet?.name}}
                                        {{-- / @{{order.fleet_route?.fleet_detail?.nickname}} (@{{order.fleet_route?.fleet_detail?.plate_number}}) --}}
                                </b>
                                {{-- <a href="{{route('fleets.index')}}"><i class="fas fa-eyes"></i></a> --}}
                                <br>
                                <p>@{{order.time_classification.name}}</p>
                                <p>@{{data.date_now}}</p>
                                <br>
                            </div>
                            <div class="col-md-3">
                                <small class="float-right mb-5">
                                    @{{order.fleet_route?.fleet_detail?.plate_number}} <br>
                                    @{{order.fleet_route?.fleet_detail?.co_driver??'Tidak ada'}} <a :href="editFleetDetail(order.fleet_route?.fleet_detail?.id)" target="_blank"><p class="fas fa-edit"></p></a>
                                </small>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label for="">RUTE</label>
                            </div>
                            <div class="col-md-11">
                                <marquee direction="left" height="20" width="100%" bgcolor="white" onmouseover="this.stop();" onmouseout="this.start();"><p style="font-size: 15px;">@{{order.fleet_route?.fleet_detail?.fleet?.name}} &nbsp;( &nbsp; @{{order.fleet_route?.fleet_detail?.fleet?.fleetclass?.name}}&nbsp;|&nbsp;@{{order.fleet_route?.route?.name}} )</p></marquee>
                            </div>
                            <div class="col-md-1">
                                <a :href="editRoute(order.fleet_route?.route?.id)" target="_blank"><p class="fas fa-edit"></p></a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label for="">AGEN</label>
                            </div>
                            <div class="col-md-11">
                                <marquee direction="left" height="20" width="100%" bgcolor="white" onmouseover="this.stop();" onmouseout="this.start();"><p style="font-size: 15px;">@{{order.fleet_route?.fleet_detail?.fleet?.name}} &nbsp;( &nbsp; @{{order.fleet_route?.fleet_detail?.fleet?.fleetclass?.name}}&nbsp;|&nbsp;@{{order.fleet_route?.agency_name}} )</p></marquee>
                            </div>
                            <div class="col-md-1">
                                <a :href="editAgent(order.fleet_route?.route?.id)" target="_blank"><p class="fas fa-edit"></p></a>
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
                    is_unit:false,
                    is_delete_unit:false,
                    is_group:false,
                    is_delete_group:false,
                    oneLayout:false,
                    ticketPrice:0,
                },
                filter: {
                    area_id: {!! $area_id !!},
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
                fleetRoutes:[],

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
                setSelectOptionLayoutText(fleetRoute) {
                    let fleetName = fleetRoute.fleet_detail.fleet.name
                    let fleetClass = fleetRoute.fleet_detail.fleet.fleetclass.name
                    let routeName = fleetRoute.route.name
                    let idfleetRoute = fleetRoute.id

                    return `${fleetName} (${fleetClass} | ${routeName}) ${idfleetRoute}`
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
                    this.getFleetRoutes(fleetRouteId)
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
                        console.log(res.data)
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
                getGroupIndexByRowCol(row, col, which) {
                    if(which == 0) {
                        return (((row - 1) * this.firstLayout.data.col) + col) - 1
                    } else {
                        return (((row - 1) * this.secondLayout.data.col) + col) - 1
                    }
                },
                loadText(row, col, which) {
                    this.whichLayout(which);

                    let chair;
                    let html;
                    html = `<marquee>`
                    
                    let index = this.getCurrentIndexByRowCol(row, col, which)
                    if(which == 0){
                        chair = this.firstLayout.data.chairs.filter((e, i) =>  i == index)[0]
                    }else{
                        chair = this.secondLayout.data.chairs.filter((e, i) =>  i == index)[0]
                    }
                    
                    if(chair.order_detail?.order_detail){
                        user = chair.order_detail?.order_detail?.filter((e, i) => e.layout_chair_id == chair.id)[0]
                    }
                    if (chair.is_selected) {
                        if(which == 0){
                            html +=  `<p class="text-nowrap d-inline">${chair.name} | ${user.name} | ${chair.code} |</p>`
                        }else{
                            return  `<p class="text-nowrap d-inline">${chair.from_name} => ${chair.name}</p>`
                        }
                    } else if (chair.is_switched) {
                        html +=  `<p class="text-nowrap d-inline">${chair.name} | ${user.name} | ${chair.code} |</p>`
                    } else if(chair.is_blocked) {
                        html +=  `<p class="text-nowrap d-inline">${chair.name}</p>`
                    } else if (chair.is_unavailable) {
                        html +=  `<p class="text-nowrap d-inline">${chair.name} | ${user.name} | ${chair.code} |</p>`
                    } else if (chair.is_unavailable_customer){
                        html +=  `<p class="text-nowrap d-inline">${chair.name} | ${user.name} | ${chair.code} |</p>`
                    } else if (chair.is_unavailable_not_paid_customer) {
                        html +=  `<p class="text-nowrap d-inline">${chair.name} | ${user.name} | ${chair.code} |</p>`
                    } else if (chair.is_unavailable_waiting_customer) {
                        html +=  `<p class="text-nowrap d-inline">${chair.name} | ${user.name} | ${chair.code} |</p>`
                    } else if (chair.is_booking) {
                        html +=  `<p class="text-nowrap d-inline">${chair.name} | ${chair.code} |</p>`
                    } else if(chair.is_door) {
                        return  `<span><i class="fas fa-door-closed"></i></span>`
                    } else if (chair.is_space) {
                        return `<span><i class="fas fa-people-arrows"></i></span>`
                    } else if (chair.is_toilet) {
                        return `<span><i class="fas fa-toilet"></i></span>`
                    } else {
                        return `<span>${chair.name}</span>`
                    }
                    if(chair.order_detail?.order_detail?.length??0 > 1){
                        chair.order_detail.order_detail.forEach(function (value, key) {
                            return html += `(${value.chair_name})`
                        })
                    }
                    html += `</marquee>`
                    
                    return html
                },
                loadClass(row, col, which) {
                    this.whichLayout(which);

                    let chair;
                    let index = this.getCurrentIndexByRowCol(row, col, which)
                    if(which == 0){
                        chair = this.firstLayout.data.chairs.filter((e, i) =>  i == index)[0]
                    }else{
                        chair = this.secondLayout.data.chairs.filter((e, i) =>  i == index)[0]
                    }

                    if(chair.is_selected) {
                        return "btn bg-teal"
                    } else if (chair.is_switched) {
                        return "btn bg-green"
                    } else if (chair.is_blocked) {
                        return "btn btn-dark"
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
                    if(this.data.is_unit == false && this.data.is_delete_unit == false && this.data.is_delete_group == false && this.data.is_group == false){
                        return alert("silahkan pilih salah satu checklist!!!");
                    }
                    this.whichLayout(which)
                    let index = this.getCurrentIndexByRowCol(row, col,which)
                    chair = this.firstLayout.data.chairs.filter((e, i) =>  i == index)[0]
                    if(!this.firstLayout.data.chairs.filter(e => e.index == index)[0].is_unavailable) {
                        return alert("Pilih kursi yang sudah dibeli!");
                    }
                    if(this.firstLayout.data.chairs.filter(e => e.index == index)[0].is_switched == true) {
                        return alert("Kursi sudah di pindah!");
                    }
                    if(this.firstLayout.data.chairs.filter(e => e.index == index)[0].is_selected != true) {
                        if(this.data.is_group == true || this.data.is_delete_group == true){
                            if(this.firstLayout.data.chairs.filter(e => e.is_selected = true).length > 0){
                                    this.firstLayout.data.chairs.filter(e => e.is_selected = true).forEach(function (value) {
                                    value.is_selected = false
                                })
                            }
                            this.firstLayout.data.chairs.filter(e => e.order_detail?.id == chair.order_detail?.id).forEach(function (value) {
                                value.is_selected = true
                            })
                        }else{
                            this.firstLayout.data.chairs.filter(e => e.is_selected == true).forEach(function (value) {
                                    value.is_selected = false
                            })
                            this.firstLayout.data.chairs.filter(e => e.index == index)[0].is_selected = true
                        }
                    } else {
                        if(this.data.is_group == true || this.data.is_delete_group == true){
                            this.firstLayout.data.chairs.filter(e => e.order_detail?.id == chair.order_detail?.id).forEach(function (value) {
                                value.is_selected = false
                            })
                        }else{
                            this.firstLayout.data.chairs.filter(e => e.index == index)[0].is_selected = false
                        }
                    }
                    this.$forceUpdate()
                },
                dropSelectedSeat(row,col,which) {
                    this.whichLayout(which)
                    let index = this.getCurrentIndexByRowCol(row, col)
                    let first = this.firstLayout.data.chairs.filter(e => e.is_selected == true)[0]
                    let second = this.secondLayout.data.chairs.filter((e, i) => i == index)[0]
                    if(second.is_unavailable) {
                        return alert("Pilih kursi yang belum dibeli!");
                    }
                    if (second.is_unavailable_customer) {
                        return alert("Maaf Kursi Yang Anda Pilih Adalah Kursi Customer");
                    }
                    if (second.is_unavailable_not_paid_customer) {
                        return alert("Maaf Kursi Yang Anda Pilih Adalah Kursi Customer");
                    }
                    if (second.is_unavailable_waiting_customer) {
                        return alert("Maaf Kursi Yang Anda Pilih Adalah Kursi Customer");
                    }
                    first.is_switched = true
                    first.is_selected = false

                    second.is_unavailable = true
                    second.is_selected = true
                    second.from_id = first.id
                    second.from_name = first.name
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
                deleteGroup(form, reason){
                    let value = this.firstLayout.data.chairs.filter(e => e.is_selected == true)[0]
                    form.from_id = value.id
                    form.to_id = value.id
                    fetch('{{url("")}}'+`/order/`+value.order_detail.id, {
                        method: 'POST',
                        body: JSON.stringify({
                            '_method':'PUT',
                            'cancelation_reason':reason,
                            'data_update':{
                                'cancelation_reason':reason,
                                'status':'CANCELED'
                            },
                            'data':value,
                            'sketch_log':form,
                        }),
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{csrf_token()}}'
                        }
                    })
                    .then(res => res.json()).then((res) => {
                    if(res.code == 0){
                        return alert(res.message);
                    }else{
                        this.searchOrders();
                        this.handleChangeFocusFirstLayout(this.firstLayout.fleetRouteId, this.firstLayout.fleetId, this.firstLayout.timeClassificationId)
                    }
                    })
                    .catch((error) => {
                        return alert('Something Wrong !!, Check your connection');
                    })
                },
                deleteUnit(chairs, form){
                    let order_detail_id = chairs[0].order_detail.order_detail.filter(e => e.layout_chair_id == chairs[0].id)[0].id
                    fetch('{{url("")}}'+`/order_detail/`+order_detail_id, {
                        method: "DELETE",
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{csrf_token()}}'
                        }
                    })
                    .then(res => res.json()).then((res) => {
                    if(res.code == 0){
                        return alert(res.message);
                    }else{
                        this.updatePrice(chairs, form)
                    }
                    })
                    .catch((error) => {
                        return alert('Something Wrong !!, Check your connection');
                    })
                },
                updatePrice(chairs, form){
                    fetch('{{url("")}}'+`/order/update_price/`+chairs[0].order_detail.id, {
                        method: "GET"
                    })
                    .then(res => res.json()).then((res) => {
                        if(res.code == 0){
                            return alert(res.message);
                        }else{
                            if(this.data.is_unit == true){
                                this.createOrder(chairs, form);
                            }else{
                                this.searchOrders();
                                this.handleChangeFocusFirstLayout(this.firstLayout.fleetRouteId, this.firstLayout.fleetId, this.firstLayout.timeClassificationId)
                            }
                        }
                    })
                    .catch((error) => {
                        return alert('Something Wrong !!, Check your connection');
                    })
                },
                sendNotification(form, reason, order_id){
                    let query = new URLSearchParams({
                            id: order_id,
                            cancelation_reason: reason,
                            status: form.status
                    });
                    fetch('{{url("")}}'+`/sketch/log/notification?${query}`, {
                        method: 'GET'
                    })
                },
                changeOrder(from, form, value){
                    fetch('{{url("")}}'+`/order/`+from[0].order_detail.id, {
                        method: 'POST',
                        body: JSON.stringify({
                            '_method':'PUT',
                            'data_update':{
                                'reserve_at':form.second_date,
                                'fleet_route_id':form.second_fleet_route_id,
                                'time_classification_id':form.second_time_classification_id
                            },
                            'data':value,
                            'sketch_log':form,
                            }),
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{csrf_token()}}'
                        }
                    })
                    .then(res => res.json()).then((res) => {
                    if(res.code == 0){
                        return alert(res.message);
                    }
                    })
                    .catch((error) => {
                        return alert('Something Wrong !!, Check your connection');
                    })
                },
                changeChair(from, form){
                    fetch('{{url("")}}'+`/order_detail/`+from[0].order_detail.order_detail.filter(e => e.layout_chair_id == form.from_id)[0].id, {
                        method: 'POST',
                        body: JSON.stringify({
                            '_method':'PUT',
                            'layout_chair_id':form.to_id,
                        }),
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{csrf_token()}}'
                        }
                    })
                    .then(res => res.json()).then((res) => {
                    if(res.code == 0){
                        return alert(res.message);
                    }else{
                        this.searchOrders();
                        this.handleChangeFocusFirstLayout(this.firstLayout.fleetRouteId, this.firstLayout.fleetId, this.firstLayout.timeClassificationId)
                    }
                    })
                    .catch((error) => {
                        return alert('Something Wrong !!, Check your connection');
                    })
                },
                createOrder(from, form){
                    let order_detail = from[0].order_detail.order_detail.filter(e => e.layout_chair_id == from[0].id)[0]
                    let to = this.secondLayout.data.chairs.filter(e => e.is_selected == true)
                    let order_detail_to = to[0]
                    fetch('{{url("")}}'+`/order`, {
                        method: 'POST',
                        body: JSON.stringify({
                            'user_id':from[0].order_detail.user_id,
                            'reserve_at':form.second_date,
                            'fleet_route_id':form.second_fleet_route_id,
                            'time_classification_id':form.second_time_classification_id,
                            'id_member':from[0].order_detail.id_member,
                            'status':'PAID',
                            'departure_agency_id':from[0].order_detail.departure_agency_id,
                            'destination_agency_id':from[0].order_detail.destination_agency_id,
                            'promo_id':from[0].order_detail.promo_id,
                            'note':from[0].order_detail.note,
                            'layout_chair_id': [order_detail_to.id],
                            'total_price': form.ticket_price,
                            'name': order_detail.name,
                            'phone': order_detail.phone,
                            'email': order_detail.email,
                            'is_feed': order_detail.is_feed,
                            'is_travel': order_detail.is_travel,
                            'is_member': order_detail.is_member,
                        }),
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{csrf_token()}}'
                        }
                    })
                    .then(res => res.json()).then((res) => {
                    if(res.code == 0){
                        return alert(res.message);
                    }else{
                        this.searchOrders();
                        this.handleChangeFocusFirstLayout(this.firstLayout.fleetRouteId, this.firstLayout.fleetId, this.firstLayout.timeClassificationId)
                    }
                    })
                    .catch((error) => {
                        return alert('Something Wrong !!, Check your connection');
                    })
                },
                changeChairAndOrder(from, form){
                    this.secondLayout.data.chairs.filter(e => e.is_selected == true).forEach((value) => {
                        form.from_id = value.from_id
                        form.to_id = value.id
                        form.status = 'CHANGE'
                        this.changeOrder(from, form, value);
                        this.changeChair(from, form);
                    })
                },
                submit() {
                    let form = {
                        first_fleet_route_id: this.firstLayout.fleetRouteId,
                        second_fleet_route_id: this.secondLayout.fleetRouteId,
                        first_date: this.firstLayout.date,
                        second_date: this.secondLayout.date,
                        first_time_classification_id: this.firstLayout.timeClassificationId,
                        second_time_classification_id: this.secondLayout.timeClassificationId,
                    }
                    let reason
                    let password
                    let chairs
                    let order_id
                    if(this.data.is_delete_group == true || this.data.is_delete_unit == true){
                        form.status = 'CANCELED'
                        chairs = this.firstLayout.data.chairs.filter(e => e.is_selected == true)

                        if(chairs.length > 1 && this.data.is_delete_unit == true){
                            return alert('Hanya dapat pilih satu kursi untuk di hapus !!!')
                        }
                        if(chairs.length != chairs[0].order_detail.order_detail.length && this.data.is_delete_group == true){
                            return alert('Data yang akan di hapus tidak sesuai')
                        }
                        reason = prompt("Masukan Alasan Penghapusan anda : ", "");
                        if(reason == null || reason == ""){
                            return alert("Anda Belum memasukan alasan anda");
                        }
                        password = prompt("Masukan Password anda : ", "");
                        if(password == null || password == ""){
                            return alert("Anda Belum Memasukan Password");
                        }
                        if(!confirm(`Yakin anda akan menghapus order berikut dengan alasan `+reason)){
                            return alert("Konfirmasi di batalkan ");
                        }
                        let query = new URLSearchParams({
                            password: password,
                        });
                        fetch(`{{url('/check-password')}}?${query}`, {
                            method:'GET'
                        })
                        .then(res => res.json()).then((res) => {
                            if(res.code == 0){
                                return alert(res.message);
                            }
                        })
                        if(chairs.length == chairs[0].order_detail.order_detail.length){
                            this.deleteGroup(form, reason)
                        }else{
                            this.deleteUnit(chairs, form)
                        }
                        order_id = this.firstLayout.data.chairs.filter(e => e.is_selected == true)[0].order_detail.id
                        this.firstLayout.isLoading = true
                        this.secondLayout.isLoading = true
                        this.sendNotification(form, reason, order_id);
                        return alert('Data berhasil di hapus');
                    }
                    if(this.data.is_group == true || this.data.is_unit == true){
                        let from = this.firstLayout.data.chairs.filter(e => e.is_switched == true)
                        let to = this.secondLayout.data.chairs.filter(e => e.is_selected == true)
                        if(from.length <= 0){
                            return alert('Anda Belum memilih kursi yang akan di pindah')
                        }
                        if(to.length <= 0){
                            return alert('Anda Belum memilih kursi yang akan di tempati')
                        }

                        if(this.firstLayout.data.chairs.filter(e => e.is_selected == true).length > 0){
                            return alert('Silahkan pilih kursi dahulu')
                        }
                        if(this.data.is_unit == true){
                            if(new Date(form.first_date).toDateString() == new Date(form.second_date).toDateString() && form.first_fleet_route_id == form.second_fleet_route_id && form.first_time_classification_id == form.second_time_classification_id){
                                this.changeChairAndOrder(from, form);
                            }else{
                                this.deleteUnit(from, form)
                            }
                        }else{
                            this.changeChairAndOrder(from, form);
                        }
                        order_id = this.firstLayout.data.chairs.filter(e => e.is_switched == true)[0].order_detail.id
                        this.firstLayout.isLoading = true
                        this.secondLayout.isLoading = true
                        this.sendNotification(form, reason, order_id);
                        return alert('Data berhasil di ubah');
                    }
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
                    this.data.is_unit = false;
                    this.data.is_group = false;
                    this.data.is_delete_group = false;
                    this.data.is_delete_unit = false;
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
                },
                editFleetDetail(fleetDetailId) {
                    return `{{url('/fleet_detail/${fleetDetailId}/edit')}}`
                },
                editRoute(routeId) {
                    return `{{url('/routes/${routeId}/edit')}}`
                },
                editAgent(routeId) {
                    let query = new URLSearchParams({
                        area_id: this.filter.area_id
                    });
                    return `{{url('/agency_route_permanent/${routeId}/edit')}}?${query}`
                },
                changeIsGroup() {
                    if(this.data.is_group == true){
                        this.data.is_group = false
                    }else{
                        this.data.is_delete_unit = false
                        this.data.is_unit =  false
                        this.data.is_delete_group =  false
                        this.data.is_group = true
                        this.data.oneLayout = false


                    }
                },
                changeIsDeleteGroup() {
                    if(this.data.is_delete_group == true){
                        this.data.is_delete_group = false
                        this.data.oneLayout = false
                    }else{
                        this.data.oneLayout = true
                        this.data.is_delete_unit = false
                        this.data.is_unit =  false
                        this.data.is_group =  false
                        this.data.is_delete_group = true
                    }
                },
                changeIsUnit() {
                    if(this.data.is_unit ==  true){
                        this.data.is_unit =  false
                    }else{
                        this.data.oneLayout = false
                        this.data.is_delete_group = false
                        this.data.is_group = false
                        this.data.is_delete_unit = false
                        this.data.is_unit =  true
                    }
                },
                changeIsDeleteUnit() {
                    if(this.data.is_delete_unit == true){
                        this.data.is_delete_unit = false
                        this.data.oneLayout = false
                    }else{
                        this.data.is_delete_group = false
                        this.data.is_group = false
                        this.data.is_unit = false
                        this.data.is_delete_unit = true
                        this.data.oneLayout = true
                    }
                },
                relaodLayout() {
                    this.handleChangeFocusFirstLayout(this.firstLayout.fleetRouteId, this.firstLayout.fleetId, this.firstLayout.timeClassificationId)
                },
                getFleetRoutes(fleetRouteId) {
                    let query = new URLSearchParams({
                        fleet_route_id: fleetRouteId,
                        area_id: this.filter.area_id
                    });
                    fetch(`{{url('/fleet_route/get-available-route')}}?${query}`, {
                        method:'GET'
                    })
                    .then(res => res.json()).then((res) => {
                        if(res.code = 0){
                            return alert(res.message);
                        }
                        this.fleetRoutes = res.data;
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
