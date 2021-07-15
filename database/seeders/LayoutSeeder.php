<?php

namespace Database\Seeders;

use App\Models\Fleet;
use App\Models\Layout;
use App\Models\LayoutChair;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
class LayoutSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');
        $array = array (0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25);
        for ($i=0; $i < 2; $i++) { 
            $layout = Layout::create([
                'name'          =>$faker->sentence($nbWords = 1, $variableNbWords = true),
                'row'           =>$faker->randomElement($array = array (5, 4)),
                'col'           =>$faker->randomElement($array = array (8, 9, 7)),
                'space_indexes' =>json_encode($faker->randomElements($array, $count = 3)),
                'toilet_indexes'=>json_encode($faker->randomElements($array, $count = 1)),
                'door_indexes'  =>json_encode($faker->randomElements($array, $count = 1)),
                'note'          =>$faker->sentence($nbWords = 6, $variableNbWords = true)
            ]);
            $count = $layout->col * $layout->row;
            $name_chair = 0;
            for ($i=0; $i < $count; $i++) { 
                if(in_array($i, json_decode($layout->space_indexes))){
                    LayoutChair::create([
                        'name'      =>'Space',
                        'index'     =>$i,
                        'layout_id' =>$layout->id,
                    ]);
                }elseif(in_array($i, json_decode($layout->toilet_indexes))){
                    LayoutChair::create([
                        'name'      =>'Toilet',
                        'index'     =>$i,
                        'layout_id' =>$layout->id,
                    ]);
                }elseif(in_array($i, json_decode($layout->door_indexes))){
                    LayoutChair::create([
                        'name'      =>'Pintu',
                        'index'     =>$i,
                        'layout_id' =>$layout->id,
                    ]);
                }else{
                    $name_chair +=  1;
                    LayoutChair::create([
                        'name'      =>$name_chair,
                        'index'     =>$i,
                        'layout_id' =>$layout->id,
                    ]);
                }
            }
        }
    }
}
