@extends('layouts.main')
@section('title')
FAQ
@endsection
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>FAQ Form</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">FAQ</li>
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
                    <form action="@isset($faq)
                        {{route('faq.update', $faq->id)}}
                    @endisset @empty($faq) {{route('faq.store')}} @endempty" method="POST">
                        @csrf
                        @isset($faq)
                        @method('PUT')
                        @endisset
                        <div class="form-group">
                            <label>Question</label>
                            <textarea class="form-control" rows="3" name="question"
                                placeholder="Enter Question">{{isset($faq) ? $faq->question : ''}}</textarea>
                        </div>
                        <div class="form-group">
                            <label>Answer</label>
                            <textarea class="form-control" rows="3" name="answer"
                                placeholder="Enter Answer">{{isset($faq) ? $faq->answer : ''}}</textarea>
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
@section('script')
<script>
    $(function () {
        $('.select2').select2()
    })
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })
</script>
@endsection