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
            <th>NAMA PENUMPANG </th>
            <th>TUJUAN</th>
        </tr>
    </thead>
    <tbody>
        @php
        $index = 0;
        @endphp
        @foreach($agencies as $agency)
            @foreach($langsir as $order)
                @if($agency->id == $order->departure_agency_id)
                    @foreach($order->order_detail as $order_detail)
                    @php
                        $layout_chair_exists[] = $order_detail->layout_chair_id;
                    @endphp
                    <tr>
                        <td width="5%">{{$loop->iteration}}</td>
                        <td>{{$agency->name}}</td>
                        <td width="20%">({{\App\Models\LayoutChair::find($order_detail->layout_chair_id)->name}}),</td>
                        <td width="20%">{{ $order_detail->name }},</td>
                        <td width="20%">{{ \App\Models\Agency::find($order->destination_agency_id)->name }}</td>
                    </tr>
                    @endforeach
                @endif
            @endforeach
        @endforeach
        <tr>
            <th>Bangku Kosong</th>
            <th colspan="4">
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