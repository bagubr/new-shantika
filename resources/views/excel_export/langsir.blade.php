<table>
    <thead>
        <tr>
            <th>Armada</th>
            <th>{{\App\Models\FleetRoute::find($fleet_route_id)->fleet_detail?->fleet?->name}}</th>
        </tr>
        <tr>
            <th>Tanggal</th>
            <th>{{date('d-m-Y', strtotime($date))}}</th>
        </tr>
        <tr>
            <th>Nomor Kendaraan</th>
            <th>{{\App\Models\FleetRoute::find($fleet_route_id)->fleet_detail?->plate_number}}</th>
        </tr>
    </thead>
</table>
<table border="1">
    <thead>
        <tr>
            <th>NO</th>
            <th>AGEN</th>
            <th>NOMOR BANGKU</th>
        </tr>
    </thead>
    <tbody>
        @foreach($agencies as $agency)
        <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{$agency->name}}</td>
            <td>
                @foreach($langsir as $order)
                @if($agency->id == $order->departure_agency_id)
                @foreach($order->order_detail->pluck('layout_chair_id') as $layout_chair_id)
                {{\App\Models\LayoutChair::find($layout_chair_id)->name}},
                @endforeach
                @endif
                @endforeach
            </td>
        </tr>
        @php
            $layout_chair_exists += $order->order_detail->pluck('layout_chair_id');
        @endphp
        @endforeach
        <tr>
            <th>Bangku Kosong</th>
            <th>
                @php
                 $layout_chair = \App\Models\LayoutChair::whereLayoutId(\App\Models\FleetRoute::find($fleet_route_id)->fleet_detail?->fleet?->layout_id)->get()->pluck('id');
                @endphp
                @foreach($layout_chair as $layout_chair_id)
                    @if(in_array($layout_chair_id, $layout_chair_exists))
                        {{\App\Models\LayoutChair::find($layout_chair_id)->name}},
                    @endif
                @endforeach
            </th>
        </tr>
    </tbody>
</table>