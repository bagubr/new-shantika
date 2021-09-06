<table>
    <tbody>
        <tr>
            <th>Tanggal</th>
            <th>Total Penjualan</th>
            <th>Rute</th>
            <th>Armada</th>
            <th>Kelas Armada</th>
        </tr>
        <tr>
            <td>{{$outcome->reported_at}}</td>
            <td>Rp. {{number_format($outcome->sum_total_pendapatan,2)}}</td>
            <td>{{$outcome->fleet_detail?->fleet_route()?->first()?->route?->name}}</td>
            <td>{{$outcome->fleet_detail?->fleet?->name}}</td>
            <td>{{$outcome->fleet_detail?->fleet?->fleetclass?->name}}</td>
        </tr>
    </tbody>
</table>
<table>
    <tbody>
        @if(!$orders->isEmpty())
        <tr>
            <th>Order Code</th>
            <th>Agen</th>
            <th>Kota</th>
            <th>Jml Seat</th>
            <th>Harga Tiket Satuan</th>
            <th>Dana Agent</th>
            <th>Makan</th>
            <th>Membership</th>
            <th>Travel</th>
            <th>Dana - Makan</th>
            <th>Komisi</th>
            <th>Setor Tiket + Makan</th>
        </tr>
        @foreach($orders as $order)
        <tr>
            <td>{{$order->code_order}}</td>
            <td>{{$order->agency->code}} </td>
            <td>{{$order->agency->city_name}}</td>
            <td>{{count($order->order_detail)}}</td>
            <td>{{$order->distribution->ticket_only/count($order->order_detail)}}</td>
            <td>{{$order->price}}</td>
            <td>{{$order->distribution->for_food}}</td>
            <td>{{$order->distribution->for_member}}</td>
            <td>{{$order->distribution->for_travel}}</td>
            <td>{{$order->price - $order->distribution->for_food}}</td>
            <td>{{$order->distribution->for_agent * -1}}</td>
            <td>{{$order->distribution->ticket_only + $order->distribution->for_food}}</td>
        </tr>
        @endforeach
        @endif
    </tbody>
</table>
<table>
    <tbody>
        <tr>
            <th>Nama Pengeluaran</th>
            <th>Jumlah Pengeluaran</th>
            <th>Total Pengeluaran</th>
        </tr>
        @php
        $amount = 0;
        @endphp
        @foreach($outcome->outcome_detail as $outcomes)
        <tr>
            <td>{{$outcomes->name}}</td>
            <td>Rp {{number_format($outcomes->amount)}}</td>
            @php
            $amount += $outcomes->amount;
            @endphp
            <td>Rp {{number_format($amount)}}</td>
        </tr>
        @endforeach
    </tbody>
</table>