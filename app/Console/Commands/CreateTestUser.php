<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Role;

class CreateTestUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:test-user {email} {name} {role}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a test user with specified role';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $name = $this->argument('name');
        $roleName = $this->argument('role');

        $role = Role::where('name', $roleName)->first();
        
        if (!$role) {
            $this->error("Role '{$roleName}' not found");
            return 1;
        }

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => bcrypt('password'),
            'role_id' => $role->id
        ]);

        $this->info("Created {$roleName} user: {$user->name} ({$user->email})");
        $this->info("Password: password");
        
        return 0;
    }
}
