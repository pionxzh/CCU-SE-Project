<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

/* Hawa */
use Mail;
use App\Employee;
use App\Employer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    /**
     *
     *
     *
     *
     *  --------------HAWA--------------
     *
     *
     *
     *
     *
     * */





    /* encode 1 as 外籍生, encode 2 as 廠商 */
    public function makeCreate(&$thisUser)
    {
        switch($thisUser ->user_type)
        {
            case 1:

                $newEmployee = new Employee;
                $newEmployee ->uid = $thisUser ->id;
                $newEmployee ->save();
                break;


            case 2:

                $newEmployer = new Employer;
                $newEmployer ->uid = $thisUser ->id;
                $newEmployer ->save();
                break;

        }
    }



    public function registrate()
    {

        $ret = new \stdClass();

        if(User::where('email', '=', Input::get('email')) ->exists())
        {
            return json_encode(($ret ->stat = 0));
        }

        /* V-key */
        $emailtok = str_random(30);


        $newUser = new User;
        $newUser ->email = Input::get('email');
        $newUser ->password = bcrypt(Input::get('password'));
        $newUser ->user_type = Input::get('user_type');
        $newUser ->emailtok = $emailtok;
        $newUser ->save();

        /*
        ************************* Mass Assignment will fail*********************
        User::create([

            'email' => Input::get('email'),
            'password' => bcrypt(Input::get('password')),
            'user_type' => Input::get('user_type'),
            'emailtok' => $emailtok
        ]);
         ***********************************************************************/


        $this ->makeCreate($newUser);

        $emailtok = ['emailtok' => $emailtok];


        Mail::send('email.verify', $emailtok, function($message)    {

            $message ->to(Input::get('email'), 'To whom it may concern')
                ->subject('Verify your email address');

            $message ->from('CCU_iTalents@gmail.com', 'CCU_iTalents');
        });


        return json_encode(($ret ->stat = 1));
    }

}
