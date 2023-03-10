<?php

namespace App\Utils;

class NotificationMessage {
    public static function successfullySendingTicket() {
        return ["Pembelian berhasil", "Pesan tiket telah berhasil, segera lakukan pembayaran"];
    }

    public static function newTicketOrder($order) {
        return [
            "Pembelian Tiket",
            "Pemesan baru pada armada ".$order->fleet_route?->fleet_detail?->fleet?->name." untuk tanggal ".date('d-m-Y', strtotime($order->reserve_at))
        ];
    }

    public static function newSlider($title,$message) {
        return [$title,$message];
    }

    public static function newArticle($title, $body) {
        $body = preg_replace( "/\n\s+/", "\n", rtrim(html_entity_decode(strip_tags($body))));
        return [$title,  "Yuk, baca artikel $body"];
    }

    public static function newTestimonial($title, $body) {
        $body = preg_replace( "/\n\s+/", "\n", rtrim(html_entity_decode(strip_tags($body))));
        return [$title, "Baca ulasan baru dari pelanggan New Shantika"];
    }

    public static function paymentWillExpired() {
        return ["Pembayaran anda akan segera berakhir", "Tagihan pembelian tiket Anda akan berakhir, segera lakukan pembayaran"];
    }

    public static function paymentExpired($datetime) {
        return ["Tagihan telah kadaluwarsa", "Tagihan Anda untuk keberangkatan tanggal ".$datetime." sudah kadaluwarsa"];
    }

    public static function OrderExpired($datetime) {
        return ["Order telah kadaluwarsa", "Pesanan Anda untuk keberangkatan tanggal ".$datetime." sudah kadaluwarsa"];
    }

    public static function bookingExpired(array $chair, $fleet_name = null) {
        $chair = implode(", ", $chair);
        $fleet_name = $fleet_name ?? 'Armada';
        return ["Booking kursi armada ".$fleet_name." kadaluwarsa", "Masa booking kursi ".$chair." telah kadaluwarsa"];
    }

    public static function paymentSuccess() {
        return ["Transaksi diterima", "Pembayaran transaksi Anda telah sukses"];
    }

    public static function paymentDeclined($str, $reason) {
        return ["Transaksi anda ditolak", "Pembayaran untuk kode ".$str." telah ditolak dengan alasan ".$reason];
    }

    public static function changeChair($fleet, $chair, $date) {
        return [
            "Perhatian! Kursi Anda telah dipindah!",
            "Nomor kursi Anda diubah menjadi ".$chair. " pada armada ".$fleet. " pada tanggal ".$date
        ];
    }

    public static function scheduleChanged($fleet, $datetime) {
        return [
            "Perhatian! Jadwal keberangkatan Anda diubah",
            "Jadwal keberangkatan di armada ".$fleet." Anda berhasil diubah"
        ];
    }

    public static function orderCanceled($fleet_name, $reason) {
        return [
            "Perhatian! Tiket anda telah kami batalkan",
            "Tiket anda untuk armada ".$fleet_name." telah kami batalkan dengan alasan berikut: ".$reason
        ];
    }

    public static function activateAccount($name) {
        $name = self::getFirstName($name);
        return ["Akun anda diaktifkan", "Yey, akun anda telah diaktifkan kembali"];
    }

    public static function deactivateAccount($name, $reason = null) {
        $name = self::getFirstName($name);
        return ["Akun anda dinonaktifkan", "Maaf, akun anda telah kami nonaktifkan sementara karena ".($reason ? $reason : "beberapa alasan")];
    }

    private static function getFirstName($name) {
        $split = explode(" ", $name);
        if(strlen($split[0]) > 10) {
            return substr($name, 9);
        }
        return $split[0];
    }

    public static function timeChange($data)
    {
        return ['Perhatian! Perubahan rute keberangkatan', 'Maaf Perjalanan anda telah berganti menjadi keberangkatan '. $data->time_classification->name];
    }

    public static function promo($data)
    {
        if($data->is_public){
            return ['Ada promo baru nih untuk kamu pelanggan setia New Shantika', 'Buruan cek sebelum kehabisan Syarat dan Ketentuan Berlaku'];
        }
        return [
            'Hai '.$data->user->name.' ada promo baru nih spesial buat kamu', 'Buruan cek sebelum kehabisan Syarat dan Ketentuan Berlaku'
        ];
    }
}