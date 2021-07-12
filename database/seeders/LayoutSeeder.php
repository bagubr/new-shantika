<?php

namespace Database\Seeders;

use App\Models\Fleet;
use App\Models\Layout;
use App\Models\LayoutChair;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LayoutSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $fleet = Fleet::all();
        DB::beginTransaction();
        foreach($fleet as $i) {
            $layout = Layout::create([
                'name'=>'A-'.$i->name,
                'row'=>5,
                'col'=>7,
                'space_indexes'=>json_encode([2,7,12,17,22,27,32]),
                'toilet_indexes'=>json_encode([31]),
                'door_indexes'=>json_encode([30]),
                'note'=>'satriotol busuk'
            ]);
            foreach([0,1,3,4,5,6,8,9,10,11,13,14,15,16,18,19,20,21,23,24,25,26,28,29,33,34] as $index => $x) {
                LayoutChair::create([
                    'name'=>++$index,
                    'index'=>$x,
                    'layout_id'=>$layout->id
                ]);
            }
        }
        DB::commit();
    }
}
