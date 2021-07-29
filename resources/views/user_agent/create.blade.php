@extends('layouts.main')
@section('title')
User Agent
@endsection
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>User Agent Form</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">User Agent</li>
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
                    <form action="@isset($user_agent)
                        {{route('user_agent.update', $user_agent->id)}}
                    @endisset @empty($user_agent) {{route('user_agent.store')}} @endempty" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @isset($user_agent)
                        @method('PUT')
                        @endisset
                        <div class="form-group">
                            <label>Nama</label><span class="text-danger">*</span>
                            <input type="text" class="form-control" name="name" placeholder="Masukkan Nama"
                                value="{{isset($user_agent) ? $user_agent->name : old('name')}}">
                        </div>
                        <div class="form-group">
                            <label>Jenis Kelamin</label><span class="text-danger">*</span>
                            <select name="gender" class="form-control">
                                <option value="">Pilih Jenis Kelamin</option>
                                @foreach ($genders as $gender)
                                <option value="{{$gender}}" @isset($user_agent)@if ($gender==$user_agent->gender)
                                    selected @endif
                                    @endisset>{{$gender}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Agent</label><span class="text-danger">*</span>
                            <select name="agency_id" class="form-control select2" id="">
                                <option value="">Pilih Agent</option>
                                @foreach ($agencies as $agency)
                                <option value="{{$agency->id}}" @isset($user_agent) @if ($agency->id ==
                                    $user_agent->agencies->agency_id)
                                    selected
                                    @endif
                                    @endisset>{{$agency->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Kota Lahir</label><span class="text-danger">*</span>
                                    <input type="text" name="birth_place" class="form-control"
                                        value="{{isset($user_agent) ? $user_agent->birth_place: old('birth_place') }}"
                                        placeholder="Masukkan Kota Kelahiran">
                                </div>
                            </div>
                            <div class="col">
                                <label>Tanggal Lahir</label><span class="text-danger">*</span>
                                <input type="date" class="form-control" name="birth"
                                    value="{{isset($user_agent) ? $user_agent->birth : old('birth')}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Phone</label><span class="text-danger">*</span>
                            <input type="text" name="phone" class="form-control" id="" placeholder="Masukkan Nomor Hp"
                                value="{{isset($user_agent) ? $user_agent->phone : old('phone')}}">
                            <span class="text-red">co. +62812345678</span>
                        </div>
                        <div class="form-group">
                            <label for="">Email</label><span class="text-danger">*</span>
                            <input type="email" name="email" class="form-control" name="email" id=""
                                placeholder="Masukkan Email"
                                value="{{isset($user_agent) ? $user_agent->email : old('email')}}">
                        </div>
                        <div class="form-group">
                            <label>Alamat</label><span class="text-danger">*</span>
                            <textarea name="address" class="form-control"
                                id="">{{isset($user_agent) ? $user_agent->address : old('address')}}</textarea>
                        </div>
                        <div class="form-group">
                            <label>Avatar</label>
                            <input type="file" accept="image/*" class="form-control" name="avatar">
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