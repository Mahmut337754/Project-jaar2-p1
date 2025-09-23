<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'bezoeker',
                'display_name' => 'Bezoeker',
                'description' => 'Visitor who can buy tickets',
                'permissions' => [
                    'view_events',
                    'buy_tickets',
                    'view_own_tickets'
                ]
            ],
            [
                'name' => 'verkoper',
                'display_name' => 'Verkoper',
                'description' => 'Vendor who can rent stands',
                'permissions' => [
                    'view_events',
                    'rent_stands',
                    'manage_own_stands',
                    'view_stand_bookings',
                    'upload_logo'
                ]
            ],
            [
                'name' => 'organisator',
                'display_name' => 'Organisator',
                'description' => 'Event organizer with full access',
                'permissions' => [
                    'manage_events',
                    'manage_users',
                    'manage_roles',
                    'manage_stands',
                    'manage_tickets',
                    'view_all_bookings',
                    'manage_vendors',
                    'manage_contact_persons',
                    'view_reports'
                ]
            ]
        ];

        foreach ($roles as $roleData) {
            Role::create($roleData);
        }
    }
}
