<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\User;
use Illuminate\Support\Str;
use Carbon\Carbon;

class EventsSeeder extends Seeder
{
    public function run(): void
    {
        // First ensure we have at least one user for the organizer
        $organizer = User::first() ?? User::factory()->create();

// $table->string('title');
// $table->string('slug')->unique();
// $table->text('description')->nullable();
// $table->longText('content')->nullable();
// $table->string('featured_image')->nullable();
// $table->enum('event_type', ['bootcamp', 'workshop', 'seminar', 'mentoring', 'meetup']);
// $table->datetime('start_date');
// $table->datetime('end_date');
// $table->string('location')->nullable();
// $table->boolean('is_online')->default(false);
// $table->string('meeting_link')->nullable();
// $table->datetime('registration_deadline')->nullable();

        $events = [
            [
                'title' => 'Web Development Bootcamp 2025',
                'description' => 'Intensive 12-week web development bootcamp covering full-stack development',
                'content' => 'Learn modern web development from industry experts. Topics include HTML, CSS, JavaScript, React, Node.js, and more.',
                'event_type' => 'bootcamp',
                'start_date' => Carbon::now()->addDays(30),
                'end_date' => Carbon::now()->addDays(114), // 12 weeks
                'location' => 'Bangkok Tech Hub',
                'is_online' => false,
                'registration_deadline' => Carbon::now()->addDays(25),
            ],
            [
                'title' => 'Introduction to AI Workshop',
                'description' => 'One-day workshop on artificial intelligence fundamentals',
                'content' => 'Get started with AI concepts, machine learning basics, and practical applications.',
                'event_type' => 'workshop',
                'start_date' => Carbon::now()->addDays(15),
                'end_date' => Carbon::now()->addDays(15)->addHours(8),
                'is_online' => true,
                'meeting_link' => 'https://zoom.us/j/example',
                'registration_deadline' => Carbon::now()->addDays(13),
            ]
        ];

        foreach ($events as $event) {
            Event::create($event);
        }
    }
}
