@extends('layouts.main')
@section('title')
User
@endsection
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>User Form</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">User</li>
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
                    <form action="@isset($user)
                        {{route('user.update', $user->id)}}
                    @endisset @empty($user) {{route('user.store')}} @endempty" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @isset($user)
                        @method('PUT')
                        @endisset
                        <div class="form-group">
                            <label>Nama</label>
                            <input type="text" class="form-control" name="name" placeholder="Masukkan Nama"
                                value="{{isset($user) ? $user->name : ''}}">
                        </div>
                        <div class="form-group">
                            <label>Jenis Kelamin</label>
                            <select name="gender" class="form-control">
                                <option value="">Pilih Jenis Kelamin</option>
                                @foreach ($genders as $gender)
                                <option value="{{$gender}}" @isset($user)@if ($gender==$user->gender) selected @endif
                                    @endisset>{{$gender}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="">Kota Lahir</label>
                                    <select name="birth_place" class="form-control select2" id="">
                                        <option value="">Pilih Kota</option>
                                        @foreach ($cities as $city)
                                        <option value="{{$city->id}}" @isset($user)@if ($city->id ==
                                            $user->birth_place)
                                            selected
                                            @endif
                                            @endisset>{{$city->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <label>Tanggal Lahir</label>
                                <input type="date" class="form-control" name="birth"
                                    value="{{isset($user) ? $user->birth : ''}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Phone</label>
                            <input type="number" name="phone" class="form-control" id="" placeholder="Masukkan Nomor Hp"
                                value="{{isset($user) ? $user->phone : ''}}">
                        </div>
                        <div class="form-group">
                            <label for="">Email</label>
                            <input type="email" name="email" class="form-control" name="email" id=""
                                placeholder="Masukkan Email" value="{{isset($user) ? $user->email : ''}}">
                        </div>
                        <div class="form-group">
                            <label>Alamat</label>
                            <textarea name="address" class="form-control"
                                id="">{{isset($user) ? $user->address : ''}}</textarea>
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