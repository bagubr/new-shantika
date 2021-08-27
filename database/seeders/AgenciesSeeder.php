<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
use App\Models\User;
use App\Models\Agency;
use App\Models\AgencyDepartureTime;
use App\Models\UserAgent;
use App\Models\City;
use App\Models\Province;

class AgenciesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');
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
        $data2['BEKASI'] = [
            'JATI ASIH',
            'CIKARANG',
            'BEKASI',
            'CIBITUNG',
            'SUMMARECON'
        ];
        $data2['BOGOR'] = [
            'TERMINAL BOGOR',
            'PARUNG',
            'CIBINONG',
            'GUNUNG PUTRI',
            'CILEUNGSI',
            'BUBULAK',
            'CILENGSI',
            'PRUMPUNG',
            'JALAN BARU  BOGOR',
            'PUL CIAWI',
            'TERMINAL BARANA SIANG',
            'KEDUNG HALANG',
            'POMAD',
            'CIKARET',
            'SIMPANG CIBINONG',
            'CENTRAL BOGOR'
        ];
        $data2['CILEGON'] = [
            'PELABUHAN MERAK',
            'TERMINAL SERUNI',
            'TERMINAL SRUNI CILEGON',
            'TOL CIPERNA',
            'TOL PLUMBON / KANCI'
        ];
        $data2['DEPOK'] = [
            'DEPOK',
            'WARUNG JAMBU',
            'TERMINAL JATI JAJAR'
        ];
        $data3['JAKARTA BARAT'] = [
            'KALIDERES'
        ];
        $data3['JAKARTA SELATAN'] = [
            'LEBAK BULUS',
            'PASAR JUMAT'
        ];
        $data3['JAKARTA TIMUR'] = [
            'PULOGEBANG',
            'TERMINAL KP. RAMBUTAN',
            'RAWA MANGUN',
            'PASAR INDUK'
        ];
        $data2['KARAWANG'] = [
            'KLARI',
            'KARAWANG BARAT'
        ];
        $data4['SERANG'] = [
            'SERANG',
            'TAMAN KOPASUS',
            'SERANG PAKUPATAN',
            'CIRUAS',
            'CIUJUNG',
            'GORDA CIKANDE'
        ];
        $data2['TANGERANG'] = [
            'CILEDUG (MERUYA)',
            'BSD',
            'BITUNG',
            'DOYONG',
            'MUNCUL',
            'AGEN PORIS',
            'CILEDUK',
            'PASAR KEMIS',
            'BALA RAJA BARAT',
            'BALA RAJA TIMUR'
        ];
        $data3['JAKARTA UTARA'] = [
            'TANJUNG PRIOK'
        ];
        $data2['SUBANG'] = [
            'KALIJATI'
        ];
        $data2['BANDUNG'] = [
            'BANDUNG,',
            'CIMAHI',
            'CICAHEUM',
            'CIMINDI',
            'EXIT TOL PADJAJARAN',
            'PADJAJARAN',
            'DAGO',
            'CIBIRU',
            'CILEUNYI',
            'JATINANGOR',
            'TERM CIAKAR SUMEDANG',
            'ALAM SARI SUMEDANG',
            'KERTA JATI'
        ];


        $province1 = Province::create([
            'name' => 'JAWA TENGAH',
        ]);
        $province2 = Province::create([
            'name' => 'JAWA BARAT',
        ]);
        $province3 = Province::create([
            'name' => 'DKI JAKARTA',
        ]);
        $province4 = Province::create([
            'name' => 'BANTEN',
        ]);
        $vowels = array("a", "e", "i", "o", "u", "A", "E", "I", "O", "U", " ");
        foreach ($data4 as $key => $value) {
            $city = City::create([
                'name' => $key,
                'province_id' => $province4->id,
                'area_id' => 2
            ]);
            foreach ($value as $k => $v) {
                $agent = Agency::create([
                    'name'      => $v,
                    'code'      => str_replace($vowels, "", $v),
                    'city_id'   => $city->id,
                    'lat'       => $faker->latitude,
                    'lng'       => $faker->longitude,
                    'address'   => $faker->address,
                    'code'      => str_replace(['a', 'i', 'u', 'e', 'o', 'A', 'I', 'U', 'E', 'O'], "", $v)
                ]);
                AgencyDepartureTime::create([
                    'agency_id' => $agent->id,
                    'departure_at' => $faker->numberBetween(10, 17) . ":00:00"
                ]);
                AgencyDepartureTime::create([
                    'agency_id' => $agent->id,
                    'departure_at' => $faker->numberBetween(14, 17) . ":00:00"
                ]);
            }
        }
        foreach ($data3 as $key => $value) {
            $city = City::create([
                'name' => $key,
                'province_id' => $province3->id,
                'area_id' => 2
            ]);
            foreach ($value as $k => $v) {
                $agent = Agency::create([
                    'name'      => $v,
                    'code'      => str_replace($vowels, "", $v),
                    'city_id'   => $city->id,
                    'lat'       => $faker->latitude,
                    'lng'       => $faker->longitude,
                    'address'   => $faker->address,
                    'code'      => str_replace(['a', 'i', 'u', 'e', 'o', 'A', 'I', 'U', 'E', 'O'], "", $v)
                ]);
                AgencyDepartureTime::create([
                    'agency_id' => $agent->id,
                    'departure_at' => $faker->numberBetween(10, 17) . ":00:00"
                ]);
                AgencyDepartureTime::create([
                    'agency_id' => $agent->id,
                    'departure_at' => $faker->numberBetween(14, 17) . ":00:00"
                ]);
            }
        }
        foreach ($data2 as $key => $value) {
            $city = City::create([
                'name' => $key,
                'province_id' => $province2->id,
                'area_id' => 2
            ]);
            foreach ($value as $k => $v) {
                $agent = Agency::create([
                    'name'      => $v,
                    'code'      => str_replace($vowels, "", $v),
                    'city_id'   => $city->id,
                    'lat'       => $faker->latitude,
                    'lng'       => $faker->longitude,
                    'address'   => $faker->address,
                    'code'      => str_replace(['a', 'i', 'u', 'e', 'o', 'A', 'I', 'U', 'E', 'O'], "", $v)
                ]);
                AgencyDepartureTime::create([
                    'agency_id' => $agent->id,
                    'departure_at' => $faker->numberBetween(10, 17) . ":00:00"
                ]);
                AgencyDepartureTime::create([
                    'agency_id' => $agent->id,
                    'departure_at' => $faker->numberBetween(14, 17) . ":00:00"
                ]);
            }
        }
        foreach ($data as $key => $value) {
            $city = City::create([
                'name' => $key,
                'province_id' => $province1->id,
                'area_id' => 1
            ]);
            foreach ($value as $k => $v) {
                $agent = Agency::create([
                    'name'      => $v,
                    'code'      => str_replace($vowels, "", $v),
                    'city_id'   => $city->id,
                    'lat'       => $faker->latitude,
                    'lng'       => $faker->longitude,
                    'address'   => $faker->address,
                    'code'      => str_replace(['a', 'i', 'u', 'e', 'o', 'A', 'I', 'U', 'E', 'O'], "", $v)
                ]);
                AgencyDepartureTime::create([
                    'agency_id' => $agent->id,
                    'departure_at' => $faker->numberBetween(10, 17) . ":00:00"
                ]);
                AgencyDepartureTime::create([
                    'agency_id' => $agent->id,
                    'departure_at' => $faker->numberBetween(14, 17) . ":00:00"
                ]);
            }
        }
    }
}
