<?php

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
			['full_name' => 'Administrator', 'avatar' => null, 'login_id' => 'admin', 'password' => bcrypt('admin@111'), 'email' => 'akhileshdarjee@gmail.com', 'role' => 'Administrator', 'owner' => 'admin', 'last_updated_by' => 'admin', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')]
		);

		DB::table('tabUser')->insert($users);
	}
}