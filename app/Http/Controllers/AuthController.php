<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterationRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Exception;
use Illuminate\Support\Facades\Password;
use App\Enums\TokenAbility;
use Carbon\Carbon;
use App\Traits\ResponseTrait;
use Egulias\EmailValidator\Result\InvalidEmail;
use Kreait\Firebase\Auth\SignIn\FailedToSignIn;
use Kreait\Firebase\Contract\Database;
use Kreait\Laravel\Firebase\Facades\Firebase;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use Kreait\Firebase\Auth as FirebaseAuth;
use Kreait\Firebase\Contract\Auth as ContractAuth;
use Kreait\Firebase\Exception\Auth\InvalidPassword;
use Kreait\Firebase\Factory;


// use Kreait\Firebase\Auth;
/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="Laravel API Documentation",
 *      description="Swagger documentation for Laravel API",
 * )
 * 
 * @OA\Tag(
 *     name="Auth",
 *     description="API Endpoints of Users Authentication"
 * )
 * @OA\Server(
 *         url="http://localhost/api"
 *     ),
 *       @OA\SecurityScheme(
 *         securityScheme="bearerAuth",
 *         type="http",
 *         scheme="bearer",
 *         bearerFormat="JWT",
 *         in="header"
 *     )
 * 
 * 
 */
class AuthController extends Controller
{
    use ResponseTrait;

    protected $database;
    protected $auth;

    public function __construct(Database $database)
    {
        $this->database = $database;
        $this->auth = Firebase::auth();
    }



    // with JWT

    // public function register(RegisterationRequest $request)
    // {
    //     try {
    //         $user = User::create($request->validated());
    //         if ($user) {
    //             return $this->respondWithToken(Auth::login($user), $user);
    //         } else {
    //             return $this->errorResponse('User registration failed', 400);
    //         }
    //     } catch (JWTException $e) {
    //         return $this->errorResponse('An error occurred during registration'.$e->getMessage(), 500);
    //     }
    // }
    // protected function respondWithToken($token, $user)
    // {
    //     return $this->respondWithAccessToken($token, $user);
    // }

    // public function login(LoginRequest $request)
    // {
    //     try {
    //         $credentials = $request->validated();
    //         if (!$token = Auth::attempt($credentials)) {
    //             return $this->errorResponse('Unauthorized', 401);
    //         }
    //         $user = Auth::user();
    //         return $this->respondWithToken($token, $user);
    //     } catch (JWTException $e) {
    //         return $this->errorResponse('An error occurred during login'.$e->getMessage(), 500);
    //     }
    // }

    // public function refresh()
    // {
    //     try {
    //         $user = Auth::user();
    //         $token = Auth::refresh();
    //         return $this->respondWithToken($token, $user);
    //     } catch (JWTException $e) {
    //         // return response()->json([
    //         //     'status' => 'error',
    //         //     'message' => 'An error occurred during token refresh',
    //         //     'error' => $e->getMessage(),
    //         // ], 500);
    //         return $this->errorResponse('An error occurred during token refresh'.$e->getMessage(), 500);
    //     }
    // }

    // public function logout()
    // {
    //     try {
    //         auth()->logout();
    //         // return response()->json([
    //         //     'status' => 'success',
    //         //     'message' => 'Successfully logged out',
    //         // ]);
    //         return $this->successResponse('Successfully logged out');
    //     } catch (JWTException $e) {
    //         // return response()->json([
    //         //     'status' => 'error',
    //         //     'message' => 'An error occurred during logout',
    //         //     'error' => $e->getMessage(),
    //         // ], 500);
    //         return $this->errorResponse('An error occurred during logout'.$e->getMessage(), 500);
    //     }
    // }

    // public function forgotPassword(Request $request)
    // {
    //     try {
    //         $request->validate(['email' => 'required|email']);

    //         $status = Password::sendResetLink(
    //             $request->only('email')
    //         );

    //         if ($status === Password::RESET_LINK_SENT) {
    //             // return response()->json(['status' => 'success', 'message' => __($status)]);
    //             return $this->successResponse(__($status));
    //         } else {
    //             // return response()->json(['status' =g> 'error', 'message' => __($status)], 404);
    //             return $this->errorResponse(__($status), 404);
    //         }
    //     } catch (Exception $e) {
    //         // return response()->json(['status' => 'error', 'message' => 'An error occurred during password reset', 'error' => $e->getMessage()], 500);
    //         return $this->errorResponse('An error occurred during password reset', 500);
    //     }
    // }

