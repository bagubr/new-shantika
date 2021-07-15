<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index()
    {
        $data['DEMAK'] = [
            'BUYARAN',
            'GAJAH',
            'PECINAN DEMAK',
            'TERMINAL DEMAK',
            'TRENGGULI',
            'WONOKERTO',
        ];
        $data['JEPARA'] = [
            'BANGSRI',
            'GARASI NGABUL',
            'GOTRI',
            'JL. PEMUDA JEPARA',
            'KALI GARANG',
            'KELET PASAR',
            'KELET TERMINAL',
            'KELING GLINGSEM',
            'KELING PERHUTANI',
            'KELING POLSEK',
            'KEMBANG',
            'KRASAK',
            'LEBAK',
            'MAYONG',
            'MLONGGO',
            'PECANGAAN',
            'PURWOGONDO',
            'SAMBUNG OYOT',
            'SEBAGOR',
            'SEKURO',
            'SENENAN',
            'SIRAHAN',
            'TANGGULASI',
            'TERMINAL JEPARA',
            'TPK', 
            'TUNGGUL',
            'WELAHAN',
        ];

        $data['KUDUS'] = [
            'DAREN',
            'DAREN FAIZ',
            'DAWE',
            'GARASI KALIWUNGU',
            'JETAK',
            'TERMINAL KUDUS',
            'UNDAAN',
        ];
        $data['PATI'] = [
            'BATANGAN',
            'CLUWAK',
            'JAKEN',
            'JUWONO',
            'NGABLAK',
            'NGEMPLAK RSI',
            'TAYU',
            'TERMINAL PATI',
            'TRANGKIL',
            'WEDARI',
        ];

        $data['REMBANG'] = [
            'KAYEN',
            'LASEM',
            'PANDANGAN',
            'REMBANG',
            'SUKOLILO',
        ];
        $data['SEMARANG'] = [
            'GENUK',
            'KALIBANTENG',
        ];
        foreach ($data as $key => $value) {
            // $test[] = $key;
            foreach ($value as $v) {
                $test[] = $v;
            }
        }
        return $test;
    }
}
