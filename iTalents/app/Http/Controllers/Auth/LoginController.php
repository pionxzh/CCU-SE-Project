<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

/*---------- Hawa---------*/
use Auth;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;


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
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */


    /*
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
     */



    /*****************************************************************
     *
     *
     *
     *
     *                              Hawa
     *
     *
     *
     *
     *
     ****************************************************************/

    public function login()
    {
        $ret = new \stdClass();
        $ret ->stat = 0;

        if(Auth::attempt(['email' => Input::get('email'), 'password' => Input::get('password')]))
        {
            $ret ->stat = 1;
        }
        return json_encode($ret);
    }

    public function logout()
    {
        $ret = new \stdClass();
        $ret ->stat = 0;

        if(Auth::check())
        {
            Auth::logout();
            $ret ->stat = 1;
        }
        return json_encode($ret);
    }
}
