@extends('layouts.main')
@section('title')
Fleet
@endsection
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Fleet Class Form</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">Fleet Class</li>
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
                    <form action="@isset($fleetclass)
                        {{route('fleetclass.update', $fleetclass->id)}}
                    @endisset @empty($fleetclass) {{route('fleetclass.store')}}" @endempty method="POST">
                        @csrf
                        @isset($fleetclass)
                        @method('PUT')
                        @endisset
                        <div class="form-group">
                            <label for="inputName">Fleet Name</label>
                            <input type="text" id="inputName" class="form-control" name="name"
                                value="{{isset($fleetclass) ? $fleetclass->name : ''}}">
                        </div>
                        <a href="{{URL::previous()}}" class="btn btn-secondary">Cancel</a>
                        <input type="submit" value="Create new Porject" class="btn btn-success float-right">
                    </form>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>
    <div class="row">
        <div class="col-12">

        </div>
    </div>
</section>
@endsection