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
                            <label>Chat Nama</label><span class="text-danger">*</span>
                            <input type="text" class="form-control" name="name" placeholder="Masukkan Nama"
                                value="{{isset($chat) ? $chat->name : ''}}">
                        </div>
                        <div class="form-group">
                            <label>Link</label><span class="text-danger">*</span>
                            <input type="text" name="link" class="form-control" placeholder="Masukkan Link"
                                value="{{isset($chat) ? $chat->link: ''}}">
                        </div>
                        <i class="text-danger">Contoh whatsapp https://wa.me/+6282xxxxxx</i>
                        <div class="form-group">
                            <label for="">Pesan</label>
                            <textarea class="form-control" name="value" id="test1"
                                placeholder="Masukkan Pesan">{{isset($chat) ? $chat->value : ''}}</textarea>
                            <i class="text-danger">Masukkan pesan jika menggunakan link whatsapp</i>
                        </div>
                        <div class="form-group">
                            <label>Tipe</label><span class="text-danger">*</span>
                            <select name="type" class="form-control" id="">
                                <option value="">Pilih Tipe</option>
                                <option value="CUST" @isset($chat) @if ($chat->type == 'CUST')
                                    selected
                                    @endif @endisset>Customer</option>
                                <option value="AGENT" @isset($chat) @if ($chat->type == 'AGENT')
                                    selected
                                    @endif @endisset>Agent</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Icon</label>
                            <input type="file" class="form-control" name="icon" accept="image/*">
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
{{-- <script>
    $('#test1').keyup(function (){
    str = $(this).val()
    str = str.replace(/\s/g,'%20')
    $('#test2').val(str);
});
</script> --}}
@endpush