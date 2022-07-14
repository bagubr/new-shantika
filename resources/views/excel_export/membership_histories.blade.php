<table>
    <thead>
        <tr>
            <th>Kode Order</th>
            <th>Kode Member</th>
            <th>Name</th>
            <th width="150px">Nomor Hp</th>
            <th>Email</th>
            <th>Agent</th>
            <th>Total Potongan</th>
            <th>Tanggal Penggunaan</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($membership_histories as $membership_history)
        <tr>
            @if ($membership_history->order_id)
            <td><a href="{{url('order')}}/{{$membership_history->order_id}}">{{$membership_history->code_order}}</a></td>
            @else
            <td>Tidak di temukan</td>
            @endif
            <td>{{$membership_history->membership->code_member ?? ''}}</td>
            <td>{{$membership_history->customer->name ?? ''}}</td>
            <td>{{$membership_history->customer->phone ?? ''}}</td>
            <td>{{$membership_history->customer->email ?? ''}}</td>
            <td>{{$membership_history->agency->name ?? ''}}</td>
            <td>Rp. {{number_format(@$membership_history->order?->distribution?->for_member ?? 0)}}</td>
            <td>{{$membership_history->created_at ?? ''}}</td>
        </tr>
        @endforeach
    </tbody>
</table>