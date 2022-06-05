<?php

namespace App\Http\Controllers\Api\Auth;

use Exception;
use App\Models\Role;
use App\Models\User;
use App\Models\AuthToken;
use Illuminate\Http\Request;
use App\Helpers\AuthTokenHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\User\UserResource;
use App\Http\Requests\Auth\RegisterRequest;
use App\Notifications\Auth\AuthNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use DomainException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AuthController extends Controller
{
    private $helper;


    public function __construct(AuthTokenHelper $helper)
    {
        $this->helper = $helper;
    }

    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (is_null($user->email_verified_at)) {
            throw ValidationException::withMessages([
                'email' => 'Your email is not verified.
                Please click <a style="color:blue !important" href="' . config('app.frontend_app_url') . '/verify-email">here</a> to resend verification email.'
            ]);
        }

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken('web')->plainTextToken;
        return response()->json([
            'data' => [
                'status' => true,
                'auth_token' => $token,
                'user' => (new UserResource($user->load([
                    'roles'
                ]))),
            ]
        ], 200);
    }

    public function register(RegisterRequest $request)
    {
        $userRole = Role::where('name', Role::USER_ROLE)->first();
        if (!$userRole) {
            throw new ModelNotFoundException('Role not found. Please contact admin');
        }
        $user = User::create($request->validated());
        $user->roles()->attach($userRole->id);

        $authToken = $this->helper->createToken($user, AuthToken::VERIFY_EMAIL_TOKEN);
        Notification::send($user, new AuthNotification($authToken));
        return response()->json((new UserResource($user->load([
            'roles'
        ]))), 201);
    }

    public function resetPassword(ForgotPasswordRequest $request)
    {
        $user = User::whereEmail($request->validated()['email'])->first();
        if (!$user) throw new ModelNotFoundException();

        $authToken = $this->helper->createToken($user, AuthToken::PASSWORD_RESET_TOKEN);
        Notification::send($user, new AuthNotification($authToken));
        return response()->json(['status' => true], 200);
    }

    public function verifyEmail(ForgotPasswordRequest $request)
    {
        $user = User::whereEmail($request->validated()['email'])->first();
        if (!$user) throw new ModelNotFoundException();

        if (!is_null($user->email_verified_at))
            throw ValidationException::withMessages([
                'email' => 'This email is already verified'
            ]);

        $authToken = $this->helper->createToken($user, AuthToken::VERIFY_EMAIL_TOKEN);
        Notification::send($user, new AuthNotification($authToken));
        return response()->json(['status' => true], 200);
    }

    public function postResetPassword(ResetPasswordRequest $request, $token)
    {
        $authToken = $this->helper->validateToken($token, AuthToken::PASSWORD_RESET_TOKEN);
        $authToken->user->update([
            'password' => Hash::make($request->validated()['password'])
        ]);

        $authToken->delete();
        return response()->json(['status' => true], 200);
    }

    public function postVerifyEmail($token)
    {
        $authToken = $this->helper->validateToken($token, AuthToken::VERIFY_EMAIL_TOKEN);
        $authToken->user->email_verified_at = now();
        $authToken->user->save();
        $authToken->delete();
        return response()->json(['status' => true], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'status' => true
        ], 200);
    }

    public function me(Request $request)
    {
        return response()->json([
            'data' => (new UserResource($request->user()->load([
                'roles'
            ]))),
            'status' => true
        ]);
    }
}