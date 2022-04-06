@extends('layouts.main')
@section('title')
Promo
@endsection
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Promo Form</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">Promo</li>
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
                    <form action="@isset($promo)
                        {{route('promo.update', $promo->id)}}
                    @endisset @empty($promo) {{route('promo.store')}} @endempty" method="POST" enctype="multipart/form-data">
                        @csrf
                        @isset($promo)
                        @method('PUT')
                        @endisset
                        <div class="form-group">
                            <label>Nama</label>
                            <input type="text" class="form-control" name="name" placeholder="Masukkan Nama"
                                value="{{isset($promo) ? $promo->name : ''}}">
                        </div>
                        <div class="form-group">
                            <label>Kode</label>
                            <input type="text" class="form-control" name="code" placeholder="Masukkan Kode"
                                value="{{isset($promo) ? $promo->code : ''}}" required>
                                <button class="btn btn-outline-primary btn-xs" onclick="">Generate Code</button>
                        </div>
                        <div class="form-group">
                            <label>Deskripsi</label>
                            <textarea type="text" class="form-control" name="description" placeholder="Masukkan Deskripsi">{{isset($promo) ? $promo->description : ''}}</textarea>
                        </div>
                        <div class="form-group">
                            <label>Gambar</label>
                            <input type="file" class="form-control" name="image" accept="image/*">
                            <small class="text-danger"><i class="fas fa-info-circle"></i> Pastikan ukuran gambar 445x236(72)dpi, agar hasil maksimal</small>
                            <br>
                            @isset($promo)
                            <a href="{{$promo->image}}" data-toggle="lightbox">
                                <img src="{{isset($promo) ? $promo->image : ''}}" class="img-thumbnail"
                                    style="height: 100px" alt="">
                            </a>
                            @endisset
                        </div>
                        <div class="form-group">
                            <label>Kuota</label>
                            <input type="number" class="form-control" name="quota" placeholder="Masukkan Kuota"
                                value="{{isset($promo) ? $promo->quota : ''}}">
                        </div>
                        <div class="form-group">
                            <label>Pilih User</label>
                            <select name="user_id" class="form-control select2" id="user_id">
                                <option value="">--PILIH USER--</option>
                                @foreach($users as $key => $name)
                                <option value="{{ $key }}">{{ $name }}</option>
                                @endforeach
                            </select>
                            <small class="text-danger d-none" id="refresh"><i class="fas fa-info-circle"></i> Dapat di Kosongkan jika ingin ditujukan ke semua user</small>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Tanggal Awal</label>
                                    <input type="date" class="form-control" name="start_at" required
                                        value="{{isset($promo) ? $promo->start_at : ''}}">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>Tanggal Akhir</label>
                                    <input type="date" class="form-control" name="end_at" required
                                        value="{{isset($promo) ? $promo->end_at : ''}}">
                                </div>
                            </div>
                            <small class="text-danger d-none" id="refresh"><i class="fas fa-info-circle"></i> Dapat di Kosongkan jika ingin tidak ada batasan waktu</small>
                        </div>
                        <div class="form-group">
                            <label>Diskon</label>
                            <input type="number" class="form-control" name="percentage_discount" placeholder="Masukkan Diskon %"
                                value="{{isset($promo) ? $promo->percentage_discount : ''}}" required>
                            <small class="text-danger d-none" id="refresh"><i class="fas fa-info-circle"></i> Berupa percentase</small>
                        </div>
                        <div class="form-group">
                            <label>Max Diskon</label>
                            <input type="number" class="form-control" name="maximum_discount" placeholder="Masukkan Maximal Diskon"
                                value="{{isset($promo) ? $promo->maximum_discount : ''}}">
                            <small class="text-danger d-none" id="refresh"><i class="fas fa-info-circle"></i> Kosongkan jika tidak di beri maximum</small>
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

function generateCode() {
  var text = "";
  var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

  for (var i = 0; i < 5; i++)
    text += possible.charAt(Math.floor(Math.random() * possible.length));

  return text;
}

$(function(){
    $('.btnGenerate', on)
})

console.log(generateCode());
</script>
<script>
    $(function () {
        $('.select2').select2()
    })
    $('.select2bs4').select2({
        theme: 'bootstrap4'
    })

</script>
<script>
    if ($('.select2').length > 0) {
                    $('.select2').select2();
                };
</script>
@endpush