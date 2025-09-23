<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\Ticket;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Sneakerness Rotterdam 2025 Event (future date)
        $event = Event::create([
            'name' => 'Sneakerness Rotterdam 2025',
            'description' => 'Join the ultimate sneaker event at Van Nellefabriek! A two-day celebration of sneakers, art, sport, fashion, and music where the sneaker community comes together.',
            'location' => 'Van Nellefabriek, Rotterdam',
            'start_date' => '2025-11-15',
            'end_date' => '2025-11-16',
            'start_time' => '11:00',
            'end_time' => '18:00',
            'is_active' => true,
            'status' => 'upcoming',
            'base_price' => 15.00,
            'image_url' => '/images/sneakerness-rotterdam.jpg',
            'additional_info' => [
                'website' => 'https://sneakerness.com',
                'contact_email' => 'info@sneakerness.com',
                'parking' => 'Available on-site',
                'accessibility' => 'Wheelchair accessible'
            ]
        ]);

        // Create ticket types based on the provided admission schedule
        $tickets = [
            // Saturday tickets
            [
                'name' => 'Zaterdag Early Access',
                'description' => 'Early entry from 11:00 - Be first to get the best sneakers!',
                'day' => 'saturday',
                'admission_time' => '11:00',
                'price' => 50.00,
                'total_quantity' => 100,
                'features' => ['Early access', 'Best selection', 'VIP treatment']
            ],
            [
                'name' => 'Zaterdag Middag',
                'description' => 'Entry from 12:00',
                'day' => 'saturday',
                'admission_time' => '12:00',
                'price' => 15.00,
                'total_quantity' => 500,
                'features' => ['Full day access']
            ],
            [
                'name' => 'Zaterdag Namiddag',
                'description' => 'Entry from 14:00',
                'day' => 'saturday',
                'admission_time' => '14:00',
                'price' => 12.00,
                'total_quantity' => 400,
                'features' => ['Afternoon access']
            ],
            [
                'name' => 'Zaterdag Avond',
                'description' => 'Entry from 16:00',
                'day' => 'saturday',
                'admission_time' => '16:00',
                'price' => 11.00,
                'total_quantity' => 300,
                'features' => ['Evening access']
            ],
            // Sunday tickets
            [
                'name' => 'Zondag Middag',
                'description' => 'Entry from 12:00',
                'day' => 'sunday',
                'admission_time' => '12:00',
                'price' => 14.00,
                'total_quantity' => 450,
                'features' => ['Full day access']
            ],
            [
                'name' => 'Zondag Namiddag',
                'description' => 'Entry from 14:00',
                'day' => 'sunday',
                'admission_time' => '14:00',
                'price' => 12.00,
                'total_quantity' => 350,
                'features' => ['Afternoon access']
            ],
            [
                'name' => 'Zondag Avond',
                'description' => 'Entry from 16:00',
                'day' => 'sunday',
                'admission_time' => '16:00',
                'price' => 10.00,
                'total_quantity' => 250,
                'features' => ['Evening access', 'Last chance deals']
            ]
        ];

        foreach ($tickets as $ticketData) {
            $ticketData['event_id'] = $event->id;
            Ticket::create($ticketData);
        }
    }
}
