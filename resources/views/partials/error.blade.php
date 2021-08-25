@if ($errors->any())
{{-- <div class="alert alert-danger">
    <ul class="list-group">
        @foreach ($errors->all() as $error)
        <li class="list-group-item text-danger">
            {{$error}}
</li>
@endforeach
</ul>
</div> --}}
<div class="callout callout-danger">
    <h4>Terjadi Masalah!</h4>
    @foreach ($errors->all() as $error)
    <li>
        {{$error}}
    </li>
    @endforeach
</div>
@endif
@if (session()->has('error'))
<div class="alert alert-danger">
    {{session()->get('error')}}
</div>
@endif