<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


/*******Hawa********/
use Auth;
use App\User;
use App\Employer;
use App\Employee;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;


class ApiController extends Controller
{

    /* **************************
     * / Route::get('api/user') /
     * *************************/
    public function getUserInfo()
    {
        $ret = new \stdClass();
        if(Auth::check())
        {
            $ret ->stat = 1;
            $ret ->user_type = Auth::User() ->user_type;
            $ret ->username = Auth::User() ->email;
            return json_encode($ret);
        }
        else
        {
            $ret ->stat = 0;
            return json_encode($ret);
        }
    }



    /* ***********************************
     * / Route::get('api/user/personel') /
     * **********************************/
    public function getPersonalInfo()
    {
        if(Auth::check())
        {
            switch(Auth::User() ->user_type)
            {
                case 1:

                    $ret = Employee::where('uid', '=', Auth::User() ->id) ->first();
                    $ret ->stat = 1;
                    return json_encode($ret);

                case 2:

                    $ret = Employer::where('uid', '=', Auth::User() ->id) ->first();
                    $ret -> stat = 1;
                    return json_encode($ret);
            }
        }
        else
        {
            $ret = new \stdClass();
            $ret ->stat = 0;
            return json_encode($ret);
        }
    }


    public function updatePersonalInfo()
    {
        $ret = new \stdClass();

        if(Auth::check())
        {

            switch(Auth::User() ->user_type)
            {
                case 1:

                    $thisUser = Employee::where('uid', '=', Auth::User() ->id) ->first();
                    $thisUser ->firstname = Input::get('');
                    $thisUser ->lastname = Input::get('');
                    $thisUser ->sex = Input::get('');
                    $thisUser ->birth = Input::get('');
                    $thisUser ->pid = Input::get('');
                    $thisUser ->phone = Input::get('');
                    $thisUser ->nation = Input::get('');
                    $thisUser ->save();
                    break;


                case 2:

                    $thisUser = Employer::where('id', '=', Auth::User() ->id) ->first();
                    $thisUser ->cid = Input::get('');
                    $thisUser ->cname = Input::get('');
                    $thisUser ->cphone = Input::get('');
                    $thisUser ->caddress = Input::get('');
                    $thisUser ->save();
                    break;

            }
            $ret ->stat = 1;
            return json_encode($ret);
        }
        else
        {
            $ret ->stat = 0;
            return json_encode($ret);
        }
    }





}
