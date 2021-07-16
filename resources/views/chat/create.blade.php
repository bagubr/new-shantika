@extends('layouts.main')
@section('title')
Chat
@endsection
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Chat Form</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">Chat</li>
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
                    <form action="@isset($chat)
                        {{route('chat.update', $chat->id)}}
                    @endisset @empty($chat) {{route('chat.store')}} @endempty" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @isset($chat)
                        @method('PUT')
                        @endisset
                        <div class="form-group">
                            <label>Chat Nama</label>
                            <input type="text" class="form-control" name="name" placeholder="Masukkan Nama"
                                value="{{isset($chat) ? $chat->name : ''}}">
                        </div>
                        <div class="form-group">
                            <label for="">Value</label>
                            <textarea name="value" id="" class="form-control"
                                placeholder="Masukkan Message">{{isset($chat) ? $chat->value : ''}}</textarea>
                        </div>
                        <div class="form-group">
                            <label>Type</label>
                            <input type="text" class="form-control" name="type" placeholder="Masukkan Type"
                                value="{{isset($chat) ? $chat->type : ''}}">
                        </div>
                        <div class="form-group">
                            <label>Icon</label>
                            <input type="text" class="form-control" name="icon" placeholder="Masukkan Icon"
                                value="{{isset($chat) ? $chat->icon : ''}}">
                        </div>
                        <div class="form-group">
                            <label>Role</label>
                            <input type="text" class="form-control" name="role" placeholder="Masukkan Role"
                                value="{{isset($chat) ? $chat->icon : ''}}">
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
<script>
    $(function () {
        $('.select2').select2()
    })
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })
</script>
@endpush