    // public function resetPassword(Request $request)
    // {
    //     try {
    //         $request->validate([
    //             'email' => 'required|email',
    //             'token' => 'required',
    //             'password' => 'required|confirmed',
    //         ]);

    //         $status = Password::reset(
    //             $request->only('email', 'token', 'password', 'password_confirmation'),
    //             function ($user, $password) {
    //                 $user->forceFill([
    //                     'password' => Hash::make($password)
    //                 ])->save();
    //             }
    //         );

    //         if ($status === Password::PASSWORD_RESET) {
    //             // return response()->json(['status' => 'success', 'message' => __($status)]);
    //             return $this->successResponse(__($status));
    //         } else {
    //             // return response()->json(['status' => 'error', 'message' => __($status)], 400);
    //             return $this->errorResponse(__($status), 400);
    //         }
    //     } catch (Exception $e) {
    //         // return response()->json(['status' => 'error', 'message' => 'An error occurred during password reset', 'error' => $e->getMessage()], 500);  
    //         return $this->errorResponse('An error occurred during password reset', 500);            
    //     }

    // }


    // Auth With Sanctum 
    /**
     * @group User Management
     *
     * Register a new user and return access and refresh tokens.
     *
     * @bodyParam name string required The name of the user. Example: John Doe
     * @bodyParam email string required The email address of the user. Example: john.doe@example.com
     * @bodyParam password string required The password for the user. Example: secretpassword
     * @bodyParam password_confirmation string required The confirmation of the password. Example: secretpassword
     *
     * @response 201 {
     *   "data": {
     *     "id": 1,
     *     "name": "John Doe",
     *     "email": "john.doe@example.com",
     *     "accessToken": "string",
     *     "refreshToken": "string"
     *   },
     *   "message": "User registered successfully"
     * }
     * @response 500 {
     *   "error": "An error occurred during registration: [error_message]"
     * }
     *
     * @responseField $.data.accessToken string The access token for the user.
     * @responseField $.data.refreshToken string The refresh token for the user.
     */
    // public function register(RegisterationRequest $request)
    // {
    //     //sanctum

    //     // try{
    //     //     $user = User::create($request->validated());
    //     //     // $token = $user->createToken('personal access token')->plainTextToken;
    //     //     $accessToken = $user->createToken('access_token', [TokenAbility::ACCESS_API->value], Carbon::now()->addMinutes(config('sanctum.ac_expiration')));
    //     //     $refreshToken = $user->createToken('refresh_token', [TokenAbility::ISSUE_ACCESS_TOKEN->value], Carbon::now()->addMinutes(config('sanctum.rt_expiration')));

    //     //     $user->accesToken = $accessToken;
    //     //     $user->refreshToken = $refreshToken;
    //     //     return $this->successResponse($user, 'User registered successfully', 201);
    //     // }catch(Exception $e){
    //     //     return $this->errorResponse('An error occurred during registration'.$e->getMessage(), 500);
    //     // }
    // } 




    public function register(RegisterationRequest $request)
    {
        // using firebase

        // $newPostKey = $this->database->getReference('users')->push($request->validated());
        // return response()->json(['id' => $newPostKey->getKey()]);

        $createdUser = $this->auth->createUserWithEmailAndPassword($request->email, $request->password);
        if ($createdUser) {
            return $this->successResponse($createdUser, 'User registered successfully', 201);
        }
        return $this->errorResponse('An error occurred during registration', 500);
    }


