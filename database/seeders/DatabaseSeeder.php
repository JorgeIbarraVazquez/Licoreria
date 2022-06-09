<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $arrays = range(1,5);
        foreach ($arrays as $valor) {
        DB::table('proveedors')->insert([
            'nombre_empresa' => Str::random(10),
            'rfc' => Str::random(12),
            'telefono' => rand(0000000,9999999),
            'domicilio' => Str::random(12),
        ]);
        }

        $faker = \Faker\Factory::create();
        $provedoresIDs = DB::table('proveedors')->pluck('id');
        $arraysP = range(1,10);
        foreach ($arraysP as $valor) {
        DB::table('productos')->insert([
            'descripcion' => Str::random(30),
            'cantidad' => rand(0,100),
            'precio' => rand(0,50),
            'id_proveedor' => $faker->randomElement($provedoresIDs),
        ]);
        }
    }
}
