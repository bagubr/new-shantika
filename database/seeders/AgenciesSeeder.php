<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
use App\Models\User;
use App\Models\Agency;
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
        
        $province = Province::create([
            'name' => 'JAWA TENGAH',
        ]);
        foreach ($data as $key => $value) {
            $city = City::create([
                'name' => $key,
                'province_id' => $province->id,
            ]);
            foreach ($value as $k => $v) {
                $agent = Agency::create([
                    'name'      => $v,
                    'city_id'   => $city->id,
                ]);
                $user = User::create([
                    'name'  => $faker->name,
                    'phone' => $faker->e164PhoneNumber,
                    'email' => $faker->email,
                    'avatar' => '',
                    'token' => $faker->regexify('[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}'),
                    'fcm_token' => $faker->sha256,
                    'birth' => $faker->date($format = 'Y-m-d', $max = 'now'),
                    'gender' => $faker->randomElement($array = array ('Male', 'Female')),
                    'address' => $faker->address,
                ]);
                UserAgent::create([
                    'user_id'    => $user->id,
                    'agency_id'   => $agent->id,
                ]);
            }
        }
    }
}
