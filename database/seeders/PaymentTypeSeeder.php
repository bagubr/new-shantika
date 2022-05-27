<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentType;
class PaymentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            'XENDIT',
            'AGENT',
        ];
        foreach ($data as $key => $value) {
            PaymentType::create([
                'name'  => $value
            ]);
        }
    }
}
