@extends('layouts.main')
@section('title')
    Blocking Kursi
@endsection
@push('css')
    <link rel="stylesheet" href="{{ asset('css/lightbox.min.css') }}">
<<<<<<< HEAD
=======
    <style>    
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
>>>>>>> rilisv1
@endpush
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Armada Rute (Bloking Kursi)</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item">Armada Rute</li>
                        <li class="breadcrumb-item active">Bloking Kursi</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <div class="content" id="app">
<<<<<<< HEAD
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
=======
        <div class="container-fluid" id="app-blocked-chairs">
            <div class="row">
                <div class="col-6">
>>>>>>> rilisv1
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Blocking Kursi Armada {{$fleet_route->fleet_detail->fleet->name}}, Rute {{$fleet_route->route->name}}</h3>
                        </div>
<<<<<<< HEAD
                        <div class="card-body" id="app-blocked-chairs">
                            <div class="overflow-auto">
                                <div v-for="i in layout.row" class="d-flex">
                                    <div v-for="j in layout.col" class="m-1">
                                        <button style="width: 100px; height: 45px" ref="btn-second-layout"
                                            class="btn btn-primary" v-html="loadText(i,j)" :class="loadClass(i,j)"
                                            @click="toggleBlock(i,j)">
                                        </button>
=======
                        <div v-if="isLoading" class="w-100 row justify-content-center">
                            <lottie-player src="https://assets7.lottiefiles.com/packages/lf20_Stt1R6.json"
                                background="transparent" speed="1" style="width: 100px; height: 100px;" loop
                                autoplay></lottie-player>
                        </div>
                        <div v-if="isLoading == false">
                            <div class="card-body">
                                <div class="overflow-auto">
                                    <div v-for="i in layout.row" class="d-flex">
                                        <div v-for="j in layout.col" class="m-1">
                                            <button style="width: 100px; height: 45px" ref="btn-second-layout"
                                                class="btn btn-primary" v-html="loadText(i,j)" :class="loadClass(i,j)"
                                                @click="toggleBlock(i,j)">
                                            </button>
                                        </div>
>>>>>>> rilisv1
                                    </div>
                                </div>
                            </div>
                        </div>
<<<<<<< HEAD
=======
                        <div class="col-12">
                            <p class="badge bg-primary">Kursi Kosong</p>
                            <p class="badge bg-dark">Kursi Di Blokir</p>
                            <p class="badge bg-secondary">Space</p>
                            <p class="badge bg-warning">Toilet</p>
                            <p class="badge bg-info">Pintu</p>
                            <p class="badge bg-green">Pindah</p>
                            <p class="badge bg-teal">Terpilih</p>
                            <p class="badge bg-danger">Pembelian Dari Agent</p>
                            <p class="badge bg-orange">Booking Dari Agent</p>
                            <p class="badge bg-purple">Pembelian Dari Customer</p>
                            <p class="badge bg-chocolate">Pembelian Dari Customer Menunggu Pembayaran</p>
                            <p class="badge bg-chocolate-light">Pembelian Dari Customer Menunggu Konfirmasi</p>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Form Pilih Tanggal</h3>
                        </div>
                        <div class="card-body" id="app-blocked-chairs">
                            <div class="form-group">
                                <vuejs-datepicker v-model="blocked_date" input-class="form-control"
                                format="yyyy-MM-dd" />
                            </div>
                            <button @click="searchOrders()" class="btn btn-success" type="submit">Cari</button>
                        </div>
