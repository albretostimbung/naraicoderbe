<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /**
     * @OA\Post(
     *      path="/login",
     *      operationId="loginUser",
     *      tags={"Authentication"},
     *      summary="User Login",
     *      description="Logs in a user and returns an API token.",
     *      @OA\RequestBody(
     *          required=true,
     *          description="User credentials",
     *          @OA\JsonContent(
     *              required={"email","password"},
     *              @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *              @OA\Property(property="password", type="string", format="password", example="password")
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Login successful",
     *          @OA\JsonContent(
     *              @OA\Property(property="meta", type="object",
     *                  @OA\Property(property="code", type="integer", example=200),
     *                  @OA\Property(property="status", type="string", example="success"),
     *                  @OA\Property(property="message", type="string", example="Login successful")
     *              ),
     *              @OA\Property(property="data", type="object",
     *                  @OA\Property(property="token", type="string", example="1|aBcDeFgHiJkLmNoPqRsTuVwXyZ"),
     *                  @OA\Property(property="user", ref="#/components/schemas/User")
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Invalid credentials"
     *      )
     * )
     */
    public function __invoke(Request $request)
    {
        $request->validate(['email' => 'required|email', 'password' => 'required']);
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
        
        $token = $user->createToken('api')->plainTextToken;

        return ResponseFormatter::success([
            'token' => $token,
            'user' => $user
        ], 'Login successful');
    }
}
