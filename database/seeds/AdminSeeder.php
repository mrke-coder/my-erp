<?php

use App\Models\Role;
use App\Models\UserRole;
use App\User;
use Illuminate\Database\Seeder;
class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'email' => 'mrke@hightech-ci.com',
            'password' => Hash::make('admin@4358'),
            'confirmed' => true,
            'avatar' => 'http://127.0.0.1:8000/uploads/avatar/user.png'
        ]);

        $role = Role::create([
            'role' => 'administrator'
        ]);

        if ($user) {
            UserRole::create([
                'user_id' => $user->id,
                'role_id' => $role->id
            ]);
        }
    }
}
