<table>
    <thead>
        <tr>
            <th data-col="1">Tanggal Pemesanan</th>
            <th data-col="2">Kode Order</th>
            <th data-col="3">Armada</th>
            <th data-col="4">Agen</th>
            <th data-col="5">Jumlah Seat</th>
            <th data-col="6">Rute</th>
            <th data-col="7">Harga Tiket Satuan</th>
            <th data-col="8">Harga Tiket</th>
            <th data-col="9">Dana Agen</th>
            <th data-col="10">Makan</th>
            <th data-col="11">Travel</th>
            <th data-col="12">Member</th>
            <th data-col="13">Agent</th>
            <th data-col="14">Total Owner</th>
            <th data-col="15">Deposit</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($order_price_distributions as $order_price_distribution)
        <tr>
            <td data-col="1">{{date('Y-m-d',strtotime($order_price_distribution->order?->reserve_at))}}</td>
            <td data-col="2">
                @if ($order_price_distribution->order)
                <a href="{{route('order.show',$order_price_distribution->order?->id)}}">
                    {{$order_price_distribution->order?->code_order}}
                </a>
                @endif
            </td>
            <td data-col="3">{{$order_price_distribution->order?->fleet_route?->fleet_detail?->fleet?->name}}/{{$order_price_distribution->order?->fleet_route?->fleet_detail?->fleet?->fleetclass?->name}}
                ({{$order_price_distribution->order?->fleet_route?->fleet_detail?->nickname}})
            </td>
            <td data-col="4">{{$order_price_distribution->order?->agency?->name}}</td>
            <td data-col="5">
                {{$order_price_distribution->order?->order_detail?->count()}}
                (
                @foreach ($order_price_distribution->order?->order_detail??[] as $order_detail)
                {{$order_detail->chair?->name}}
                @if (!$loop->last)
                ,
                @endif
                @endforeach
                )
            </td>
            <td data-col="6">
                @if ($order_price_distribution->order?->fleet_route)
                <a
                    href="{{route('fleet_route.show',$order_price_distribution->order?->fleet_route_id)}}">
                    {{$order_price_distribution->order?->fleet_route?->route?->name}}
                </a>
                @endif
            </td>
            <td data-col="7">
                @if($order_price_distribution->ticket_price <= 0)
                {{number_format($order_price_distribution->ticket_price, 2, '.')}}
                @else
                {{number_format($order_price_distribution->ticket_price/ $order_price_distribution->order?->order_detail?->count(), 2, '.')}}
                @endif
            </td>
            <td data-col="8">
                {{number_format($order_price_distribution->ticket_price, 2, '.')}}
            </td>
            <td data-col="9">
                {{number_format($order_price_distribution->order?->fleet_route?->price * $order_price_distribution->order?->order_detail?->count(), 2, '.')}}
            </td>
            <td data-col="10"> {{number_format($order_price_distribution->for_food,2, '.')}}</td>
            <td data-col="11"> {{number_format($order_price_distribution->for_travel,2, '.')}}</td>
            <td data-col="12"> {{number_format($order_price_distribution->for_member,2, '.')}}</td>
            <td data-col="13"> {{number_format($order_price_distribution->for_agent,2, '.')}}</td>
            <td data-col="14"> {{number_format($order_price_distribution->for_owner,2, '.')}}</td>
            <td data-col="15">
                @if ($order_price_distribution->deposited_at)
                {{date('Y-m-d', strtotime($order_price_distribution->deposited_at))}}
                @else
                Belum Deposit
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>