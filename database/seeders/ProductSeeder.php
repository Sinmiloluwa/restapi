<?php

namespace Database\Seeders;

use App\Models\Product;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create(Product::class);
        for($i = 1; $i <= 20 ; $i++)
        {
            DB::table('products')->insert([
            'name' => $faker->word,
            'description' => $faker->text(200),
            'price' => $faker->randomDigit,
            'created_at' => now(),
            'updated_at' => now()
            ]);
        }
    }
}
