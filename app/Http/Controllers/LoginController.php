<?php

namespace App\Http\Controllers;

use App\Http\Requests\Login\LoginPostRequest;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends Controller
{
    use ThrottlesLogins;

    /**
     * @return string
     */
    public function username()
    {
        return 'username';
    }

    /**
     * @return Factory|RedirectResponse|View
     */
    public function index()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('login/login');
    }

    /**
     * @param LoginPostRequest $request
     * @return ResponseFactory|\Illuminate\Http\Response|void
     * @throws ValidationException
     */
    public function login(LoginPostRequest $request)
    {
        if (method_exists($this, 'hasTooManyLoginAttempts') && $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        if (Auth::guard()->attempt($request->only('username', 'password'), $request->input('remember_me'))) {
            $this->clearLoginAttempts($request);
            return response(['status' => true, 'redirect' => redirect()->intended('dashboard')->getTargetUrl()]);
        } else {
            $this->incrementLoginAttempts($request);
            return response(['message' => 'Incorrect username or password'], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @return RedirectResponse
     */
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
