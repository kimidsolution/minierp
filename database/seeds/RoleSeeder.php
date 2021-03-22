<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create(['name' => 'Super Admin']);
        Role::create(['name' => 'Ops Admin']);
        Role::create(['name' => 'Company Admin']);
        Role::create(['name' => 'Finance Manager']);
        Role::create(['name' => 'Finance Staff']);
    }
}
