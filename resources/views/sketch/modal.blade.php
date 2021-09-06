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
                                <div class="row">
                                    <a class="btn btn-primary" :href="printFirstLayout()" target="_blank">Print</a>
                                </div>
                                <label for="">Tanggal</label>
                                <div class="row">
                                    <div class="col-12">
                                        <vuejs-datepicker v-model="firstLayout.date" input-class="form-control bg-white" @input="handleDateChange('FIRST')" format="yyyy-MM-dd"/>
                                    </div>
                                </div>
                                <label for="">Armada</label>
                                <div class="row">
                                    <div class="col">
                                        <select @change="selectOptionFirstLayout($event)" v-model="firstLayout.fleetRouteId" class="form-control" id="">
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
                                <div class="row">
                                    <a class="btn btn-primary" :href="printSecondLayout()" target="_blank">Print</a>
                                </div>
                                <label for="">Tanggal</label>
                                <div class="row">
                                    <div class="col-12">
                                        <vuejs-datepicker v-model="secondLayout.date" input-class="form-control bg-white" @input="handleDateChange('SECOND')" format="yyyy-MM-dd"/>
                                    </div>
                                </div>
                                <label for="">Armada</label>
                                <div class="row">
                                    <div class="col">
                                        <select @change="selectOptionSecondLayout($event)" v-model="secondLayout.fleetRouteId" class="form-control"
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