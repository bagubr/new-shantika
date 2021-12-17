@inject('notification', 'App\Repositories\AdminNotificationRepository')
@foreach($notification::all() as $item)
{{-- <a class="dropdown-item" href="{{url('notification/'.$item->id)}}" target="_blank"> --}}
    <a href="#" class="dropdown-item">
        {{$item->title}} ·
        <small class="text-secondary font-italic">{{date('d M Y H:i', strtotime($item->created_at))}}</small> ·
        <small class="text-secondary font-italic">{{ $item->type }}</small>
        <br>
        <span class="text-secondary">
            {{strlen($item->body) > 150 ? substr($item->body,0, 150)."..." : $item->body }}
        </span>
    </a>
    {{-- </a> --}}
@endforeach

{{-- <a class="dropdown-item text-lightblue" href="">
    Lihat Semua...
</a> --}}