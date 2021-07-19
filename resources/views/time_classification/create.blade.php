@extends('layouts.main')
@section('title')
Waktu
@endsection
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Waktu Form</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">Waktu</li>
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
                    <form action="@isset($time_classification)
                        {{route('time_classification.update', $time_classification->id)}}
                    @endisset @empty($time_classification) {{route('time_classification.store')}} @endempty"
                        method="POST">
                        @csrf
                        @isset($time_classification)
                        @method('PUT')
                        @endisset
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control"
                                value="{{isset($time_classification) ? $time_classification->name : ''}}"
                                placeholder="Masukkan Nama">
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Waktu Mulai</label>
                                    <input type="time" class="form-control" name="time_start"
                                        value="{{isset($time_classification) ? $time_classification->time_start : ''}}">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>Waktu Akhir</label>
                                    <input type="time" class="form-control" name="time_end"
                                        value="{{isset($time_classification) ? $time_classification->time_end : ''}}">
                                </div>
                            </div>
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