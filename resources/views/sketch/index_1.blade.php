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
    @include('sketch.modal')
</div>
@endsection
@push('script')
<script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
{{-- <script src="https://cdn.jsdelivr.net/npm/vue@2"></script> --}}

<script src="https://unpkg.com/vuejs-datepicker"></script>
<script>
    var app = new Vue({
            el: '#app-sketch',
            components: {
                vuejsDatepicker
            },
            data: {
                csrf_token: '{{ csrf_token() }}',
                data: {
                    areas: {!! $areas !!}
                },
                filter: {
                    area_id: {!! $areas->first()->id !!}
                },
                result: {
                    orders: []
                },
                firstLayout: {
                    date: new Date().toDateString(),
                    fleetId: null,
                    fleetRouteId: null,
                    isLoading: false,
                    isShowInGrid: true,
                    fleet: {},
                    data: {},
                },
                secondLayout: {
                    date:  new Date().toDateString(),
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
                    fetch("{{url('/')}}/sketch/orders?"+params).then(res => res.json()).then(res => {
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
                handleDateChange(type) {
                    if(type == 'FIRST') {
                        this.getFirstLayout()
                    } else {
                        this.getSecondLayout()
                    }
                },
                getFirstLayout(fleetRouteId = null) {
                    this.firstLayout.isLoading = true
                    let params = new URLSearchParams({
                        fleet_route_id: fleetRouteId || this.firstLayout.fleetRouteId,
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
                        date: new Date(this.secondLayout.date).toDateString()
                    })
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
                    let index = this.getCurrentIndexByRowCol(row, col,which)
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
                        first_date: this.firstLayout.date,
                        second_date: this.secondLayout.date,
                        data: {
                            from_date: this.firstLayout.date,
                            to_date: this.secondLayout.date,
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
            },
            mounted() {
                this.searchOrders()
            }
        });
</script>
@endpush