<?php

namespace App\OpenApi;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="User",
 *     type="object",
 *     title="User",
 *     properties={
 *         @OA\Property(property="id", type="integer", example=1),
 *         @OA\Property(property="name", type="string", example="John Doe"),
 *         @OA\Property(property="email", type="string", format="email", example="john.doe@example.com"),
 *         @OA\Property(property="phone", type="string", example="1234567890"),
 *         @OA\Property(property="profile_photo", type="string", format="uri", nullable=true),
 *         @OA\Property(property="bio", type="string", nullable=true),
 *         @OA\Property(property="skills", type="array", @OA\Items(type="string"), nullable=true),
 *         @OA\Property(property="job_title", type="string", nullable=true),
 *         @OA\Property(property="company", type="string", nullable=true),
 *         @OA\Property(property="linkedin", type="string", format="uri", nullable=true),
 *         @OA\Property(property="github", type="string", format="uri", nullable=true),
 *         @OA\Property(property="portfolio_url", type="string", format="uri", nullable=true),
 *         @OA\Property(property="location", type="string", nullable=true),
 *         @OA\Property(property="created_at", type="string", format="date-time"),
 *         @OA\Property(property="updated_at", type="string", format="date-time"),
 *         @OA\Property(property="event_registrations", type="array", @OA\Items()),
 *         @OA\Property(property="testimonials", type="array", @OA\Items())
 *     }
 * )
 *
 * @OA\Schema(
 *     schema="UserPaginator",
 *     type="object",
 *     properties={
 *         @OA\Property(property="current_page", type="integer"),
 *         @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/User")),
 *         @OA\Property(property="first_page_url", type="string", format="uri"),
 *         @OA\Property(property="from", type="integer"),
 *         @OA\Property(property="last_page", type="integer"),
 *         @OA\Property(property="last_page_url", type="string", format="uri"),
 *         @OA\Property(property="next_page_url", type="string", format="uri", nullable=true),
 *         @OA\Property(property="path", type="string", format="uri"),
 *         @OA\Property(property="per_page", type="integer"),
 *         @OA\Property(property="prev_page_url", type="string", format="uri", nullable=true),
 *         @OA\Property(property="to", type="integer"),
 *         @OA\Property(property="total", type="integer")
 *     }
 * )
 *
 * @OA\Schema(
 *     schema="UserApiResponse",
 *     type="object",
 *     properties={
 *         @OA\Property(property="meta", type="object", properties={
 *             @OA\Property(property="code", type="integer"),
 *             @OA\Property(property="status", type="string"),
 *             @OA\Property(property="message", type="string")
 *         }),
 *         @OA\Property(property="data", ref="#/components/schemas/User")
 *     }
 * )
 *
 * @OA\Schema(
 *     schema="StoreUserRequest",
 *     type="object",
 *     required={"name", "email", "password", "password_confirmation"},
 *     properties={
 *         @OA\Property(property="name", type="string", example="John Doe"),
 *         @OA\Property(property="email", type="string", format="email", example="john.doe@example.com"),
 *         @OA\Property(property="password", type="string", format="password", example="password123"),
 *         @OA\Property(property="password_confirmation", type="string", format="password", example="password123"),
 *         @OA\Property(property="phone", type="string", example="1234567890"),
 *         @OA\Property(property="profile_photo", type="string", format="binary"),
 *         @OA\Property(property="bio", type="string"),
 *         @OA\Property(property="skills", type="array", @OA\Items(type="string")),
 *         @OA\Property(property="job_title", type="string"),
 *         @OA\Property(property="company", type="string"),
 *         @OA\Property(property="linkedin", type="string", format="uri"),
 *         @OA\Property(property="github", type="string", format="uri"),
 *         @OA\Property(property="portfolio_url", type="string", format="uri"),
 *         @OA\Property(property="location", type="string")
 *     }
 * )
 *
 * @OA\Schema(
 *     schema="UpdateUserRequest",
 *     type="object",
 *     properties={
 *         @OA\Property(property="name", type="string", example="John Doe"),
 *         @OA\Property(property="email", type="string", format="email", example="john.doe@example.com"),
 *         @OA\Property(property="password", type="string", format="password", example="password123"),
 *         @OA\Property(property="password_confirmation", type="string", format="password", example="password123"),
 *         @OA\Property(property="phone", type="string", example="1234567890"),
 *         @OA\Property(property="profile_photo", type="string", format="binary"),
 *         @OA\Property(property="bio", type="string"),
 *         @OA\Property(property="skills", type="array", @OA\Items(type="string")),
 *         @OA\Property(property="job_title", type="string"),
 *         @OA\Property(property="company", type="string"),
 *         @OA\Property(property="linkedin", type="string", format="uri"),
 *         @OA\Property(property="github", type="string", format="uri"),
 *         @OA\Property(property="portfolio_url", type="string", format="uri"),
 *         @OA\Property(property="location", type="string")
 *     }
 * )
 *
 * @OA\SecurityScheme(
 *      securityScheme="sanctum",
 *      type="http",
 *      scheme="bearer",
 *      bearerFormat="JWT",
 * )
 */
class Schemas {}

