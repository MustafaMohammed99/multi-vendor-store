<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models;
use App\Models\Category;
use App\Models\Product;
use App\Models\Role;
use App\Models\Store;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Admin::factory(5)->create();

        Role::create([
            'name' => 'user',
        ]);
        Role::create([
            'name' => 'admin',
        ]);
        Store::factory(5)->create();
        User::factory(2)->create();

        Category::factory(7)->create();
        Product::factory(10)->create();
    }
}
