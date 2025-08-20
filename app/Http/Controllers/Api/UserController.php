<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
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
        $users = User::with(['eventRegistrations', 'testimonials'])
            ->when(request('search'), function($query, $search) {
                return $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('job_title', 'like', "%{$search}%")
                        ->orWhere('company', 'like', "%{$search}%")
                        ->orWhere('location', 'like', "%{$search}%");
                });
            })
            ->orderBy(request('sort_by', 'created_at'), request('sort_order', 'desc'))
            ->paginate(request('per_page', 10));

        return ResponseFormatter::success($users, 'Users retrieved successfully');
    }

    /**
     * Store a newly created user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required_with:password|same:password',
            'phone' => 'nullable|string|max:15',
            'profile_photo' => 'nullable|image|max:2048',
            'bio' => 'nullable|string|max:500',
            'skills' => 'nullable|array',
            'job_title' => 'nullable|string|max:100',
            'company' => 'nullable|string|max:100',
            'linkedin' => 'nullable|url|max:255',
            'github' => 'nullable|url|max:255',
            'portfolio_url' => 'nullable|url|max:255',
            'location' => 'nullable|string|max:100'
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error('Validation failed', 422, $validator->errors());
        }

        $user = User::create($validator->validated());

        return ResponseFormatter::success($user->load('eventRegistrations', 'testimonials'), 'User created successfully', 201);
    }

    /**
     * Display the specified user.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(User $user)
    {
        return ResponseFormatter::success($user->load('eventRegistrations', 'testimonials'), 'User retrieved successfully');
    }

    /**
     * Update the specified user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:users,email,' . $user->id,
            'password' => 'sometimes|required|string|min:8|confirmed',
            'password_confirmation' => 'sometimes|required_with:password|same:password',
            'phone' => 'nullable|string|max:15',
            'profile_photo' => 'nullable|image|max:2048',
            'bio' => 'nullable|string|max:500',
            'skills' => 'nullable|array',
            'job_title' => 'nullable|string|max:100',
            'company' => 'nullable|string|max:100',
            'linkedin' => 'nullable|url|max:255',
            'github' => 'nullable|url|max:255',
            'portfolio_url' => 'nullable|url|max:255',
            'location' => 'nullable|string|max:100'
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error('Validation failed', 422, $validator->errors());
        }

        $user->update($validator->validated());

        return ResponseFormatter::success($user->fresh()->load('eventRegistrations', 'testimonials'), 'User updated successfully');
    }

    /**
     * Remove the specified user.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(User $user)
    {
        $user->delete();

        return ResponseFormatter::success(null, 'User deleted successfully');
    }

    /**
     * Get registrations for a specific user.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function registrations(User $user)
    {
        $registrations = $user->eventRegistrations()
            ->with('user')
            ->paginate(request('per_page', 10));

        return ResponseFormatter::success($registrations, 'User registrations retrieved successfully');
    }
}
