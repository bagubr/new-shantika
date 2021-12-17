<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CustomerMenu;
class CustomerMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            'Pesan Tiket',
            'Informasi Kelas Armada',
            'Informasi Perusahaan',
            'Merchandise',
            'Sosial Media',
            'Informasi Agen',
            'Membership Online',
            'Website Landing Page',
        ];

        foreach ($data as $key => $value) {
            CustomerMenu::create([
                'name' => $value,
                'icon' => '',
            ]);
        }
    }
}
