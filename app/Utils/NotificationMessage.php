<?php

namespace App\Utils;

class NotificationMessage {
    public static function successfullySendingTicket() {
        return ["Pembelian berhasil", "Tiket berhasil dikirimkan"];
    }

    public static function newSlider($title,$message) {
        return [$title,$message];
    }

    public static function newArticle($title) {
        return $title;
    }

    public static function newTestimonial($title) {
        return $title;
    }

    public static function paymentWillExpired($min) {
        return "Pembayaran anda akan berakhir dalam ".$min;
    }

    public static function paymentExpired($datetime) {
        return "Pembayaran anda untuk keberangkatan jam ".$datetime." sudah kadaluarsa";
    }

    public static function bookingExpired(string|array $chair) {
        return "Booking kursi ".$chair." telah kadaluarsa";
    }

    public static function paymentSuccess($datetime) {
        return "Pembayaran untuk kode ".$datetime." telah sukses";
    }

    public static function paymentDeclined($datetime, $reason) {
        return "Pembayaran untuk kode ".$datetime." telah ditolak dengan alasan ".$reason;
    }

    public static function changeChair($fleet, $chair) {
        return "Kursi anda di armada ".$fleet." telah kami ganti menjadi ".$chair.". Kami mohon maaf atas ketidaknyamanan ini";
    }

    public static function activateAccount($name) {
        $name = self::getFirstName($name);
        return "Halo, ".$name.", akun anda telah diaktifkan";
    }

    public static function deactivateAccount($name, $reason = null) {
        $name = self::getFirstName($name);
        return "Halo, ".$name.", akun anda telah kami nonaktifkan sementara karena ".($reason ? $reason : "beberapa alasan");
    }

    private static function getFirstName($name) {
        $split = explode(" ", $name);
        if(strlen($split[0]) > 10) {
            return substr($name, 9);
        }
        return $split[0];
    }
}