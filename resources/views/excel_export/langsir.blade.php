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
            <th>TUJUAN</th>
        </tr>
    </thead>
    <tbody>
        {{-- @foreach($agencies as $agency) --}}
        @foreach($langsir as $order_detail)
        @if($order_detail->order?->agency?->id == $order_detail->order?->departure_agency_id)
        <tr class="header">
            <td width="5%">{{$loop->iteration}}</td>
            <td>{{$order_detail->order?->agency?->name}}</td>
            {{-- <td>{{$agency->name}}</td> --}}
            <td width="20%">({{$order_detail->chair?->name}}),</td>
            <td width="20%">{{ $order_detail?->name }},</td>
            <td width="20%">{{ $order_detail->order?->agency_destiny?->name }}</td>
        </tr>
        @endif
        {{-- @endforeach --}}
        @endforeach
        <tr>
            <th>Bangku Kosong</th>
            <th colspan="4" style="font-size: 20px">
                @php
                $layout_chair_exists = $order_detail->with(['chairs'=>function($query) {
                $query->orderBy('index', 'asc');
                }])->pluck('layout_chair_id')->toArray();
                $layout_chair =
                \App\Models\LayoutChair::whereLayoutId(\App\Models\FleetRoute::find($fleet_route_id)->fleet_detail?->fleet?->layout_id)->orderBy('index',
                'asc')->get()->pluck('id');
                @endphp
                @foreach($layout_chair as $layout_chair_id)
                @if(!in_array($layout_chair_id, $layout_chair_exists))
                @if(\App\Models\LayoutChair::find($layout_chair_id)->is_space == false &&
                \App\Models\LayoutChair::find($layout_chair_id)->is_door == false &&
                \App\Models\LayoutChair::find($layout_chair_id)->is_toilet == false)
                {{\App\Models\LayoutChair::find($layout_chair_id)->name}},
                @endif
                @endif
                @endforeach
            </th>
        </tr>
    </tbody>
</table>