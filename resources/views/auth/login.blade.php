@extends('layouts.main')
@section('title')
Login
@endsection
@push('css')
<style>
    .btn-primary {
        background-color: #271D5F;
        border-color: #1a1342;
    }

    .btn-primary:hover {
        background-color: #1a1342;
        border-color: #271D5F;
    }

    .btn-primary:not(:disabled):not(.disabled).active,
    .btn-primary:not(:disabled):not(.disabled):active,
    .show>.btn-primary.dropdown-toggle {
        color: #fff;
        background-color: #1a1342;
        border-color: #271D5F;
    }

    .btn-primary.focus,
    .btn-primary:focus {
        background-color: #1a1342;
        border-color: #271D5F;
    }

    .login-box,
    .register-box {
        width: 75%;
    }

    .col-8.image.p-0 {
        background-image: url("{{asset('img/cover-web-new-shantika.png')}}");
        background-size: cover;
    }

    @keyframes animatedBackground {
        from {
            background-position: 0 0;
        }

        to {
            background-position: 100% 0;
        }
    }

    #animate-area {
        background-image: url("{{asset('img/cover-web-new-shantika.png')}}");
        background-position: 0px 0px;
        background-size: cover;
        background-repeat: repeat-x;
        animation: animatedBackground 100s linear infinite alternate;
    }
</style>
@endpush
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-4 p-0 hold-transition login-page">
            <div class="login-logo">
                <img class="img-fluid" src="{{asset('img/Logo-new-shantika-app (2).png')}}" alt="" style="height:100px">
            </div>
            <div class="login-box">
                <div class="card">
                    <div class="card-header" style="background-color: #271D5F">
                    </div>
                    <div class="card-body login-card-body">
                        <p class="login-box-msg">Masukkan Username dan Password</p>

                        <form action="{{ route('login') }}" method="post">
                            @csrf
                            <div class="input-group mb-3">
                                <input id="email" autofocus type="email"
                                    class="form-control @error('email') is-invalid @enderror" placeholder="Email"
                                    name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-envelope"></span>
                                    </div>
                                </div>
                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror

                            </div>
                            <div class="input-group mb-3">
                                <input id="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror" name="password"
                                    required placeholder="Password" autocomplete="current-password">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-lock"></span>
                                    </div>
                                </div>
                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="row">
                                <div class="col-8">
                                    <div class="icheck-primary">
                                        <input type="checkbox" name="remember" id="remember"
                                            {{ old('remember') ? 'checked' : '' }}>
                                        <label for="remember">
                                            Ingat Saya
                                        </label>
                                    </div>
                                </div>
                                <!-- /.col -->
                                <div class="col-4">
                                    <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                                </div>
                                <!-- /.col -->
                            </div>
                        </form>
                        {{-- @if (Route::has('password.request'))
                    <p class="mb-1">
                        <a href="{{ route('password.request') }}">I forgot my password</a>
                        </p>
                        @endif --}}
                        {{-- <p class="mb-0">
                        <a href="register.html" class="text-center">Register a new membership</a>
                    </p> --}}
                    </div>
                    <!-- /.login-card-body -->
                </div>
                <div class="mt-3">
                    <p class="text-center" style="color: #271D5F">Support by
                        <a target="_blank" href="https://can.co.id/" target="_blank">Can Creative</a> | <a href="{{url('_/privacy_policy')}}" target="_blank">Kebijakan Privasi</a>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-8 p-0" id="animate-area">
        </div>
    </div>
</div>
@endsection
@push('js')
<script>
    // 1 detik = 1000
  window.setTimeout("waktu()",1000);  
  function waktu() {   
  var tanggal = new Date();  
  setTimeout("waktu()",1000);  
  document.getElementById("jam").innerHTML = tanggal.getHours()+":"+tanggal.getMinutes()+":"+tanggal.getSeconds();
  }
</script>
@endpush