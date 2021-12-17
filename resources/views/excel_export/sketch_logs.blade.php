<table>
    <thead>
        <tr>
            <th>Admin</th>
            <th>Kode Order</th>
            <th>Waktu Perubahan</th>
            <th>Nama Pembeli</th>
            <th>Agen Keberangkatan</th>
            <th>Dari Armada ke Armada</th>
            <th>Dari Kursi ke Kursi</th>
            <th>Dari Tanggal ke Tanggal</th>
            <th>Dari Shift ke Shift</th>
            <th>Jenis</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($logs as $log)
        <tr>
            <td>{{$log->admin?->name}}</td>
            <td>{{$log->order->code_order}}</td>
            <td>{{date('d M Y H:i:s', strtotime($log->created_at))}}</td>
            <td>{{$log->order->order_detail[0]->name}}</td>
            <td>{{$log->order->agency->name}}</td>
            <td>
                {{$log->from_fleet_route?->fleet_detail?->fleet->name}} ->
                {{$log->to_fleet_route?->fleet_detail?->fleet->name}}
            </td>
            <td>
                {{$log->from_layout_chair->name}} ->
                {{$log->to_layout_chair->name}}
            </td>
            <td>{{date('d M Y H:i:s', strtotime($log->from_date))}} ->
                {{date('d M Y H:i:s', strtotime($log->to_date))}}
            </td>
            <td>
                {{$log->from_time_classification->name}} ->
                {{$log->to_time_classification->name}}
            </td>
            <td>
                {{$log->type == 'CHANGE' ? 'Perubahan Kursi' : 'Pembatalan'}}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>