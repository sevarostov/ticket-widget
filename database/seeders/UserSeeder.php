<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run(): void {

		foreach (['admin', 'manager'] as $role) {
			if (!Role::where('name', $role)->exists()) {
				Role::create(['name' => $role]);
			}
			$this->createUserByRole($role);
		}
	}

	/**
	 * Создаёт пользователя по роли
	 *
	 * @param string $role Роль пользователя (admin/manager)
	 *
	 * @return User Созданный пользователь
	 */
	private function createUserByRole(string $role): User {
		$user = null;
		if (!User::where('email', $role . '@example.com')->exists()) {
			$user = User::factory()
				->withPassword($role)
				->create([
					'name' => ucfirst($role) . ' User',
					'email' => $role . '@example.com',
				]);

			$user->assignRole($role);
		}


		return $user ?? User::where('email', $role . '@example.com')->first();
	}
}
