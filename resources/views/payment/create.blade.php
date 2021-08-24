@extends('layouts.main')
@section('title')
Detail Pembayaran
@endsection
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Detail Pembayaran Form</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">Detail Pembayaran</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>
<section class="content" id="app_payment">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Form</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body" style="display: block;">
                    @include('partials.error')
                    <form action="@isset($payment)
                        {{route('payment.update', $payment->id)}}
                    @endisset @empty($payment) {{route('payment.store')}} @endempty" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @isset($payment)
                        @method('PUT')
                        @endisset
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Kode Order</label>
                                    <input type="text" name="code_order" class="form-control" readonly
                                        value="{{isset($payment) ? $payment->order->code_order : ''}}"
                                        placeholder="Masukkan Nama Pembayaran">
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Tipe Pembayaran</label>
                                    <select class="form-control" name="payment_type_id" style="width: 100%;" readonly>
                                        <option value="">Pilih Tipe Pembayaran</option>
                                        @foreach ($payment_types as $payment_type)
                                        <option value="{{$payment_type->id}}" @isset($payment) @if ($payment_type->id
                                            ===
                                            $payment->payment_type_id)
                                            selected
                                            @endif @endisset>{{$payment_type->name}}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">

                                    <label>Status</label>
                                    <select name="status" class="form-control" v-model="status">
                                        <option value="">Select Status</option>
                                        @foreach ($statuses as $status)
                                        <option value="{{$status}}" @isset($payment) @if ($status==$payment->status)
                                            selected
                                            @endif
                                            @endisset>{{$status}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>Dibayar Pada Tanggal</label>
                                    <input type="date" class="form-control" name="paid_at" value="{{ date('Y-m-d') }}">
                                </div>
                            </div>
                        </div>

                        <div class="form-group" v-if="status == 'DECLINED'">
                            <label>Alasan Penolakan</label>
                            <textarea name="proof_decline_reason" class="form-control"
                                rows="5">{{isset($payment) ? $payment->proof_decline_reason : ''}}</textarea>
                        </div>
                        <div class="form-group">
                            @isset($payment)
                            <a href="{{$payment->proof_url}}" target="blank">
                                <img src="{{$payment->proof_url}}" height="100px" alt="">
                            </a>
                            @endisset
                        </div>
                        <a href="{{URL::previous()}}" class="btn btn-secondary">Batal</a>
                        <input type="submit" value="Submit" class="btn btn-success float-right">
                    </form>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>
</section>
@endsection
@push('script')
<script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
<script>
    var app = new Vue({
        el: '#app_payment',
        data: {
            message: 'Hello Vue!',
            status: '{{$payment->status}}',
        }
    });
</script>
@endpush