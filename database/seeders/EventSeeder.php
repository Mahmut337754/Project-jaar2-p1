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

        // Create 5 additional events
        
        // Event 2: Sneaker Drop Amsterdam
        $event2 = Event::create([
            'name' => 'Sneaker Drop Amsterdam',
            'description' => 'Exclusieve sneaker release event in het hart van Amsterdam. Kom langs voor de nieuwste limited edition releases van top merken.',
            'location' => 'Amsterdam RAI, Amsterdam',
            'start_date' => '2025-12-05',
            'end_date' => '2025-12-05',
            'start_time' => '18:00',
            'end_time' => '22:00',
            'is_active' => true,
            'status' => 'upcoming',
            'base_price' => 25.00,
            'image_url' => '/images/sneaker-drop-amsterdam.jpg',
            'additional_info' => [
                'website' => 'https://sneakerdrop.nl',
                'contact_email' => 'info@sneakerdrop.nl',
                'parking' => 'Paid parking available',
                'accessibility' => 'Fully accessible'
            ]
        ]);

        // Event 3: Retro Sneaker Meet Utrecht
        $event3 = Event::create([
            'name' => 'Retro Sneaker Meet Utrecht',
            'description' => 'Een bijeenkomst voor vintage en retro sneaker liefhebbers. Koop, verkoop en ruil je favoriete klassiekers.',
            'location' => 'TivoliVredenburg, Utrecht',
            'start_date' => '2025-11-28',
            'end_date' => '2025-11-28',
            'start_time' => '14:00',
            'end_time' => '18:00',
            'is_active' => true,
            'status' => 'upcoming',
            'base_price' => 18.00,
            'image_url' => '/images/retro-sneaker-meet.jpg',
            'additional_info' => [
                'website' => 'https://retrosneakermeet.nl',
                'contact_email' => 'hello@retrosneakermeet.nl',
                'parking' => 'Street parking',
                'accessibility' => 'Limited accessibility'
            ]
        ]);

        // Event 4: Sneaker Expo Den Haag
        $event4 = Event::create([
            'name' => 'Sneaker Expo Den Haag',
            'description' => 'De grootste sneaker expo van Nederland! Ontdek nieuwe trends, ontmoet designers en vind je perfecte paar.',
            'location' => 'World Forum, Den Haag',
            'start_date' => '2025-12-15',
            'end_date' => '2025-12-16',
            'start_time' => '10:00',
            'end_time' => '17:00',
            'is_active' => true,
            'status' => 'upcoming',
            'base_price' => 22.00,
            'image_url' => '/images/sneaker-expo-denhaag.jpg',
            'additional_info' => [
                'website' => 'https://sneakerexpo.nl',
                'contact_email' => 'contact@sneakerexpo.nl',
                'parking' => 'Free parking available',
                'accessibility' => 'Wheelchair accessible'
            ]
        ]);

        // Event 5: Streetwear & Sneakers Festival
        $event5 = Event::create([
            'name' => 'Streetwear & Sneakers Festival',
            'description' => 'Een festival dat streetwear en sneaker cultuur samenbrengt met live muziek, food trucks en exclusive drops.',
            'location' => 'Ziggo Dome, Amsterdam',
            'start_date' => '2025-12-22',
            'end_date' => '2025-12-22',
            'start_time' => '16:00',
            'end_time' => '23:00',
            'is_active' => true,
            'status' => 'upcoming',
            'base_price' => 35.00,
            'image_url' => '/images/streetwear-festival.jpg',
            'additional_info' => [
                'website' => 'https://streetwearfestival.nl',
                'contact_email' => 'info@streetwearfestival.nl',
                'parking' => 'Paid parking adjacent',
                'accessibility' => 'Full accessibility'
            ]
        ]);

        // Event 6: Sneaker Swap Meet Eindhoven
        $event6 = Event::create([
            'name' => 'Sneaker Swap Meet Eindhoven',
            'description' => 'Ruil, verkoop en koop sneakers in een relaxte sfeer. Perfect voor verzamelaars en liefhebbers.',
            'location' => 'Klokgebouw, Eindhoven',
            'start_date' => '2025-11-30',
            'end_date' => '2025-11-30',
            'start_time' => '13:00',
            'end_time' => '17:00',
            'is_active' => true,
            'status' => 'upcoming',
            'base_price' => 12.00,
            'image_url' => '/images/sneaker-swap-eindhoven.jpg',
            'additional_info' => [
                'website' => 'https://sneakerswap.nl',
                'contact_email' => 'swap@sneakerswap.nl',
                'parking' => 'Free parking',
                'accessibility' => 'Accessible venue'
            ]
        ]);
    }
}
