<?php

use App\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = array(
            ['first_name' => 'System', 'last_name' => 'Administrator', 'full_name' => 'System Administrator', 'username' => 'sysadmin', 'password' => bcrypt('sysadmin@111'), 'email' => 'akhi_192@yahoo.com', 'email_verified_at' => date('Y-m-d H:i:s'), 'role' => 'System Administrator', 'locale' =>'en', 'first_login' => null, 'owner' => 'sysadmin', 'last_updated_by' => 'sysadmin', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['first_name' => 'Akhilesh', 'last_name' => 'Darjee', 'full_name' => 'Akhilesh Darjee', 'username' => 'admin', 'password' => bcrypt('admin@111'), 'email' => 'akhileshdarjee@gmail.com', 'email_verified_at' => date('Y-m-d H:i:s'), 'role' => 'Administrator', 'locale' =>'en', 'first_login' => null, 'owner' => 'admin', 'last_updated_by' => 'admin', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')]
        );

        User::insert($users);

        // factory(App\User::class, 500)->create();
    }
}
