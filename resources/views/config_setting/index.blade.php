@extends('layouts.main')
@section('title')
Pengaturan Global
@endsection
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Pengaturan Global Form</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">Pengaturan Global</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>
<section class="content">
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
                    <form action="{{route('config_setting.store')}}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Nominal Member</label>
                            <input type="number" class="form-control" name="member" value="{{$config_setting->member}}"
                                placeholder="Nominal Member">
                        </div>
                        <div class="form-group">
                            <label>Nominal Travel</label>
                            <input type="number" class="form-control" name="travel" value="{{$config_setting->travel}}"
                                placeholder="Nominal Travel">
                        </div>
                        <div class="form-group">
                            <label>Durasi Booking/menit</label>
                            <input type="integer" class="form-control" name="booking_expired_duration"
                                value="{{$config_setting->booking_expired_duration}}">
                        </div>
                        <div class="form-group">
                            <label>Komisi Agent (Persen)</label>
                            <input type="integer" class="form-control" name="commision"
                                value="{{$config_setting_commision}}">
                        </div>
                        <div class="form-group">
                            <label>Xendit Charge (Rp)</label>
                            <input type="integer" class="form-control" name="xendit_charge"
                                value="{{$config_setting->xendit_charge}}">
                        </div>
                        <div class="form-group">
                            <label>Default Food Price (Rp)</label>
                            <input type="integer" class="form-control" name="default_food_price"
                                value="{{$config_setting->default_food_price}}">
                        </div>
                        <div class="form-group">
                            <label>Set Time Expired (Hours:Minute)</label>
                            <input type="time" class="form-control" name="time_expired"
                                value="{{$config_setting->time_expired}}">
                        </div>
                        <div class="form-group">
                            <label>Get Point After Purchase</label>
                            <input type="integer" class="form-control" name="point_purchase"
                                value="{{$config_setting->point_purchase}}">
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
@section('script')
<script>
    $(function () {
        $('.select2').select2()
    })
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })
</script>
@endsection
