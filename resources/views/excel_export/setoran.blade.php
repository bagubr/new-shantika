<table>
    <thead>
        <tr>
            <th>Tanggal Pemesanan</th>
            <th>Kode Order</th>
            <th>Armada</th>
            <th>Agen</th>
            <th>Jumlah Seat</th>
            <th>Rute</th>
            <th>Harga Tiket</th>
            <th>Dana Agen</th>
            <th>Makan</th>
            <th>Travel</th>
            <th>Member</th>
            <th>Agent</th>
            <th>Total Owner</th>
            <th>Deposit</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($order_price_distributions as $order_price_distribution)
        <tr>
            <td>{{date('Y-m-d',strtotime($order_price_distribution->order?->reserve_at))}}</td>
            <td>
                @if ($order_price_distribution->order)
                <a href="{{route('order.show',$order_price_distribution->order?->id)}}">
                    {{$order_price_distribution->order?->code_order}}
                </a>
                @endif
            </td>
            <td>{{$order_price_distribution->order?->fleet_route?->fleet_detail?->fleet?->name}}/{{$order_price_distribution->order?->fleet_route?->fleet_detail?->fleet?->fleetclass?->name}}
                ({{$order_price_distribution->order?->fleet_route?->fleet_detail?->nickname}})
            </td>
            <td>{{$order_price_distribution->order?->agency?->name}}</td>
            <td>
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
            <td>
                @if ($order_price_distribution->order?->fleet_route)
                <a
                    href="{{route('fleet_route.show',$order_price_distribution->order?->fleet_route_id)}}">
                    {{$order_price_distribution->order?->fleet_route?->route?->name}}
                </a>
                @endif
            </td>
            <td>Rp. {{number_format($order_price_distribution->order?->fleet_route?->price)}}
            </td>
            <td>Rp.
                {{number_format($order_price_distribution->order?->fleet_route?->price * $order_price_distribution->order?->order_detail?->count())}}
            </td>
            <td>Rp. {{number_format($order_price_distribution->for_food,2)}}</td>
            <td>Rp. {{number_format($order_price_distribution->for_travel,2)}}</td>
            <td>Rp. {{number_format($order_price_distribution->for_member,2)}}</td>
            <td>Rp. {{number_format($order_price_distribution->for_agent,2)}}</td>
            <td>Rp. {{number_format($order_price_distribution->for_owner,2)}}</td>
            <td>
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