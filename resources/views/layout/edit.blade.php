@extends('layouts.main')
@section('title')
    Layout Armada
@endsection
@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                @if(request()->is('layouts/create'))
                <h1 class="m-0">Create Layout Armada</h1>
                @else
                <h1 class="m-0">Edit Layout Armada "{{$layout->name}}"</h1>
                @endif
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
            <div class="col-12 col-md-6">
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="">Nama Layout</label>
                            <input type="text" class="form-control" x-model="layout.name">
                        </div>
                        <div class="form-group">
                            <label for="">Jumlah Baris</label>
                            <div class="row">
                                <div class="col-3">
                                    <button class="form-control" x-on:click="decreaseRow()">-</button>
                                </div>
                                <div class="col-6">
                                    <input type="text" type="number" x-model="layout.row" class="form-control text-center" x-init="$watch('layout.row', (val, old) => handleRowChange(val, old))">
                                </div>
                                <div class="col-3">
                                    <button class="form-control" x-on:click="increaseRow()">+</button>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">Jumlah Kolom</label>
                            <div class="row">
                                <div class="col-3">
                                    <button class="form-control" x-on:click="decreaseCol()">-</button>
                                </div>
                                <div class="col-6">
                                    <input type="text" type="number" x-model="layout.col" class="form-control text-center" x-init="$watch('layout.col', (val, old) => handleColChange(val, old))">
                                </div>
                                <div class="col-3">
                                    <button class="form-control" x-on:click="increaseCol()">+</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="">Note</label>
                            <textarea x-model="layout.note" class="form-control" rows="7"></textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <table class="table-bordered table-striped table-primary w-100">
                            <tbody>
                                <tr>
                                    <td>Jumlah Kursi</td>
                                    <td x-text="(layout.col * layout.row) - (layout.toilet_indexes.length + layout.door_indexes.length + layout.space_indexes.length)"></td>
                                </tr>
                                <tr>
                                    <td>Jumlah Toilet</td>
                                    <td x-text="layout.toilet_indexes.length | 0"></td>
                                </tr>
                                <tr>
                                    <td>Jumlah Pintu</td>
                                    <td x-text="layout.door_indexes.length || 0"></td>
                                </tr>
                                <tr>
                                    <td>Jumlah Jarak</td>
                                    <td x-text="layout.space_indexes.length || 0"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <template x-for="r in layout.row">
                    <div class="row">
                        <template x-for="c in layout.col">
                            <div :class="setClass(r,c, $el)" :id="getIndex(r,c)" x-on:click="addData($el)" contenteditable="true" x-text="getText($el)"> 
                            </div>
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
                    @if(!request()->is('layouts/create'))
                    <button class="btn btn-primary w-75" x-on:click="submitEdit($el)">
                        Lanjutkan
                    </button>
                    @else
                    <button class="btn btn-primary w-75" x-on:click="submitCreate($el)">
                        Lanjutkan
                    </button>
                    @endif
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
            layout: {!! @$layout !!},
            formData: {},
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

            getText(el) {
                try {
                    return this.layout.chairs.filter(e => e.index == el.id)[0].name
                } catch(err) {
                    return ''
                }
            },
            increaseCol() {
                this.layout.col += 1
            },
            decreaseCol() {
                this.layout.col -= 1
            },
            increaseRow() {
                this.layout.row += 1
            },
            decreaseRow() {
                this.layout.row -= 1  
            },
            refilterIndexes() {
                this.layout.toilet_indexes = this.layout.toilet_indexes.filter((e, i) => e <= this.layout.row * this.layout.col - 1)
                this.layout.space_indexes = this.layout.space_indexes.filter((e, i) => e <= this.layout.row * this.layout.col - 1)
                this.layout.door_indexes = this.layout.door_indexes.filter((e, i) => e <= this.layout.row * this.layout.col - 1)
            },
            handleRowChange(val, old) {
                this.layout.row = parseInt(val)
                this.refilterIndexes()
            },
            handleColChange(val, old) {
                this.layout.col = parseInt(val)
                this.refilterIndexes()
            },
            getIndex(row, col) {
                return (((row - 1) * this.layout.col) + col) - 1
            },
            setButtonClass(string = '', id = null) {
                let classes = 'text-capitalize col m-1 btn btn-' + string
                if(id != null) {
                    document.getElementById(id).removeAttribute('class')
                    document.getElementById(id).setAttribute('class', classes)
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
            setClass(row, col, el) {
                let index = this.getIndex(row, col)
                let classes = this.setButtonClass()

                if(this.layout.toilet_indexes.includes(index)) {
                    classes = this.setButtonClass(this.focuses[0].class)
                }
                else if(this.layout.door_indexes.includes(index)) {
                    classes = this.setButtonClass(this.focuses[1].class)
                }
                else if(this.layout.space_indexes.includes(index)) {
                    classes = this.setButtonClass(this.focuses[2].class)
                } else {
                    classes = this.setButtonClass('primary') 
                }

                return classes
            },
            addData(el) {
                let index = el.id
                if(this.layout.toilet_indexes.includes(index) 
                || this.layout.door_indexes.includes(index)
                || this.layout.space_indexes.includes(index)) {
                    return this.removeData(el)
                }
                switch (this.focus) {
                    case 0:
                        this.layout.toilet_indexes.push(index)
                        this.setButtonClass(this.focuses[0].class, index)
                        break;
                    case 1:
                        this.layout.door_indexes.push(index)
                        this.setButtonClass(this.focuses[1].class, index)
                        break;
                    case 2:
                        this.layout.space_indexes.push(index)
                        this.setButtonClass(this.focuses[2].class, index)
                        break;
                    default:
                        break;
                }
            },
            removeData(el) {
                this.setButtonClass('primary', el.id)
                this.layout.toilet_indexes = this.layout.toilet_indexes.filter(e => e != el.id)
                this.layout.door_indexes = this.layout.door_indexes.filter(e => e != el.id)
                this.layout.space_indexes = this.layout.space_indexes.filter(e => e != el.id)
            },
            submitEdit(el) {
                el.disable = true;
                let csrf = '{!!csrf_token()!!}'
                let arr = []
                let total_indexes = (this.layout.row * this.layout.col) - 1
                for(let i=0;i <= total_indexes;i++) {
                    arr.push(i)
                }
                arr = arr.filter(e => !this.layout.space_indexes.includes(e))
                    .filter(e => !this.layout.toilet_indexes.includes(e))
                    .filter(e => !this.layout.door_indexes.includes(e))
                arr = arr.map((e, i) => {
                    return {
                        name: document.getElementById(e).innerText || i + 1,
                        index: e
                    }
                })
                this.formData = {
                    id: this.layout.id,
                    name: this.layout.name,
                    row: this.layout.row,
                    col: this.layout.col,
                    space_indexes: this.layout.space_indexes.map(e => parseInt(e)),
                    toilet_indexes: this.layout.toilet_indexes.map(e => parseInt(e)),
                    door_indexes: this.layout.door_indexes.map(e => parseInt(e)),
                    chair_indexes: arr,
                    note: this.layout.note || ''
                }
                let formData = this.formData
                fetch(`http://shantika.idaman.org/layouts/${this.layout.id}`, {
                    body: JSON.stringify(formData),
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': csrf,
                        'Content-Type': 'application/json',
                    }
                }).then(res => res.json()).finally(e => {
                    el.disable = false;
                })
            },
            submitCreate(el) {
                el.disable = true;
                let csrf = '{!!csrf_token()!!}'
                let arr = []
                let total_indexes = (this.layout.row * this.layout.col) - 1
                for(let i=0;i <= total_indexes;i++) {
                    arr.push(i)
                }
                arr = arr.filter(e => !this.layout.space_indexes.includes(e))
                    .filter(e => !this.layout.toilet_indexes.includes(e))
                    .filter(e => !this.layout.door_indexes.includes(e))
                arr = arr.map((e, i) => {
                    return {
                        name: document.getElementById(e).innerText || i + 1,
                        index: e
                    }
                })
                this.formData = {
                    name: this.layout.name,
                    row: this.layout.row,
                    col: this.layout.col,
                    space_indexes: this.layout.space_indexes,
                    toilet_indexes: this.layout.toilet_indexes,
                    door_indexes: this.layout.door_indexes,
                    chair_indexes: arr,
                    note: this.layout.note || ''
                }
                let formData = this.formData
                fetch('http://localhost:8000/layouts', {
                    body: JSON.stringify(formData),
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrf,
                        'Content-Type': 'application/json',
                    }
                }).then(res => res.json()).finally(e => {
                    el.disable = false;
                })
            }
        }))
    })
</script>
@endpush