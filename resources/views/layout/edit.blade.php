@extends('layouts.main')
@section('title')
    Layout Armada
@endsection
@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Layout Armada</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">Armada</li>
                    <li class="breadcrumb-item active">Layout</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<div class="content" x-data="layout">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-md-2">
                <table class="table-bordered table-striped table-primary w-100">
                    <tbody>
                        <tr>
                            <td>Jumlah Kursi</td>
                            <td>0</td>
                        </tr>
                        <tr>
                            <td>Jumlah Toilet</td>
                            <td>0</td>
                        </tr>
                        <tr>
                            <td>Jumlah Pintu</td>
                            <td>0</td>
                        </tr>
                        <tr>
                            <td>Jumlah Jarak</td>
                            <td>0</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-12 col-md-8">
                <template x-for="r in layout.row">
                    <div class="row">
                        <template x-for="c in layout.col">
                            <button :class="setClass(r,c, $el)" x-text="getText(r,c)" :id="getIndex(r,c)" x-on:click="addData($el)"></button>
                        </template>
                    </div>
                    <br>
                </template>
            </div>
            <div class="col-12 col-md-2">
                <template x-for="(f, focusIndex) in focuses">
                    <div class="row justify-content-center">
                        <button :class="`btn btn-${f.class} w-75 text-capitalize my-1 ${focusIndex == focus ? 'active' : ''}`" x-on:click="changeFocus(focusIndex)" x-text="f.name"></button>
                    </div>
                </template>
            </div>
            <hr class="col-12">
            <div class="col-2 align-self-end offset-10">
                <div class="row justify-content-center">
                    <button class="btn btn-primary w-75">
                        Lanjutkan
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('script')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('layout', () => ({
            layout: {!!$layout!!},
            formData: {
                chairs: [],
                toiletIndex: [],
                doorIndex: [],
                spaceIndex: [],
            },
            focuses: [
                {
                    name: 'toilet',
                    class: 'warning'
                }, 
                {
                    name: 'door',
                    class: 'info'
                },
                {
                    name: 'space',
                    class: 'secondary'
                }
            ],
            focus: 0,
            incrementIndex: 0,

            getIndex(row, col) {
                return (((row - 1) * this.layout.col) + col) - 1
            },
            setButtonClass(string = '', id = null) {
                let classes = 'text-capitalize col m-1 btn btn-' + string
                if(id != null) {
                    document.getElementById(id).removeAttribute('class')
                    document.getElementById(id).setAttribute('class', classes)
                    document.getElementById(id).innerHTML = this.focuses[this.focus].name
                }
                return classes
            },
            changeFocus(focus) {
                this.focus = focus
            },
            checkInUnusedIndex(index) {
                return new Promise((resolve, reject) => {
                    if(this.layout.toilet_indexes.includes(index)) {
                        resolve(this.focuses[0])
                    }
                    else if(this.layout.door_indexes.includes(index)) {
                        resolve(this.focuses[1])
                    }
                    else if(this.layout.space_indexes.includes(index)) {
                        resolve(this.focuses[2])
                    } else {
                        reject()
                    }
                })
            },
            getText(row, col) {
                let index = this.getIndex(row, col)
                let elem = document.getElementById(index)
                this.checkInUnusedIndex(index).then(res => {
                    elem.innerText = res.name
                }).catch(() => {
                    elem.innerText = 'Pnmpg'
                })
            },
            async setClass(row, col, el) {
                let index = this.getIndex(row, col)
                let elem = document.getElementById(index)

                let classes = this.setButtonClass()

                await this.checkInUnusedIndex(index).then((res) => {
                    classes += res.class
                    el.setAttribute('class', classes)
                }).catch(() => {
                    classes += 'primary'
                    el.setAttribute('class', classes)
                })
            },
            addData(el) {
                let index = el.id
                if(this.formData.toiletIndex.includes(index) 
                || this.formData.doorIndex.includes(index)
                || this.formData.spaceIndex.includes(index)) {
                    return this.removeData(el)
                }
                switch (this.focus) {
                    case 0:
                        this.formData.toiletIndex.push(index)
                        this.setButtonClass(this.focuses[0].class, index)
                        break;
                    case 1:
                        this.formData.doorIndex.push(index)
                        this.setButtonClass(this.focuses[1].class, index)
                        break;
                    case 2:
                        this.formData.spaceIndex.push(index)
                        this.setButtonClass(this.focuses[2].class, index)
                        break;
                    default:
                        break;
                }
            },
            removeData(el) {
                this.setButtonClass('primary', el.id)
                switch (this.focus) {
                    case 0:
                        this.formData.toiletIndex = this.formData.toiletIndex.filter(e => e != el.id)
                        break;
                    case 1:
                        this.formData.doorIndex = this.formData.doorIndex.filter(e => e != el.id)
                        break;
                    case 2:
                        this.formData.spaceIndex = this.formData.spaceIndex.filter(e => e != el.id)
                        break;
                    default:
                        break;
                }
                document.getElementById(el.id).innerText = 'Pnmpg'
            }
        }))
    })
</script>
@endpush