@extends('layouts.main')
@section('title')
    Blocking Kursi
@endsection
@push('css')
    <link rel="stylesheet" href="{{ asset('css/lightbox.min.css') }}">
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
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Blocking Kursi</h3>
                        </div>
                        <div class="card-body" id="app-blocked-chairs">
                            <div class="overflow-auto">
                                <div v-for="i in layout.row" class="d-flex">
                                    <div v-for="j in layout.col" class="m-1">
                                        <button style="width: 100px; height: 45px" ref="btn-second-layout"
                                            class="btn btn-primary" v-html="loadText(i,j)" :class="loadClass(i,j)"
                                            @click="toggleBlock(i,j)">
                                        </button>
                                    </div>
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
    <script>
        var app = new Vue({
            el: '#app-blocked-chairs',
            data: {
                'layout': {!! $layout !!},
                'blockedChairs': {!! $blocked_chairs !!},
                'fleetRoute': {!! $fleet_route !!}
            },
            methods: {
                getCurrentIndexByRowCol(row, col) {
                    return (((row - 1) * this.layout.col) + col) - 1
                },
                loadText(row, col) {
                    let index = this.getCurrentIndexByRowCol(row, col)
                    let chair = this.layout.chairs.filter((e, i) =>  i == index)[0]

                    if (chair.is_blocked) {
                        return `<p class="text-nowrap">${chair.name}</p>`
                    } else if (chair.is_door) {
                        return `<span><i class="fas fa-door-closed"></i></span>`
                    } else if (chair.is_space) {
                        return `<span><i class="fas fa-people-arrows"></i></span>`
                    } else if (chair.is_toilet) {
                        return `<span><i class="fas fa-toilet"></i></span>`
                    } else {
                        return `<span>${chair.name}</span>`
                    }
                },
                loadClass(row, col) {
                    let index = this.getCurrentIndexByRowCol(row, col)
                    let chair = this.layout.chairs.filter((e, i) =>  i == index)[0]

                    if (chair.is_blocked) {
                        return "btn btn-danger"
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
                toggleBlock(row, col) {
                    let index = this.getCurrentIndexByRowCol(row, col);
                    let chair = this.layout.chairs.filter((e, i) => i == index)[0]
                    
                    fetch(`{{url('fleet_route/block_chair/${this.fleetRoute.id}/${chair.id}')}}`, {
                        method: 'PUT',
                        headers: {
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
