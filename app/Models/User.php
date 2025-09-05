<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
// $table->string('name');
// $table->string('email')->unique();
// $table->timestamp('email_verified_at')->nullable();
// $table->string('password');
// $table->string('phone')->nullable();
// $table->string('profile_photo')->nullable();
// $table->text('bio')->nullable();
// $table->text('skills')->nullable(); // JSON field for skills array
// $table->string('job_title')->nullable();
// $table->string('company')->nullable();
// $table->string('linkedin')->nullable();
// $table->string('github')->nullable();
// $table->string('portfolio_url')->nullable();
// $table->string('location')->nullable();
// $table->boolean('is_active')->default(true);
// $table->enum('role', ['admin', 'member'])->default('member');
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'profile_photo',
        'bio',
        'skills', // JSON field for skills array
        'job_title',
        'company',
        'linkedin',
        'github',
        'portfolio_url',
        'location',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function testimonials()
    {
        return $this->hasMany(Testimonial::class);
    }

    public function eventRegistrations()
    {
        return $this->hasMany(EventRegistration::class);
    }
}
