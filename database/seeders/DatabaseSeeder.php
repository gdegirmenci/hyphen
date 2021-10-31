<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Categories
        DB::table('categories')->insert(['id' => 1, 'name' => 'Tools']);
        DB::table('categories')->insert(['id' => 2, 'name' => 'Switches']);

        // Products
        DB::table('products')->insert(['id' => 'B102', 'price' => '4.99', 'category_id' => 1]);
        DB::table('products')->insert(['id' => 'A101', 'price' => '9.75', 'category_id' => 2]);
        DB::table('products')->insert(['id' => 'A102', 'price' => '49.50', 'category_id' => 2]);
    }
}
