<table>
    <thead>
        <tr>
            <th>Total Penjualan</th>
            <th></th>
            <th></th>
            <th>Rp. {{number_format($outcome->sum_total_pendapatan,2)}}</th>
        </tr>
        <tr>
            <th>Tanggal</th>
            <th></th>
            <th></th>
            <th>{{$outcome->reported_at}}</th>
        </tr>
        <tr>
            <th>{{$outcome->fleet_detail?->fleet?->name}}</th>
            <th>{{$outcome->fleet_detail?->fleet?->fleetclass?->name}}</th>
        </tr>
    </thead>
        <tr>
            <td></td>
        </tr>
    <tbody>
        <tr>
            <td>Order Code</td>
            <td>Agen</td>
            <td>Jml Seat</td>
            <td>Tujuan</td>
            <td>Harga Tiket Satuan</td>
            <td>Dana Agent</td>
            <td>Makan</td>
            <td>Membership</td>
            <td>Travel</td>
            <td>Dana - Makan</td>
            <td>Komisi</td>
            <td>Setor Tiket + Makan</td>
        </tr>
        @foreach($orders as $order)
        <tr>
            <td>{{$order->code_order}}</td>
            <td>{{$order->agency->name}} {{$order->agency->city_name}}</td>
            <td>{{count($order->order_detail)}}</td>
            <td>{{$order->agency_destiny->name}} {{$order->agency_destiny->city_name}}</td>
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
        <tr>
            <td></td>
        </tr>
        <tr>
            <td>Nama Pengeluaran</td>
            <td>Jumlah Pengeluaran</td>
        </tr>
        @foreach($outcome->outcome_detail as $outcomes)
        <tr>
            <td>{{$outcomes->name}}</td>
            <td>Rp {{number_format($outcomes->amount)}}</td>
        </tr>
        @endforeach
        <tr>
            <td>Total Pengeluaran</td>
            <td>Rp {{number_format($outcome->sum_pengeluaran)}}</td>
        </tr>
    </tbody>
</table>