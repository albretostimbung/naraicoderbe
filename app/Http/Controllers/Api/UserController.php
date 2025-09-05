<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseFormatter;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Get(
     *      path="/users",
     *      operationId="getUsersList",
     *      tags={"Users"},
     *      summary="Get list of users",
     *      description="Returns list of users with pagination, sorting, and searching.",
     *      security={{"sanctum":{}}},
     *      @OA\Parameter(name="search", in="query", description="Search term for users", required=false, @OA\Schema(type="string")),
     *      @OA\Parameter(name="per_page", in="query", description="Number of items per page", required=false, @OA\Schema(type="integer", default=10)),
     *      @OA\Parameter(name="page", in="query", description="Page number", required=false, @OA\Schema(type="integer", default=1)),
     *      @OA\Parameter(name="sort_by", in="query", description="Column to sort by", required=false, @OA\Schema(type="string", default="created_at")),
     *      @OA\Parameter(name="sort_order", in="query", description="Sort order ('asc' or 'desc')", required=false, @OA\Schema(type="string", default="desc")),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/UserPaginator")
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      )
     * )
     */
    public function index()
    {
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
     *
     * @OA\Post(
     *      path="/users",
     *      operationId="storeUser",
     *      tags={"Users"},
     *      summary="Create a new user",
     *      description="Creates a new user and returns the user data.",
     *      security={{"sanctum":{}}},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/StoreUserRequest")
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="User created successfully",
     *          @OA\JsonContent(ref="#/components/schemas/UserApiResponse")
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Validation error"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      )
     * )
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
     *
     * @OA\Get(
     *      path="/users/{id}",
     *      operationId="getUserById",
     *      tags={"Users"},
     *      summary="Get user information",
     *      description="Returns user data",
     *      security={{"sanctum":{}}},
     *      @OA\Parameter(name="id", in="path", description="ID of user to return", required=true, @OA\Schema(type="integer")),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/UserApiResponse")
     *      ),
     *      @OA\Response(response=404, description="User not found"),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      )
     * )
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
     *
     * @OA\Put(
     *      path="/users/{id}",
     *      operationId="updateUser",
     *      tags={"Users"},
     *      summary="Update existing user",
     *      description="Updates an existing user and returns the updated user data.",
     *      security={{"sanctum":{}}},
     *      @OA\Parameter(name="id", in="path", description="ID of user to update", required=true, @OA\Schema(type="integer")),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/UpdateUserRequest")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="User updated successfully",
     *          @OA\JsonContent(ref="#/components/schemas/UserApiResponse")
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Validation error"
     *      ),
     *      @OA\Response(response=404, description="User not found"),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      )
     * )
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
     *
     * @OA\Delete(
     *      path="/users/{id}",
     *      operationId="deleteUser",
     *      tags={"Users"},
     *      summary="Delete existing user",
     *      description="Deletes a user record and returns no content.",
     *      security={{"sanctum":{}}},
     *      @OA\Parameter(name="id", in="path", description="ID of user to delete", required=true, @OA\Schema(type="integer")),
     *      @OA\Response(
     *          response=200,
     *          description="User deleted successfully"
     *      ),
     *      @OA\Response(response=404, description="User not found"),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      )
     * )
     */
    public function destroy(User $user)
    {
        $user->delete();

        return ResponseFormatter::success(null, 'User deleted successfully');
    }
}
