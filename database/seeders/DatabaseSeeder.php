<?php

namespace Database\Seeders;

use App\Models\Employment;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        Role::create(['name' => 'staff']);
        Role::create(['name' => 'warga']);
        Role::create(['name' => 'rt']);
        Role::create(['name' => 'lurah']);

        User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('123')
        ])->assignRole('staff');

        $this->call([
            AdministrativeAreaSeeder::class,
            ReligionSeeder::class,
            BloodGroupSeeder::class,
            EmploymentSeeder::class,
            EducationSeeder::class
        ]);


    }
}
