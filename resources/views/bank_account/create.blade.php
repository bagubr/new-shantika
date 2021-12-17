@extends('layouts.main')
@section('title')
Bank
@endsection
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Bank Form</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">Bank</li>
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
                    <form action="@isset($bank_account)
                        {{route('bank_account.update', $bank_account->id)}}
                    @endisset @empty($bank_account) {{route('bank_account.store')}} @endempty" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @isset($bank_account)
                        @method('PUT')
                        @endisset
                        <div class="form-group">
                            <label>Nama Bank</label>
                            <input type="text" class="form-control" name="bank_name" placeholder="Masukkan Nama Bank"
                                value="{{isset($bank_account) ? $bank_account->bank_name : ''}}">
                        </div>
                        <div class="form-group">
                            <label>Pemilik Rekening</label>
                            <input type="text" class="form-control" name="account_name"
                                placeholder="Masukkan Nama Pemilik Rekening"
                                value="{{isset($bank_account) ? $bank_account->account_name : ''}}">
                        </div>
                        <div class="form-group">
                            <label>Nomor Rekening</label>
                            <input type="text" class="form-control" name="account_number"
                                placeholder="Masukkan Nomor Rekening"
                                value="{{isset($bank_account) ? $bank_account->account_number : ''}}">
                        </div>
                        <div class="form-group">
                            <label>Logo Bank</label>
                            <input type="file" class="form-control" name="image" accept="image/*">
                            <span class="text-danger">Pastikan Logo Bank Anda Berukuran Lebar 150px dan Tinggi
                                100px</span>
                        </div>
                        <a href="{{URL::previous()}}" class="btn btn-secondary">Batal</a>
                        <input type="submit" value="Submit" class="btn btn-success float-right">
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@push('script')
<script src="{{asset('plugins/summernote/summernote-bs4.min.js')}}"></script>
<script>
    $(function () {
        $('.select2').select2()
    })
    $('.select2bs4').select2({
        theme: 'bootstrap4'
    })

</script>
@endpush