<?php

namespace Modules\Users\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Modules\Users\Events\VerifyEmailByCode;
use Modules\Users\Events\VerifyPhoneByCode;
use Modules\Users\Http\Requests\RegisterRequest;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * Activate user account using a code sent to either email or phone
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function activateAccount(Request $request)
    {
        $request->validate([
            'code_type' => 'required|in:email,phone',
            'code' => 'required|integer'
        ]);

        if ($request->code_type == 'phone') {
            if ($request->code == User::findorfail(auth('api')->id())->phone_code) {
                $user = auth('api')->user();
                $user->phone_code = null;
                $user->phone_verified_at = now();
                $user->save();
                $message = __('main.phone_activated_successfuly');
            } else {
                $message = __('main.phone_activation_failed');
            }
        } elseif ($request->code_type == 'email') {
            if ($request->code == User::findorfail(auth('api')->id())->email_code) {
                $user = auth('api')->user();
                $user->email_code = null;
                $user->email_verified_at = now();
                $user->save();
                $message = __('main.email_activated_successfuly');
            } else {
                $message = __('main.email_activation_failed');
            }
        }

        return res_data([], $message);
    }

    /**
     * Resend activation code to user email or phone
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function resendActivatonCode(Request $request)
    {
        $request->validate(['code_type' => 'required|in:email,phone']);

        $user = User::findorfail(auth('api')->id());

        if ($request->code_type == 'email' && !is_null($user->email_verified_at)) {
            $message = __('main.email_already_activated');
        } elseif ($request->code_type == 'phone' && !is_null($user->phone_verified_at)) {
            $message = __('main.phone_already_activated');
        } elseif ($request->code_type == 'phone') {
            event(new VerifyPhoneByCode($user));
            $message = __('main.phone_code_sent');
        } elseif ($request->code_type == 'email') {
            event(new VerifyEmailByCode($user));
            $message = __('main.email_code_sent');
        }

        return res_data([], $message);
    }

    /**
     * Registers a new user.
     *
     * @param \App\Http\Requests\RegisterRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();

        $data['email_code'] = mt_rand(10000, 99999);
        $data['phone_code'] = mt_rand(10000, 99999);

        User::create($data);

        return $this->attemptLogin([
            'email' => $request->email,
            'password' => $request->password,
        ], __('main.register_msg'));
    }

    /**
     * Login a user
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     *
     * @throws \Illuminate\Auth\AuthenticationException
     *
     * @uses \App\Http\Requests\LoginRequest
     */
    public function login(Request $request)
    {
        $credentials = ['password' => $request->password];

        if (filter_var($request->account, FILTER_VALIDATE_EMAIL)) {
            $credentials['email'] = $request->account;
        } else {
            $credentials['phone'] = $request->account;
        }

        return $this->attemptLogin($credentials, __('main.login_msg'));
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return res_data(auth()->user()->only(
            'id',
            'name',
            'email',
            'email_code',
            'email_verified_at',
            'phone',
            'phone_code',
            'phone_verified_at'
        ));
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return res_data([], __('main.logout_msg'));
    }

    /**
     * Refresh the user's token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return res_data($this->tokenPayload(auth()->refresh()));
    }

    /**
     * Attempt to login a user with given credentials.
     *
     * If the credentials are invalid, a JSON response with a 401 status code
     * and an "error" key containing the string "Unauthorized" will be returned.
     *
     * If the credentials are valid, a JSON response with the token payload and the
     * given message will be returned.
     *
     * @param array $credentials The credentials to attempt to login with.
     * @param string $message The message to include in the JSON response if the
     * credentials are valid.
     * @return \Illuminate\Http\JsonResponse
     */
    private function attemptLogin(array $credentials, string $message = '')
    {
        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return res_data($this->tokenPayload($token), $message);
    }

    /**
     * Build the token payload.
     *
     * @param string $token
     *
     * @return array
     */
    private function tokenPayload($token)
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 99999,
        ];
    }
}
