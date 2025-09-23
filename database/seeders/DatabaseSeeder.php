<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
<<<<<<< HEAD
        // User::factory(10)->create();

=======
        // Seed roles first
        $this->call(RoleSeeder::class);

        // Create admin account
        $adminUser = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@sneakerness.com',
            'password' => bcrypt('password'),
        ]);

        // Assign organisator role to admin
        $organisatorRole = \App\Models\Role::where('name', 'organisator')->first();
        if ($organisatorRole) {
            $adminUser->role_id = $organisatorRole->id;
            $adminUser->save();
        }

        // Create bezoeker account
        $bezoekerUser = User::factory()->create([
            'name' => 'Bezoeker User',
            'email' => 'bezoeker@sneakerness.com',
            'password' => bcrypt('password'),
        ]);

        // Assign bezoeker role
        $bezoekerRole = \App\Models\Role::where('name', 'bezoeker')->first();
        if ($bezoekerRole) {
            $bezoekerUser->role_id = $bezoekerRole->id;
            $bezoekerUser->save();
        }

        // Create test user
>>>>>>> dev
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
<<<<<<< HEAD
=======

        // Call event seeder
        $this->call(EventSeeder::class);
>>>>>>> dev
    }
}
