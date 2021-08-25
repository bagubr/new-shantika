<table>
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>Nama Rute</th>
            <th>Nama Pengeluaran</th>
            <th>Jumlah Pengeluaran</th>
        </tr>
    </thead>
    <tbody>
        @foreach($outcomes as $outcome)
        <tr>
            <td>{{date('Y-m-d',strtotime($outcome->outcome?->reported_at))}}</td>
            <td>{{$outcome->outcome?->fleet_route?->route?->name}}/{{$outcome->outcome?->fleet_route?->fleet?->name}}
            </td>
            <td>{{$outcome->name}}</td>
            <td>Rp {{number_format($outcome->amount)}}</td>
        </tr>
        @endforeach
    </tbody>
</table>