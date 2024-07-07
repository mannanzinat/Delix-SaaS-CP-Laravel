<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\Branch;
use App\Models\RoleUser;
use App\Enums\UserTypeEnum;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Cartalyst\Sentinel\Laravel\Facades\Activation;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('en_BD');

        $sasAdminRole           = Role::find(1);
        $courierAdminRole       = Role::find(2);

        $roleSeeder = new RoleSeeder();
        // // Start superadmin
        $sas_admin = User::create([
            'first_name'        => 'SAS',
            'last_name'         => 'Admin',
            'email'             => 'sas_admin@admin.com',
            'permissions'       => $roleSeeder->superAdminPermissions(),
            'password'          => bcrypt(123456),
            'user_type'         => UserTypeEnum::SAS_ADMIN,
        ]);

        $courier_admin = User::create([
            'first_name'        => 'Courier',
            'last_name'         => 'Admin',
            'dashboard'         => 'admin',
            'email'             => 'courier_admin@admin.com',
            'permissions'       => $roleSeeder->adminPermissions(),
            'password'          => bcrypt(123456),
            'user_type'         => UserTypeEnum::COURIER_ADMIN,
        ]);


        $activation     = Activation::create($sas_admin);
        Activation::complete($sas_admin, $activation->code);
        $sasAdminRole->users()->attach($sas_admin);
        $pass_has       =  bcrypt(123456);

        $activation     = Activation::create($courier_admin);
        Activation::complete($courier_admin, $activation->code);
        $courierAdminRole->users()->attach($courier_admin);
        $pass_has       =  bcrypt(123456);

    }


}
