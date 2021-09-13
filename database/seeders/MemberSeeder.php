<?php

namespace Database\Seeders;

use App\Models\Membership;
use Illuminate\Database\Seeder;

class MemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Membership::truncate();
        for ($i = 1; $i < 5020; $i++) {
            Membership::create([
                "name"      => "No Name",
                "address"   => "No Address",
                "phone"     => 0,
                "agency_id" => 1,
                "code_member" => $i
            ]);
        }
        $csvFile = fopen(base_path("database/data/csv/shantika.csv"), "r");
        $firstline = true;
        $i = 5020;
        while (($data = fgetcsv($csvFile, 2000, ";")) !== FALSE) {
            if (!$firstline) {
                Membership::create([
                    "name"      => $data['0'],
                    "address"   => $data['2'],
                    "phone"     => "+62" . $data['1'],
                    "agency_id" => 1,
                    "code_member" => $i++,
                ]);
            }
            $firstline = false;
        }
        fclose($csvFile);
    }
}
