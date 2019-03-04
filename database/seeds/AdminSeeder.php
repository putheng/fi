<?php

class AdminSeeder extends DatabaseSeeder {

	public function run()
	{
		//Dangerous... Commented
		/*
		DB::table('users')->where('email', '=', 'admin@admin.com')->delete();
        DB::table('roles')->delete();
		DB::table('role_users')->delete();
		DB::table('activations')->delete();

		$admin = Sentinel::registerAndActivate(array(
			'email'       => 'admin@admin.com',
			'password'    => "admin",
			'first_name'  => 'FHI',
			'last_name'   => 'Admin',
		));

		$adminRole = Sentinel::getRoleRepository()->createModel()->create([
			'name' => 'Admin',
			'slug' => 'admin',
			'permissions' => array('admin' => 1),
		]);

		Sentinel::getRoleRepository()->createModel()->create([
			'name'  => 'User',
			'slug'  => 'user',
		]);

		$admin->roles()->attach($adminRole);

		$this->command->info('Admin User created with username admin@admin.com and password admin');
		*/
	}

}