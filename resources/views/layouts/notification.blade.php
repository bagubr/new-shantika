@inject('notification', 'App\Repositories\AdminNotificationRepository')
@foreach($notification->all() as $item)
    <a class="dropdown-item" href="">
        {{$item->title}} · 
        <small class="text-secondary font-italic">{{date('d M Y H:i', $item->created_at)}}</small> ·
        <small class="text-secondary font-italic">{{ $item->type }}</small>
        <br>
        <span class="text-secondary">
            {{strlen($item->body) > 150 ? substr($item->body,0, 150)."..." : $item->body }}
        </span>
    </a>
@endforeach
