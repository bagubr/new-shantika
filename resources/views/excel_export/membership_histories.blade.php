<table>
    <thead>
        <tr>
            <th>Kode Member</th>
            <th>Name</th>
            <th width="150px">Nomor Hp</th>
            <th>Email</th>
            <th>Agent</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($membership_histories as $membership_history)
        <tr>
            <td>{{$membership_history->membership->code_member ?? ''}}</td>
            <td>{{$membership_history->customer->name ?? ''}}</td>
            <td>{{$membership_history->customer->phone ?? ''}}</td>
            <td>{{$membership_history->customer->email ?? ''}}</td>
            <td>{{$membership_history->agency->name ?? ''}}</td>
        </tr>
        @endforeach
    </tbody>
</table>