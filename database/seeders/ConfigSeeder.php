<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ConfigSeeder extends Seeder
{
    public function run()
    {
        DB::table('configs')->insert([
            'id' => 1,
            'theme' => 'vuz2',
            'orientation' => 'horizontal',
            'activated' => 'n',
            'title' => 'НГУ им. П. Ф. Лесгафта, Санкт-Петербург',
            'logo' => '/img/vuz2/logo.png',
        ]);
    }
}