@extends('layouts.main')
@section('title', 'Souvenir')
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Souvenir</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">Souvenir</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            {{-- <div class="col-6">
                <a href="{{route('souvenir.create')}}" class="btn btn-primary mb-2">Buat Souvenir Baru</a>
            </div> --}}
            <div class="col-md-12">
                <table class="table table-striped">
                    <thead>

                    </thead>
                    <tbody>
                        @foreach($data as $each)
                        <tr>

                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{$data -> links("pagination::bootstrap-4")}}
            </div>
        </div>
    </div>
</div>
@endsection
@push('script')

@endpush
