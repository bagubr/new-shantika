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
            <td width="5%">{{$loop->iteration}}</td>
            <td>{{$agency->name}}</td>
            <td width="70%">
                @foreach($langsir as $order)
                @if($agency->id == $order->departure_agency_id)
                @foreach($order->order_detail->pluck('layout_chair_id') as $layout_chair_id)
                {{\App\Models\LayoutChair::find($layout_chair_id)->name}},
                    @php
                        $layout_chair_exists[] = $layout_chair_id;
                    @endphp
                @endforeach
                @endif
                @endforeach
            </td>
        </tr>
        @endforeach
        <tr>
            <th>Bangku Kosong</th>
            <th colspan="2">
                @php
                 $layout_chair = \App\Models\LayoutChair::whereLayoutId(\App\Models\FleetRoute::find($fleet_route_id)->fleet_detail?->fleet?->layout_id)->get()->pluck('id');
                @endphp
                @foreach($layout_chair as $layout_chair_id)
                    @if(!in_array($layout_chair_id, $layout_chair_exists))
                        @if(\App\Models\LayoutChair::find($layout_chair_id)->is_space == false && \App\Models\LayoutChair::find($layout_chair_id)->is_door == false && \App\Models\LayoutChair::find($layout_chair_id)->is_toilet == false)
                        {{\App\Models\LayoutChair::find($layout_chair_id)->name}},
                        @endif
                    @endif
                @endforeach
            </th>
        </tr>
    </tbody>
</table>