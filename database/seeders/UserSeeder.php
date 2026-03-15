<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		foreach (['admin', 'manager', 'hr'] as $roleName) {

			$role = $this->createRoleByName($roleName);
			$this->createPermissionsByRole($role);
			$this->createUserByRole($role);
		}
		$this->assignAdminRoles();
	}

	/**
	 * @param string $roleName
	 *
	 * @return Role
	 */
	private function createRoleByName(string $roleName): Role
	{
		$role = Role::where('name', $roleName)->first();

		if (!Role::where('name', $roleName)->exists()) {
			$role = Role::create(['name' => $roleName]);
		}

		return $role;
	}

	/**
	 * @param Role $role
	 *
	 * @return void
	 */
	private function createPermissionsByRole(Role $role): void
	{
		$objects = match ($role->name) {
			'admin' => ['ticket.updateStatus', 'ticket', 'resume'],
			'hr' => ['resume'],
			default => ['ticket'],
		};
		$permissions = [];
		foreach ($objects as $object) {
			$permissionName = $object;
			$permission = Permission::where('name', $permissionName)->first();
			if (!$permission) {
				$permission = Permission::create([
					'name' => $permissionName,
				]);
			}
			$permissions[] = $permission;
		}
		$role->givePermissionTo($permissions);
	}


	/**
	 * Создаёт пользователя по роли
	 *
	 * @param Role $role Роль пользователя (admin/manager/hr)
	 *
	 * @return void Созданный пользователь
	 */
	private function createUserByRole(Role $role): void
	{
		$user = User::where('email', $role->name . '@example.com')->first();

		if (!$user) {
			$user = User::factory()
				->withPassword($role->name . "_pwd")
				->create([
					'name' => ucfirst($role->name) . ' User',
					'email' => $role->name . '@example.com',
				]);

			$user->assignRole($role);
		}
	}

	/**
	 *
	 * @return void
	 */
	private function assignAdminRoles(): void
	{
		$admin = User::where('email', 'admin@example.com')->first();

		foreach (['manager', 'hr'] as $roleName) {
			$role = Role::where('name', $roleName)->first();
			if (!$admin->hasRole($role)) {
				$admin->assignRole($role);
			}
		}
	}
}
