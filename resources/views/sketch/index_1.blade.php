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
                                <option v-for="area in data.areas" :key="area.id" :value="area.id">
                                    @{{area.name}}
                                </option>
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
                    <div class="card h-100 p-2 shadow" data-toggle="modal" data-target="#modal-default" @click="handleChangeFocusFirstLayout(order.fleet_route_id)">
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
        <div class="modal fade" id="modal-default">
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">@{{this.firstLayout.fleet.name}} -> @{{this.secondLayout.fleet.name}}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Memindahkan penumpang dari bis <b>@{{this.firstLayout.fleet.name}}</b> ke <b>@{{this.secondLayout.fleet.name}}</b></p>
                        <div class="row">
                            <div class="col-6 border-right">
                                <div class="form-group position-sticky bg-white pb-1 pt-1" style="top: -17px">
                                    <label for="">Armada</label>
                                    <div class="row">
                                        <div class="col">
                                            <select name="" class="form-control" id="">
                                                <option :value="order.id" v-for="order in result.orders" :key="order.id" v-text="setSelectOptionLayoutText(order)"></option>
                                            </select>
                                        </div>
                                        <div class="col-auto">
                                            <button class="btn btn-secondary">
                                                <i class="fas fa-list"></i>
                                            </button>
                                            <button class="btn btn-secondary">
                                                <i class="fas fa-th"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div v-if="firstLayout.isLoading" class="w-100 row justify-content-center">
                                    <lottie-player src="https://assets7.lottiefiles.com/packages/lf20_Stt1R6.json"  background="transparent"  speed="1"  style="width: 100px; height: 100px;"  loop autoplay></lottie-player>
                                </div>
                                <div v-else>
                                    <div v-if="firstLayout.isShowInGrid">
                                        <div v-for="i in firstLayout.data.row" class="d-flex">
                                            <div v-for="j in firstLayout.data.col" class="m-1">
                                                <button 
                                                    v-html="loadText(i,j,0)"
                                                    :class="loadClass(i,j,0)" 
                                                    style="min-width: 100px"
                                                    ref="btn-first-layout"
                                                    @click="selectSeat(i,j,0)"></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div v-else>
                                        <div v-for="i in firstLayout.data.chairs" class="w-100">
                                            <p>Ayy</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 border-right">
                                <div class="form-group position-sticky bg-white pb-1 pt-1" style="top: -17px">
                                    <label for="">Armada</label>
                                    <div class="row">
                                        <div class="col">
                                            <select name="" class="form-control" id="" @change="handleChangeFocusSecondLayout($event)">
                                                <option :value="order.id" v-for="order in result.orders" :key="order.id" v-text="setSelectOptionLayoutText(order)"></option>
                                            </select>
                                        </div>
                                        <div class="col-auto">
                                            <button class="btn btn-secondary">
                                                <i class="fas fa-list"></i>
                                            </button>
                                            <button class="btn btn-secondary">
                                                <i class="fas fa-th"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div v-if="secondLayout.isLoading" class="w-100 row justify-content-center">
                                    <lottie-player src="https://assets7.lottiefiles.com/packages/lf20_Stt1R6.json"  background="transparent"  speed="1"  style="width: 100px; height: 100px;"  loop autoplay></lottie-player>
                                </div>
                                <div v-else>
                                    <div v-if="secondLayout.isShowInGrid">
                                        <div v-for="i in secondLayout.data.row" class="d-flex">
                                            <div v-for="j in secondLayout.data.col" class="m-1">
                                                <button 
                                                    v-html="loadText(i,j,0)"
                                                    :class="loadClass(i,j,0)" 
                                                    style="min-width: 100px"
                                                    ref="btn-second-layout"
                                                    @click="selectSeat(i,j,0)"></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div v-else>
                                        <div v-for="i in secondLayout.data.chairs" class="w-100">
                                            <p>Ayy</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/vue@2"></script> --}}
    <script>
        $('[data-toggle="popover"]').popover();
    </script>
    <script>
        var app = new Vue({
            el: '#app-sketch',
            data: {
                csrf_token: '{{ csrf_token() }}',
                data: {
                    areas: {!! $areas !!}
                },
                filter: {
                    date: new Date().toISOString().slice(0,10),
                    area_id: {!! $areas->first()->id !!}
                },
                result: {
                    orders: []
                },
                
                firstLayout: {
                    fleetRouteId: null,
                    isLoading: false,
                    isShowInGrid: true,
                    fleet: {},
                    data: {},
                },
                secondLayout: {
                    fleetRouteId: null,
                    isLoading: false,
                    isShowInGrid: true,
                    fleet: {},
                    data: {}
                },
                form: []
            },
            methods: {
                searchOrders() {
                    let params = new URLSearchParams(this.filter)
                    fetch('/sketch/orders?'+params).then(res => res.json()).then(res => {
                        this.result.orders = res.orders
                    })
                },
                setSelectOptionLayoutText(order) {
                    let fleetName = order.fleet_route.fleet.name
                    let fleetClass = order.fleet_route.fleet.fleetclass.name
                    let at = `${order.fleet_route.route.departure_at} - ${order.fleet_route.route.arrived_at}`

                    return `${fleetName} (${fleetClass}) [ ${at} ]`
                },
                handleChangeFocusFirstLayout(fleetRouteId) {
                    this.firstLayout.fleetRouteId = fleetRouteId
                    this.getFirstLayout()
                },
                handleChangeFocusSecondLayout(fleetRouteId) {
                    this.secondLayout.fleetRouteId = fleetRouteId
                    this.getSecondLayout()
                },
                getFirstLayout() {
                    this.firstLayout.isLoading = true
                    let params = new URLSearchParams({
                        fleet_route_id: this.firstLayout.fleetRouteId,
                        date: this.filter.date
                    })
                    fetch('/sketch/orders/detail?'+params).then(res => res.json()).then(res => {
                        console.log(res)
                        this.firstLayout.data = res.data
                        this.firstLayout.fleet = res.fleet
                    }).finally(() => {
                        this.firstLayout.isLoading = false
                        this.handleChangeFocusSecondLayout(this.firstLayout.fleetRouteId)
                    })
                },
                getSecondLayout() {
                    this.secondLayout.isLoading = true
                    let params = new URLSearchParams({
                        fleet_route_id: this.secondLayout.fleetRouteId,
                        date: this.filter.date
                    })
                    fetch('/sketch/orders/detail?'+params).then(res => res.json()).then(res => {
                        console.log(res.data)
                        this.secondLayout.data = res.data
                        this.secondLayout.fleet = res.fleet
                    }).finally(() => {
                        this.secondLayout.isLoading = false
                    }) 
                },
                getCurrentIndexByRowCol(row, col) {
                    return (((row - 1) * this.firstLayout.data.col) + col) - 1
                },
                loadText(row, col, which) {
                    this.whichLayout(which);
                    let index = this.getCurrentIndexByRowCol(row, col)
                    
                    let chair;
                    if(which == 0) {
                        chair = this.firstLayout.data.chairs.filter((e, i) =>  e.index == index)[0]
                    } else {
                        chair = this.secondLayout.data.chairs.filter((e, i) =>  e.index == index)[0]
                    }

                    if(chair.is_door) {
                        return `<i class="fas fa-door-closed"></i>`
                    } else if (chair.is_space) {
                        return `<i class="fas fa-people-arrows"></i>`
                    } else if (chair.is_toilet) {
                        return `<i class="fas fa-toilet"></i>`
                    } else if (chair.is_unavailable) {
                        return `<i class="fas fa-user"></i>`
                    } else {
                        return `<i class="fas fa-chair"></i>`
                    }
                },
                loadClass(row, col, which) {
                    this.whichLayout(which);
                    let index = this.getCurrentIndexByRowCol(row, col)

                    let chair;
                    if(which == 0) {
                        chair = this.firstLayout.data.chairs.filter((e, i) =>  i == index)[0]
                    } else {
                        chair = this.secondLayout.data.chairs.filter((e, i) =>  i == index)[0]
                    }

                    if(chair.is_door) {
                        return "btn btn-info"
                    } else if (chair.is_space) {
                        return "btn btn-secondary"
                    } else if (chair.is_toilet) {
                        return "btn btn-warning"
                    } else if (chair.is_unavailable) {
                        return "btn btn-danger"
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
                    this.$refs['btn-first-layout'][this.getCurrentIndexByRowCol(row,col)].classList.value = "btn bg-teal"
                    this.$refs['btn-first-layout'][this.getCurrentIndexByRowCol(row,col)].innerHTML = `<i class="fas fa-user-check"></i>`
                },
            },
            mounted() {
                this.searchOrders()
            }
        });
    </script>
@endpush