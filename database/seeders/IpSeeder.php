<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\IpDatatable;

class IpSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        for($i=0; $i<29; $i++){
            IpSeeder::create_row(193 + $i, 222);
        }

        for($i=0; $i<13; $i++){
            IpSeeder::create_row(241 + $i, 254);
        }
    }

    public static function create_row($i, $g)
    {
        $res = IpDatatable::create([
            'ip' => $i,
            'gateway' => $g,
        ]);
    }
}
