<table>
    <thead>
        <tr>
            <th data-col="1">Tanggal Pemesanan</th>
            <th data-col="2">Kode Order</th>
            <th data-col="3">Armada</th>
            <th data-col="4">Agen</th>
            <th data-col="5">Jumlah Seat</th>
            <th data-col="6">Rute</th>
            <th data-col="7">Harga Tiket</th>
            <th data-col="8">Dana Agen</th>
            <th data-col="9">Makan</th>
            <th data-col="10">Travel</th>
            <th data-col="11">Member</th>
            <th data-col="12">Agent</th>
            <th data-col="13">Total Owner</th>
            <th data-col="14">Deposit</th>
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
                Rp.{{number_format($order_price_distribution->ticket_price)}}
            </td>
            <td data-col="8">Rp.
                {{number_format($order_price_distribution->order?->fleet_route?->price * $order_price_distribution->order?->order_detail?->count())}}
            </td>
            <td data-col="9">Rp. {{number_format($order_price_distribution->for_food,2)}}</td>
            <td data-col="10">Rp. {{number_format($order_price_distribution->for_travel,2)}}</td>
            <td data-col="11">Rp. {{number_format($order_price_distribution->for_member,2)}}</td>
            <td data-col="12">Rp. {{number_format($order_price_distribution->for_agent,2)}}</td>
            <td data-col="13">Rp. {{number_format($order_price_distribution->for_owner,2)}}</td>
            <td data-col="14">
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