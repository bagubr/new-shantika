@extends('layouts.main')
@section('title')
Member
@endsection
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Member Form</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">Member</li>
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
                    <form action="@isset($member)
                        {{route('member.update', $member->id)}}
                    @endisset @empty($member) {{route('member.store')}} @endempty" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @isset($member)
                        @method('PUT')
                        @endisset
                        <div class="form-group">
                            <label>Nama</label><span class="text-danger">*</span>
                            <input type="text" class="form-control" name="name" required
                                value="{{isset($member)? $member->name : ''}}" id="">
                        </div>
                        <div class="form-group">
                            <label>Alamat</label><span class="text-danger">*</span>
                            <input type="text" name="address" class="form-control" id="" required
                                value="{{isset($member) ? $member->address : ''}}">
                        </div>
                        <div class="form-group">
                            <label>Nomor Telepon</label><span class="text-danger">*</span>
                            <input type="text" name="phone" class="form-control" id="" required
                                value="{{isset($member) ? $member->phone : ''}}">
                            <span class="text-red">co. +62812345678</span>
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