<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Partner;
use Illuminate\Support\Str;

class PartnersSeeder extends Seeder
{
    public function run(): void
    {
        $partners = [
            [
                'name' => 'Tech Corp International',
                'description' => 'Leading technology solutions provider',
                'website_url' => 'https://techcorp.com',
                'contact_email' => 'partner@techcorp.com',
                'partnership_type' => 'corporate',
                'is_active' => true,
                'is_featured' => true
            ],
            [
                'name' => 'Digital Academy',
                'description' => 'Premier coding bootcamp and education center',
                'website_url' => 'https://digitalacademy.edu',
                'contact_email' => 'info@digitalacademy.edu',
                'partnership_type' => 'educational',
                'is_active' => true,
                'is_featured' => true
            ],
            [
                'name' => 'StartupHub',
                'description' => 'Innovative startup incubator',
                'website_url' => 'https://startuphub.io',
                'contact_email' => 'connect@startuphub.io',
                'partnership_type' => 'startup',
                'is_active' => true,
                'is_featured' => false
            ]
        ];

        foreach ($partners as $partner) {
            Partner::create($partner);
        }
    }
}
