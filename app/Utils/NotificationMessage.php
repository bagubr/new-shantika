<?php

namespace App\Utils;

class NotificationMessage {
    public static function successfullySendingTicket() {
        return ["Pembelian berhasil", "Tiket berhasil dikirimkan"];
    }

    public static function newSlider($title,$message) {
        return [$title,$message];
    }

    public static function newArticle($title, $body) {
        return [$title,  preg_replace( "/\n\s+/", "\n", rtrim(html_entity_decode(strip_tags($body))))];
    }

    public static function newTestimonial($title, $body) {
        return [$title, preg_replace( "/\n\s+/", "\n", rtrim(html_entity_decode(strip_tags($body))))];
    }

    public static function paymentWillExpired() {
        return ["Pembayaran anda akan segera berakhir", "Pembayaran anda akan berakhir dalam 30 menit lagi"];
    }

    public static function paymentExpired($datetime) {
        return ["Pembayaran telah kadaluarsa", "Pembayaran anda untuk keberangkatan jam ".$datetime." sudah kadaluarsa"];
    }

    public static function bookingExpired(array $chair) {
        $chair = implode(", ", $chair);
        return ["Booking Kursi Armada Kadaluarsa", "Booking kursi ".$chair." telah kadaluarsa"];
    }

    public static function paymentSuccess($str) {
        return ["Pembayaran diterima", "Pembayaran untuk kode ".$str." telah sukses"];
    }

    public static function paymentDeclined($str, $reason) {
        return ["Pembayaran anda ditolak", "Pembayaran untuk kode ".$str." telah ditolak dengan alasan ".$reason];
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