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
            <div class="col-md-12" v-if="result.orders == 0">
                <div class="card p-5">
                    <h3>
                        Data tidak di temukan
                    </h3>
                </div>
            </div>
            <div v-for="order in result.orders" class="col-12 col-sm-6 col-lg-4 col-xl-3">
                <div class="card h-100 p-2 shadow" data-toggle="modal" data-target="#modal-default"
                    @click="handleChangeFocusFirstLayout(order.fleet_route_id, order.fleet_route.fleet_id)">
                    <div class="card-body">
                        <div class="row m-1">
                            <div class="col-md-3 text-center">
                                <i class="fas fa-bus" style="font-size: 2.5em; color: Mediumslateblue;"></i>
                            </div>
                            <div class="col-md-9">
                                <b>@{{order.fleet_route?.fleet_detail?.fleet?.name}}
                                    (@{{order.fleet_route?.fleet_detail?.fleet?.fleetclass?.name}})</b>
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
                    <p>Memindahkan penumpang dari bis <b>@{{this.firstLayout.fleet.name}}</b> ke
                        <b>@{{this.secondLayout.fleet.name}}</b></p>
                    <div class="row">
                        <div class="col-6 border-right">
                            <div class="form-group position-sticky bg-white pb-1 pt-1" style="top: -17px">
                                <label for="">Armada</label>
                                <div class="row">
                                    <div class="col">
                                        <select @change="selectOptionFirstLayout($event)" class="form-control" id="">
                                            <option :value="order.fleet_route_id" v-for="order in result.orders"
                                                :key="order.id" v-text="setSelectOptionLayoutText(order)"></option>
                                        </select>
                                    </div>
                                    <div class="col-auto">
                                        <button class="btn btn-secondary" @click="firstLayout.isShowInGrid = false">
                                            <i class="fas fa-list"></i>
                                        </button>
                                        <button class="btn btn-secondary" @click="firstLayout.isShowInGrid = true">
                                            <i class="fas fa-th"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div v-if="firstLayout.isLoading" class="w-100 row justify-content-center">
                                <lottie-player src="https://assets7.lottiefiles.com/packages/lf20_Stt1R6.json"
                                    background="transparent" speed="1" style="width: 100px; height: 100px;" loop
                                    autoplay></lottie-player>
                            </div>
                            <div v-else>
                                <div v-if="firstLayout.isShowInGrid">
                                    <div v-for="i in firstLayout.data.row" class="d-flex">
                                        <div v-for="j in firstLayout.data.col" class="m-1">
                                            <button v-html="loadText(i,j,0)" :class="loadClass(i,j,0)"
                                                style="width: 100px; height: 45px" ref="btn-first-layout"
                                                @click="selectSeat(i,j,0)"></button>
                                        </div>
                                    </div>
                                </div>
                                <div v-else>
                                    <div v-for="chair in firstLayout.data.chairs" class="w-100">
                                        <div v-if="chair.order_detail != undefined" class="border-top border-bottom">
                                            <p class="font-weight-bold">@{{chair.name}} -
                                                @{{chair.order_detail.order_detail[0].name}}
                                                (@{{chair.order_detail?.user?.agencies?.agent?.name || 'Customer'}})</p>
                                            <a :href="'https://wa.me/'+chair.order_detail.order_detail[0].phone"
                                                target="_blank">@{{chair.order_detail.order_detail[0].phone}}</a>
                                            <p>Status Pembelian Tiket: @{{chair.order_detail.status}}</p>
                                            <p>@{{chair.order_detail.code_order}}</p>
                                        </div>
                                        <div v-if="chair.booking_detail != undefined" class="border-top border-bottom">
                                            <p class="font-weight-bold text-warning">@{{chair.name}} -
                                                @{{chair.booking_detail.name}}
                                                (@{{chair.order_detail?.user?.agencies?.agent?.name || 'Customer'}})</p>
                                            <a :href="'https://wa.me/'+chair.booking_detail.phone"
                                                target="_blank">@{{chair.booking_detail.phone}}</a>
                                            <p>Jam Kadaluarsa Booking: @{{chair.booking_detail.expired_at}}</p>
                                            <p>@{{chair.booking_detail.code_booking}}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 border-right">
                            <div class="form-group position-sticky bg-white pb-1 pt-1" style="top: -17px">
                                <label for="">Armada</label>
                                <div class="row">
                                    <div class="col">
                                        <select @change="selectOptionSecondLayout($event)" name="" class="form-control"
                                            id="">
                                            <option :value="order.fleet_route_id" v-for="order in result.orders"
                                                :key="order.id" v-text="setSelectOptionLayoutText(order)"></option>
                                        </select>
                                    </div>
                                    <div class="col-auto">
                                        <button class="btn btn-secondary" @click="secondLayout.isShowInGrid = false">
                                            <i class="fas fa-list"></i>
                                        </button>
                                        <button class="btn btn-secondary" @click="secondLayout.isShowInGrid = true">
                                            <i class="fas fa-th"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div v-if="secondLayout.isLoading" class="w-100 row justify-content-center">
                                <lottie-player src="https://assets7.lottiefiles.com/packages/lf20_Stt1R6.json"
                                    background="transparent" speed="1" style="width: 100px; height: 100px;" loop
                                    autoplay></lottie-player>
                            </div>
                            <div v-else>
                                <div v-if="secondLayout.isShowInGrid">
                                    <div v-for="i in secondLayout.data.row" class="d-flex">
                                        <div v-for="j in secondLayout.data.col" class="m-1">
                                            <button v-html="loadText(i,j,1)" :class="loadClass(i,j,1)"
                                                style="width: 100px; height: 45px" ref="btn-second-layout"
                                                @click="dropSelectedSeat(i,j,1)"></button>
                                        </div>
                                    </div>
                                </div>
                                <div v-else>
                                    <div v-for="chair in secondLayout.data.chairs" class="w-100">
                                        <div v-if="chair.order_detail != undefined" class="border-top border-bottom">
                                            <p class="font-weight-bold">@{{chair.name}} -
                                                @{{chair.order_detail.order_detail[0].name}}
                                                (@{{chair.order_detail?.user?.agencies?.agent?.name || 'Customer'}})</p>
                                            <a :href="'https://wa.me/'+chair.order_detail.order_detail[0].phone"
                                                target="_blank">@{{chair.order_detail.order_detail[0].phone}}</a>
                                            <p>Status Pembelian Tiket: @{{chair.order_detail.status}}</p>
                                            <p>@{{chair.order_detail.code_order}}</p>
                                        </div>
                                        <div v-if="chair.booking_detail != undefined" class="border-top border-bottom">
                                            <p class="font-weight-bold text-warning">@{{chair.name}} -
                                                @{{chair.booking_detail.name}}
                                                (@{{chair.order_detail?.user?.agencies?.agent?.name || 'Customer'}})</p>
                                            <a :href="'https://wa.me/'+chair.booking_detail.phone"
                                                target="_blank">@{{chair.booking_detail.phone}}</a>
                                            <p>Jam Kadaluarsa Booking: @{{chair.booking_detail.expired_at}}</p>
                                            <p>@{{chair.booking_detail.code_booking}}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-danger" @click="reset()">Reset</button>
                    <button type="button" class="btn btn-primary" @click="submit()">Save</button>
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
                    fleetId: null,
                    fleetRouteId: null,
                    isLoading: false,
                    isShowInGrid: true,
                    fleet: {},
                    data: {},
                },
                secondLayout: {
                    fleetId: null,
                    fleetRouteId: null,
                    isLoading: false,
                    isShowInGrid: true,
                    fleet: {},
                    data: {}
                }
            },
            methods: {
                searchOrders() {
                    let params = new URLSearchParams(this.filter)
                    fetch('/sketch/orders?'+params).then(res => res.json()).then(res => {
                        this.result.orders = res.orders
                    })
                },
                setSelectOptionLayoutText(order) {
                    let fleetName = order.fleet_route.fleet_detail.fleet.name
                    let fleetClass = order.fleet_route.fleet_detail.fleet.fleetclass.name
                    let routeName = order.fleet_route.route.name

                    return `${fleetName} (${fleetClass} | ${routeName})`
                },
                selectOptionFirstLayout(event) {
                    this.getFirstLayout(event.currentTarget.value)
                },
                selectOptionSecondLayout(event) {
                    this.getSecondLayout(event.currentTarget.value)
                },
                handleChangeFocusFirstLayout(fleetRouteId, fleetId) {
                    this.firstLayout.fleetRouteId = fleetRouteId
                    this.firstLayout.fleetId = fleetId
                    this.getFirstLayout()
                    this.handleChangeFocusSecondLayout(fleetRouteId, fleetId)
                },
                handleChangeFocusSecondLayout(fleetRouteId, fleetId) {
                    this.secondLayout.fleetRouteId = fleetRouteId
                    this.secondLayout.fleetId = fleetId
                    this.getSecondLayout()
                },
                getFirstLayout(fleetRouteId = null) {
                    this.firstLayout.isLoading = true
                    let params = new URLSearchParams({
                        fleet_route_id: fleetRouteId || this.firstLayout.fleetRouteId,
                        date: this.filter.date
                    })
                    fetch('/sketch/orders/detail?'+params).then(res => res.json()).then(res => {
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
                        date: this.filter.date
                    })
                    fetch('/sketch/orders/detail?'+params).then(res => res.json()).then(res => {
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
                        chair = this.firstLayout.data.chairs.filter((e, i) =>  i == index)[0]
                    } else {
                        chair = this.secondLayout.data.chairs.filter((e, i) =>  i == index)[0]
                    }

                    if (chair.is_unavailable) {
                        if(chair.is_selected) {
                            return `<marquee>${chair.name} <i class="fas fa-user-check"></i> ${chair.code}</marquee>`
                        } else if (chair.is_switched) {
                            return `<marquee>${chair.name} <i class="fas fa-user-tag"></i> ${chair.code}</marquee>`
                        }
                        return `<marquee>${chair.name} <i class="fas fa-user"></i> ${chair.code}</marquee>`
                    } else if (chair.is_booking) {
                        return `<marquee>${chair.name} <i class="fas fa-user-tag"></i> ${chair.code}</marquee>`
                    } else if(chair.is_door) {
                        return `<span><i class="fas fa-door-closed"></i></span>`
                    } else if (chair.is_space) {
                        return `<span><i class="fas fa-people-arrows"></i></span>`
                    } else if (chair.is_toilet) {
                        return `<span><i class="fas fa-toilet"></i></span>`
                    } else {
                        return `<span>${chair.name} <i class="fas fa-chair"></i></span>`
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

                    if (chair.is_unavailable) {
                        if(chair.is_selected) {
                            return "btn bg-teal"
                        } else if (chair.is_switched) {
                            return "btn bg-green"
                        }
                        return "btn btn-danger"
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
                    let index = this.getCurrentIndexByRowCol(row, col)
                    if(!this.firstLayout.data.chairs.filter(e => e.index == index)[0].is_unavailable) {
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
                    let value = this.firstLayout.data.chairs.filter(e => e.is_selected == true)[0]
                    this.firstLayout.data.chairs.filter(e => e.is_selected  == true)[0].is_switched = true
                    this.firstLayout.data.chairs.filter(e => e.is_selected == true)[0].is_selected = false

                    this.secondLayout.data.chairs.filter((e, i) => i == index)[0].is_unavailable = true
                    this.secondLayout.data.chairs.filter((e, i) => i == index)[0].is_selected = true
                    this.$forceUpdate()
                },
                submit() {
                    let form = {
                        first_fleet_route_id: this.firstLayout.fleetRouteId,
                        second_fleet_route_id: this.secondLayout.fleetRouteId,
                        first_fleet_id: this.firstLayout.fleetId,
                        second_fleet_id: this.secondLayout.fleetId,
                        date: this.filter.date,
                        data: {
                            from_layout_chair_id: this.firstLayout.data.chairs.filter(e => e.is_switched == true),
                            to_layout_chair_id: this.secondLayout.data.chairs.filter(e => e.is_selected == true),
                        }
                    }

                    fetch('/sketch/store', {
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
            },
            mounted() {
                this.searchOrders()
            }
        });
</script>
@endpush