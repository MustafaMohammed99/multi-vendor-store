<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models;
use App\Models\Admin;
use App\Models\Category;
use App\Models\Product;
use App\Models\Role;
use App\Models\RoleAbility;
use App\Models\Store;
use App\Models\User;
use Illuminate\Database\Seeder;
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
        // \App\Models\Admin::factory(5)->create();

        Admin::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'username' => 'admin',
            'password' => Hash::make('password'),
            'phone_number' => '0593270000',
            'super_admin' => 1,
        ]);

        $role_user = Role::create([
            'name' => 'user',
        ]);
        $role_admin = Role::create([
            'name' => 'admin',
        ]);

        foreach (app('abilities') as $ability => $value) {
            $type = 'deny';
            if (strpos($ability, 'products.') === 0) {
                $type = 'allow';
            }
            RoleAbility::create([
                'role_id' => $role_user->id,
                'ability' => $ability,
                'type' => $type,
            ]);
            RoleAbility::create([
                'role_id' => $role_admin->id,
                'ability' => $ability,
                'type' => 'allow',
            ]);
        }



        Store::factory(5)->create();
        // User::factory(2)->create();

        User::create([
            'name'                  => 'user',
            'email'                 => 'user@gmail.com',
            'email_verified_at'     => now(),
            'password'              => Hash::make('password'),
            'remember_token'        => Str::random(10),
            'store_id' => 1,

        ]);
        Category::factory(5)->create();
        Product::factory(10)->create();
    }
}
