<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        * {
            font-family: 'Helvetica', sans-serif; 
        }
        .table-collapse {
            border-collapse: collapse;
        }
        .border-black {
            border: 1px solid black;
        }
        th {
            font-weight: bold;
            border: 1px solid black;
            padding-left: 8px;
            padding-right: 8px;
            padding-bottom: 4px;
            padding-top: 4px;
        }
        td {
            border: 1px solid black;
            padding-left: 8px;
            padding-right: 8px;
            padding-bottom: 4px;
            padding-top: 4px;
            font-weight: normal;
        }
    </style>
</head>
<body>
    Terima kasih, anda telah melakukan pemesanan pada vendor kami!
    <br>
    <p>Berikut adalah detail pesanan anda:</p>
    <table class="table-collapse">
        <thead>
            <tr class="border-black">
                <th>Nama Armada</th>
                <th>Kelas Armada</th>
                <th>Titik Keberangkatan</th>
                <th>Jam Keberangkatan</th>
                <th>Titik Tujuan</th>
                <th>Jam Sampai Tujuan</th>
            </tr>
        </thead>
        <tbody>
            <tr class="border-black">
                <td>{{ $fleet_name }}</td>
                <td>{{ $fleet_class }}</td>
                <td>{{ $checkpoint_start->city_name }} (Agen {{$checkpoint_start->agency_name}})</td>
                <td>{{ $departure_at }}</td>
                <td>{{ $checkpoint_destination->city_name }} (Agen {{$checkpoint_destination->agency_name}})</td>
                <td>{{ $arrived_at }}</td>
            </tr>
        </tbody>
    </table>

    <br>
    Dengan kode berikut: <h2>{{ $code_order }}</h2>

    <table class="border-black table-collapse">
        <thead>
            <tr>
                <th>No Kursi</th>
                <th>Nama</th>
                <th>Makan</th>
                <th>Travel</th>
                <th>Member</th>
            </tr>
        </thead>
        <tbody>
            <tr >
                <td class="border-black table-collapse">{{$seats}}</td>
                <td>{{ $order_detail->customer_name }}</td>
                <td>{{ $order_detail->is_feed }}</td>
                <td>{{ $order_detail->is_travel }}</td>
                <td>{{ $order_detail->id_member }}</td>
            </tr>
            <tr>
                <td colspan="4">Harga Tiket (Tanpa Makan)</td>
                <td>{{$distribution->ticket_only / $seats_count}}</td>
            </tr>
            <tr>
                <td colspan="4">Potongan Member</td>
                <td>{{$distribution->for_member}}</td>
            </tr>
            <tr>
                <td colspan="4">Harga Makan</td>
                <td>{{$distribution->for_food}}</td>
            </tr>
            <tr>
                <td colspan="4">Harga Travel</td>
                <td>{{$distribution->for_travel}}</td>
            </tr>
            <tr>
                <td colspan="4">Harga Total</td>
                <td>{{$total_price}}</td>
            </tr>
        </tbody>
    </table>

    <br>
    Terima kasih telah memesan melalui aplikasi kami semoga perjalanan anda menyenangkan dan selamat sampai tujuan!
</body>
</html>