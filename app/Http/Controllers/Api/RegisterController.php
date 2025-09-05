<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /**
     * @OA\Post(
     *      path="/register",
     *      operationId="registerUser",
     *      tags={"Authentication"},
     *      summary="User Registration",
     *      description="Registers a new user and returns the user data.",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/StoreUserRequest")
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="User registered successfully",
     *          @OA\JsonContent(ref="#/components/schemas/UserApiResponse")
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Validation error"
     *      )
     * )
     */
    public function __invoke(Request $request)
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
            'location' => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error('Validation Failed', 422, $validator->errors());
        }


        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'phone' => $request->phone,
            'profile_photo' => $request->profile_photo,
            'bio' => $request->bio,
            'skills' => json_encode($request->skills), // Store skills as JSON
            'job_title' => $request->job_title,
            'company' => $request->company,
            'linkedin' => $request->linkedin,
            'github' => $request->github,
            'portfolio_url' => $request->portfolio_url,
            'location' => $request->location,
            'is_active' => true, // Default to active
        ]);

        $user->assignRole('member'); // Assign default role

        return ResponseFormatter::success($user, 'User registered successfully');
    }
}
