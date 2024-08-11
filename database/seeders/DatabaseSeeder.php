<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::factory()->create([
            'name' => 'Rania',
            'email' => 'rania@gmail.com',
        ]);

    //    $user2= User::factory()->create([
    //         'name' => 'user2',
    //         'email' => 'user2@gmail.com',
    //     ]);

       // $role = Role::findById(3);
        //->where(['name'=> 'subscriber']);
      //  dd($role);
        //$role2 = Role::create(['name'=> 'Editor']);
        // $user2->assignRole($role2);
        $user->assignRole(3);
    }
}