>>>>>>> rilisv1
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
<<<<<<< HEAD
    <script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
    <script>
        var app = new Vue({
            el: '#app-blocked-chairs',
            data: {
                'layout': {!! $layout !!},
                'blockedChairs': {!! $blocked_chairs !!},
                'fleetRoute': {!! $fleet_route !!}
=======
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
    <script src="https://unpkg.com/vuejs-datepicker"></script>
    <script>
        var app = new Vue({
            el: '#app-blocked-chairs',
            components: {
                vuejsDatepicker
            },
            data: {
                'isLoading': false,
                'layout': {!! $layout !!},
                'fleetRoute': {!! $fleet_route !!},
                'blocked_date': new Date().toDateString()
>>>>>>> rilisv1
            },
            methods: {
                getCurrentIndexByRowCol(row, col) {
                    return (((row - 1) * this.layout.col) + col) - 1
                },
                loadText(row, col) {
<<<<<<< HEAD
                    let index = this.getCurrentIndexByRowCol(row, col)
                    let chair = this.layout.chairs.filter((e, i) =>  i == index)[0]

                    if (chair.is_blocked) {
                        return `<p class="text-nowrap">${chair.name}</p>`
                    } else if (chair.is_door) {
                        return `<span><i class="fas fa-door-closed"></i></span>`
=======
                    let chair;
                    let html;
                    html = `<marquee>`
                    let index = this.getCurrentIndexByRowCol(row, col)
                    chair = this.layout.chairs.filter((e, i) =>  i == index)[0]
                    
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
>>>>>>> rilisv1
                    } else if (chair.is_space) {
                        return `<span><i class="fas fa-people-arrows"></i></span>`
                    } else if (chair.is_toilet) {
                        return `<span><i class="fas fa-toilet"></i></span>`
                    } else {
                        return `<span>${chair.name}</span>`
                    }
<<<<<<< HEAD
=======

                    if(chair.order_detail?.order_detail?.length??0 > 1){
                        chair.order_detail.order_detail.forEach(function (value, key) {
                            return html += `(${value.chair_name})`
                        })
                    }
                    html += `</marquee>`
                    
                    return html
>>>>>>> rilisv1
                },
                loadClass(row, col) {
                    let index = this.getCurrentIndexByRowCol(row, col)
                    let chair = this.layout.chairs.filter((e, i) =>  i == index)[0]

<<<<<<< HEAD
                    if (chair.is_blocked) {
                        return "btn btn-danger"
=======
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
>>>>>>> rilisv1
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
<<<<<<< HEAD
=======
                searchOrders() {
                    this.isLoading = true
                    let params = new URLSearchParams({
                        date: new Date(this.blocked_date).toDateString(),
                        fleet_route_id: this.fleetRoute.id,
                    })
                    fetch("{{url('/')}}/sketch/orders/detail?"+params)
                    .then(res => res.json()).then(res => {
                        console.log(res)
                        this.layout = res.data
                    })
                    .finally(() => {
                        this.isLoading = false
                    })
                },
>>>>>>> rilisv1
                toggleBlock(row, col) {
                    let index = this.getCurrentIndexByRowCol(row, col);
                    let chair = this.layout.chairs.filter((e, i) => i == index)[0]
                    
<<<<<<< HEAD
                    fetch(`{{url('fleet_route/block_chair/${this.fleetRoute.id}/${chair.id}')}}`, {
                        method: 'PUT',
                        headers: {
=======
                    if(chair.is_unavailable){
                        return alert('Silahkan Pilih Kursi yang kosong')
                    }
                    if(chair.is_unavailable_customer){
                        return alert('Silahkan Pilih Kursi yang kosong')
                    }
                    if(chair.is_unavailable_not_paid_customer){
                        return alert('Silahkan Pilih Kursi yang kosong')
                    }
                    if(chair.is_unavailable_waiting_customer){
                        return alert('Silahkan Pilih Kursi yang kosong')
                    }
                    if(chair.is_door){
                        return alert('Silahkan Pilih Kursi yang kosong')
                    }
                    if(chair.is_space){
                        return alert('Silahkan Pilih Kursi yang kosong')
                    }
                    if(chair.is_booking){
                        return alert('Silahkan Pilih Kursi yang kosong')
                    }
                    
                    fetch(`{{url('fleet_route/block_chair/${this.fleetRoute.id}/${chair.id}')}}`, {
                        method: 'PUT',
                        body:JSON.stringify({
                            'blocked_date':this.blocked_date,
                        }),
                        headers: {
                            'Content-Type': 'application/json',
>>>>>>> rilisv1
                            'X-CSRF-TOKEN': '{{csrf_token()}}'
                        }
                    }).then(res => res.json()).then(res => {
                        this.layout.chairs.filter((e, i) => i == index)[0].is_blocked = res.is_blocked
                        this.$forceUpdate()
                        toastr.success("Berhasil mengubah status blokir kursi")
                    }).catch(() => {
                        toastr.error("Gagal mengubah status blokir kursi")
                    })
                }
            }
        });
    </script>
@endpush