    // /**
    //  * @group User Management
    //  *
    //  * Log in a user and return access and refresh tokens.
    //  *
    //  * @bodyParam email string required The email address of the user. Example: john.doe@example.com
    //  * @bodyParam password string required The password for the user. Example: secretpassword
    //  *
    //  * @response 200 {
    //  *   "data": {
    //  *     "id": 1,
    //  *     "name": "John Doe",
    //  *     "email": "john.doe@example.com",
    //  *     "accessToken": "string",
    //  *     "refreshToken": "string"
    //  *   },
    //  *   "message": "User logged in successfully"
    //  * }
    //  * @response 401 {
    //  *   "error": "Unauthorized"
    //  * }
    //  *
    //  * @responseField $.data.accessToken string The access token for the user.
    //  * @responseField $.data.refreshToken string The refresh token for the user.
    //  */
    // public function login(LoginRequest $request)
    // {
    //     //sanctum
    //     // $credentials = $request->validated();
    //     // // if(!Auth::attempt($credentials)){
    //     // //     return $this->errorResponse('Unauthorized', 401);
    //     // // }
    //     // $user = User::where('email', $credentials['email'])->first();
    //     // if (!$user || !Hash::check($credentials['password'], $user->password)) {
    //     //     return $this->errorResponse('Invalid email or password.', 404);
    //     // }
    //     // $accessToken = $user->createToken('access_token', [TokenAbility::ACCESS_API->value], Carbon::now()->addMinutes(config('sanctum.ac_expiration')));
    //     // $refreshToken = $user->createToken('refresh_token', [TokenAbility::ISSUE_ACCESS_TOKEN->value], Carbon::now()->addMinutes(config('sanctum.rt_expiration')));

    //     // $user->accesToken = $accessToken;
    //     // $user->refreshToken = $refreshToken;
    //     // return $this->successResponse($user, 'User logged in successfully', 200);
    // } 

    /**
     * @OA\Post(
     *    path="/login",
     *    tags={"Auth"},
     *    summary="User login",
     *    description="Authenticate a user and return access and refresh tokens",
     *    @OA\RequestBody(
     *        required=true,
     *        @OA\MediaType(
     *            mediaType="application/json",
     *            @OA\Schema(
     *                @OA\Property(
     *                    property="email",
     *                    type="string",
     *                    description="User's email address",
     *                    example="dd@gmail.com"
     *                ),
     *                @OA\Property(
     *                    property="password",
     *                    type="string",
     *                    description="User's password",
     *                    example="12345678"
     *                )
     *            )
     *        )
     *    ),
     *    @OA\Response(
     *        response=200,
     *        description="User logged in successfully",
     *        @OA\JsonContent(
     *            @OA\Property(
     *                property="success",
     *                type="boolean",
     *                example=true
     *            ),
     *            @OA\Property(
     *                property="message",
     *                type="string",
     *                example="User logged in successfully"
     *            ),
     *            @OA\Property(
     *                property="data",
     *                type="object",
     *                @OA\Property(
     *                    property="accessToken",
     *                    type="string",
     *                    description="The access token for authenticated requests",
     *                    example="eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c"
     *                ),
     *                @OA\Property(
     *                    property="refreshToken",
     *                    type="string",
     *                    description="The refresh token to obtain a new access token",
     *                    example="eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c"
     *                )
     *            )
     *        )
     *    ),
     *    @OA\Response(
     *        response=401,
     *        description="Unauthorized - Invalid credentials",
     *        @OA\JsonContent(
     *            @OA\Property(
     *                property="success",
     *                type="boolean",
     *                example=false
     *            ),
     *            @OA\Property(
     *                property="message",
     *                type="string",
     *                example="Unauthorized"
     *            )
     *        )
     *    )
     * )
     */
    public function login(LoginRequest $request)
    {
        // using firebase

        // $absolutePath = base_path('config/firebase-credentials.json');
        // $factory = (new Factory)->withServiceAccount($absolutePath);
        // $auth = $factory->createAuth();

        try {
            $email = $request->email;
            $password = $request->password;
            $user = $this->auth->signInWithEmailAndPassword($email, $password);
            return response()->json(['message' => 'User logged in successfully', 'data' => $user->data()]);
        } catch (InvalidPassword $e) {
            return response()->json(['message' => 'Invalid password'], 400);
        } catch (FailedToSignIn $e) {
            return response()->json(['message' => 'Failed to sign in: ' . $e->getMessage()], 500);
        }
    }

    public function logout(Request $request)
    {
        if ($request->user()->currentAccessToken()->delete()) {
            return $this->successResponse('User logged out successfully', 200);
        }
        return $this->errorResponse('An error occurred during logout', 500);
    }

    // refresh token
    public function refreshToken(Request $request)
    {
        $accessToken = $request->user()->createToken('access_token', [TokenAbility::ACCESS_API->value], Carbon::now()->addMinutes(config('sanctum.ac_expiration')))->plainTextToken;
        return $this->successResponse(['access_token' => $accessToken], 'Token refreshed successfully', 200);
    }
}


//http://localhost/api/documentation