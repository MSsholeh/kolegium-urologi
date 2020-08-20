<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Providers\RouteServiceProvider;
use Corcel\Laravel\Auth\AuthUserProvider;
use Corcel\Model\User;
use Corcel\Services\PasswordService;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Exception;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/admin/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

//    public function loginAdmin(Request $request)
//    {
//        try {
//
//            $this->validateLogin($request);
//
//            $user = User::where('user_email', $request->email)->first();
//
//            if ( ! $user) {
//                throw ValidationException::withMessages(['error' => 'Wrong Email or Password.']);
//            }
//
//            $meta = $user->meta->where('meta_key', 'wp_capabilities')->first();
//
//            if ( ! array_key_exists('administrator', $meta->value)) {
//                throw ValidationException::withMessages(['error' => 'Role is not Administrator']);
//            }
//
//            $authProvider = new AuthUserProvider;
//            $check = $authProvider->validateCredentials($user, ['password' => $request->password]);
//
//            if ( ! $check) {
//                throw ValidationException::withMessages(['error' => 'Wrong Email or Password.']);
//            }
//
//            Admin::updateOrCreate([
//                'user_id' => $user->ID
//            ], [
//                'name' => $user->user_login,
//                'email' => $user->user_email,
//                'password' => Hash::make($request->password),
//            ]);
//
//            return $this->login($request);
//
//        } catch (ValidationException $e) {
//
//            return response()->json($e->errors(), 422);
//
//        }
//    }

    protected function guard()
    {
        return Auth::guard('admin');
    }

    protected function authenticated(Request $request, $user)
    {
        if ($request->expectsJson()) {
            return response()->json($user);
        } else {
            return redirect()->route('admin.home');
        }
    }
}
