<table>
    <thead>
        <tr>
            <th>Pemesan</th>
            <th>Kode Order</th>
            <th>Rute</th>
            <th>Armada</th>
            <th>Total Harga</th>
            <th>Status</th>
            <th>Keberangkatan -> Kedatangan</th>
            <th>Tanggal Pemesanan</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($orders as $order)
        <tr>
            <td>
                @if ($order->user?->agencies)
                {{$order->user?->name_agent}}
                @elseif ($order->user)
                {{$order->user?->name}}
                @else
                {{$order->order_detail[0]->name}}
                @endif
            </td>
            <td>{{$order->code_order}}</td>
            <td>
                @if ($order->fleet_route)
                {{$order->fleet_route?->route?->name}}
                @endif
            </td>
            <td>
                {{$order->fleet_route?->fleet_detail?->fleet?->name}}/{{$order->fleet_route?->fleet_detail?->fleet?->fleetclass?->name}}
                ({{$order->fleet_route?->fleet_detail?->nickname}})
            </td>
            <td>
                Rp. {{number_format($order->price,2)}}
            </td>
            <td>{{$order->status}}</td>
            <td>{{$order->agency?->name}} -> {{$order->agency_destiny?->name}}</td>
            <td>{{date('Y-m-d',strtotime($order->reserve_at))}}</td>
        </tr>
        @endforeach
    </tbody>
</table>