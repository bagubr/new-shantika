<?php

namespace App\Utils;

class CodeMember {
    public static function code($code) {
        $code_member = str_replace('SNTK', '', $code);
        $code_member = (int) ltrim($code_member, '0');
        return $code_member;
    }   
}