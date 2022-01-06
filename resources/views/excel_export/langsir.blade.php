<style>
    .header th {
        font-size: 20px
    }

    .header td {
        font-size: 30px
    }
</style>

<head>
    <title>Export Sketch Langsir</title>
</head>

<table>
    <thead>
        <tr>
            <th>Armada</th>
            <th>{{\App\Models\FleetRoute::find($fleet_route_id)?->fleet_detail?->fleet?->name}}</th>
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
        <tr class="header">
            <th>NO</th>
            <th>AGEN</th>
            <th>NOMOR BANGKU</th>
            <th>NAMA PENUMPANG </th>
            <th>STATUS</th>
            <th>TUJUAN</th>
        </tr>
    </thead>
    <tbody>
        @foreach($langsir as $order_detail)
        @if($order_detail->order?->agency?->id == $order_detail->order?->departure_agency_id)
        <tr class="header">
            <td width="5%">{{$loop->iteration}}</td>
            <td>{{$order_detail->order?->agency?->name}}</td>
            <td width="20%">({{$order_detail->chair?->name}}),</td>
            <td width="20%">{{ $order_detail?->name }},</td>
            <td width="20%">{{ $order_detail?->order?->status }}</td>
            <td width="20%">{{ $order_detail->order?->agency_destiny?->name }}</td>
        </tr>
        @endif
        @endforeach
        <tr>
            <th>Bangku Kosong</th>
            <th colspan="5" style="font-size: 20px">
                @php
                $layout_chair_exists = \App\Models\LayoutChair::whereIn('id', $langsir->pluck('layout_chair_id')->toArray())->pluck('id')->toArray(); 
                $layout_chair = \App\Models\LayoutChair::whereLayoutId(\App\Models\FleetRoute::find($fleet_route_id)->fleet_detail?->fleet?->layout_id)->orderBy('index','asc')->get()->pluck('id');
                @endphp
                @foreach($layout_chair as $layout_chair_id)
                    @if(
                        !in_array($layout_chair_id, $layout_chair_exists) &&
                        \App\Models\LayoutChair::find($layout_chair_id)->is_space == false &&
                        \App\Models\LayoutChair::find($layout_chair_id)->is_door == false &&
                        \App\Models\LayoutChair::find($layout_chair_id)->is_toilet == false
                    )
                    {{\App\Models\LayoutChair::find($layout_chair_id)->name}},
                    @endif
                @endforeach
            </th>
        </tr>
    </tbody>
</table>