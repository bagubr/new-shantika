<div class="modal fade" id="modal-default">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 v-if="secondLayout.isLoading == false" class="modal-title">@{{this.firstLayout.fleet.name}} -> @{{this.secondLayout.fleet.name}} <i class="fas fa-retweet float-right" @click="relaodLayout()"></i></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p v-if="secondLayout.isLoading == false">Memindahkan penumpang dari bis <b>@{{this.firstLayout.fleet.name}}</b> ke
                        <b>@{{this.secondLayout.fleet.name}}</b></p>
                    <div class="row">
                        <div :class="[data.oneLayout == false ? 'col-12 col-lg-6 border-right':'col-12 col-lg-12 border-right']">
                            <div v-if="secondLayout.isLoading == false" class="form-group position-md-sticky bg-white pb-1 pt-1" style="top: -17px">
                                <div class="row mb-1">
                                    <div class="col-12 text-right">
                                        <a class="btn btn-secondary" :href="printFirstLayout()" target="_blank"><i class="fas fa-print"></i> Print Sketch Langsir</a>
                                    </div>
                                </div>
                                <label for="">Tanggal</label>
                                <div class="row">
                                    <div class="col-12">
                                        <vuejs-datepicker v-model="firstLayout.date" input-class="form-control bg-white" @input="handleDateChange('FIRST')" format="yyyy-MM-dd"/>
                                    </div>
                                </div>
                                <label for="">Shift</label>
                                <div class="row">
                                    <div class="col-12">
                                        <select @change="selectOptionFirstLayout()" v-model="firstLayout.timeClassificationId" class="form-control" id="">
                                            <option v-for="timeClassification in data.timeClassifications" :value="timeClassification.id" 
                                                :key="timeClassification.id">@{{timeClassification.name}}</option>
                                        </select>
                                    </div>
                                </div>
                                <label for="">Armada</label>
                                <div class="row">
                                    <div class="col">
                                        <select @change="selectOptionFirstLayout($event)" v-model="firstLayout.fleetRouteId" class="form-control" id="">
                                            <option :value="fleetRoute.id" v-for="fleetRoute in fleetRoutes"
                                                :key="fleetRoute.id" v-text="setSelectOptionLayoutText(fleetRoute)"></option>
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
                                <div v-if="firstLayout.isShowInGrid" :class="[data.oneLayout == false ? 'overflow-auto':'overflow-auto m-auto']" :style="{'max-width': data.oneLayout == true ? '50%' : '100%'}">
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
                                        <div v-if="chair.order_detail != undefined" class="border-top border-bottom pt-3 pb-3">
                                            <div class="row">
                                                <div class="col">
                                                    <span class="font-weight-bold">@{{chair.name}} -
                                                        @{{chair.order_detail.order_detail[0].name}}
                                                        (@{{chair.order_detail?.user?.agencies?.agent?.name}})  (@{{chair.order_detail?.user?.name}})</span>
                                                </div>
                                                <div class="col-auto">
                                                    <span class="badge badge">@{{chair.order_detail.status}}</span>
                                                </div>
                                            </div>
                                            <span target="_blank">
                                                @{{chair.order_detail.order_detail[0].phone}} |
                                                <a :href="'https://wa.me/'+chair.order_detail.order_detail[0].phone">
                                                    <i class="fab fa-whatsapp"></i>
                                                    Whatsapp
                                                </a> |
                                                <a :href="'tel:'+chair.order_detail.order_detail[0].phone">
                                                    <i class="fas fa-phone"></i>
                                                    Telepon
                                                </a>
                                            </span>
                                            <br>
                                            <span>Agen Keberangkatan: @{{chair.order_detail.agency?.name}}</span>
                                            <br>
                                            <span>Agen Tujuan: @{{chair.order_detail.agency_destiny?.name}}</span>
                                            <br>
                                            <span>@{{chair.order_detail.code_order}}</span>
                                            <br>
                                            <div class="row">
                                                <div class="col-12">
                                                    <form autocomplete="off" @submit.prevent="cancelOrder(chair.order_detail.order_detail.filter(e => e.layout_chair_id == chair.id)[0].id, chair.password, chair.cancelation_reason, chair.isAll)" class="w-100">
                                                        <div class="form-group">
                                                            <label for="">Alasan Penolakan</label>
                                                            <input type="text" autocomplete="off" v-model="chair.cancelation_reason" class="form-control">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="">Password</label>
                                                            <input type="password" autocomplete="new-password" v-model="chair.password" class="form-control" id="">
                                                        </div>
                                                        <div class="form-check">
                                                            <input type="checkbox" v-model="chair.is_all" class="form-check-input" id="">
                                                            <label class="form-check-label">&nbsp; Batalkan 1 rombongan ini</label>
                                                        </div>
                                                        <div class="form-group text-right">
                                                            <button class="btn btn-primary" type="submit">Submit</button>
                                                            <button class="btn btn-secondary" type="button" @click="chair.is_show_cancel_order = false">Close</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
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
                        <div v-if="data.oneLayout == false" class="col-12 col-lg-6 border-right">
                            <div v-if="secondLayout.isLoading == false" class="form-group position-md-sticky bg-white pb-1 pt-1" style="top: -17px">
                                <div class="row mb-1">
                                    <div class="col-12 text-right">
                                        <a class="btn btn-secondary" :href="printSecondLayout()" target="_blank"><i class="fas fa-print"></i> Print Sketch Langsir</a>
                                    </div>
                                </div>
                                <label for="">Tanggal</label>
                                <div class="row">
                                    <div class="col-12">
                                        <vuejs-datepicker v-model="secondLayout.date" input-class="form-control bg-white" @input="handleDateChange('SECOND')" format="yyyy-MM-dd"/>
                                    </div>
                                </div>
                                <label for="">Shift</label>
                                <div class="row">
                                    <div class="col-12">
                                        <select @change="selectOptionSecondLayout()" v-model="secondLayout.timeClassificationId" class="form-control" id="">
                                            <option v-for="timeClassification in data.timeClassifications" :value="timeClassification.id" 
                                                :key="timeClassification.id">@{{timeClassification.name}}</option>
                                        </select>
                                    </div>
                                </div>
                                <label for="">Armada</label>
                                <div class="row">
                                    <div class="col">
                                        <select @change="selectOptionSecondLayout($event)" v-model="secondLayout.fleetRouteId" class="form-control" id="">
                                            <option :value="fleetRoute.id" v-for="fleetRoute in fleetRoutes"
                                                :key="fleetRoute.id" v-text="setSelectOptionLayoutText(fleetRoute)"></option>
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
                                <div v-if="secondLayout.isShowInGrid" class="overflow-auto">
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
                @unlessrole('owner')

                <div v-if="secondLayout.isLoading == false" class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-secondary" @click="reset()">Reset</button>
                    <button type="button" class="btn btn-primary" @click="submit()">Save</button>
                </div>
                <div v-if="secondLayout.isLoading == false" class="modal-footer">
                    <div class="col-3 form-group form-check">
                        <input class="form-check-input" type="checkbox" v-model="data.is_group" id="flexCheckDefault" @click="changeIsGroup()">
                        <label class="form-check-label" for="flexCheckDefault">
                          Pilih Kursi Rombongan
                        </label>
                    </div>
                    <div class="col-3 form-group form-check">
                        <input class="form-check-input" type="checkbox" v-model="data.is_unit" id="flexCheckUnit" @click="changeIsUnit()">
                        <label class="form-check-label" for="flexCheckUnit">
                          Pilih Kursi
                        </label>
                    </div>
                    <div class="col-3 form-group form-check">
                        <input class="form-check-input" type="checkbox" v-model="data.is_delete_group" id="flexCheckDefault1" @click="changeIsDeleteGroup()">
                        <label class="form-check-label" for="flexCheckDefault1">
                            Hapus Kursi Rombongan
                        </label>
                    </div>
                    <div class="col-3 form-group form-check">
                        <input class="form-check-input" type="checkbox" v-model="data.is_delete_unit" id="flexCheckDefaultDunit" @click="changeIsDeleteUnit()">
                        <label class="form-check-label" for="flexCheckDefaultDunit">
                          Hapus Kursi
                        </label>
                    </div>
                    <div class="col-12">
                        <p class="badge bg-primary">Kursi Kosong</p>
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
                @endunlessrole
            </div>
        </div>
    </div>