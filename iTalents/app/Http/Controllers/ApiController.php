<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


/*******Hawa********/
use Auth;
use App\User;
use App\Employer;
use App\Employee;
use App\Recruitment;
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

            switch(Auth::User() ->user_type)
            {
                case 1:
                    $ret ->emailStat = Employee::where('uid', '=', Auth::User() ->id) ->first() ->is_active;
                    $ret ->userType = Auth::User() ->user_type;
                    $ret ->username = Auth::User() ->email;
                    break;
                case 2:
                    $tempUser = Employer::where('uid', '=', Auth::User() ->id) ->first();
                    $ret ->emailState = $tempUser ->is_active;
                    $ret ->verify = $tempUser ->is_verify;
                    $ret ->userType = Auth::User() ->user_type;
                    $ret ->username = Auth::User() ->email;
                    break;
            }
        }
        else
        {
            $ret ->stat = 0;
        }
        return json_encode($ret);
    }



    /* ***********************************
     * / Route::get('api/user/personal') /
     * **********************************/
    public function getPersonalInfo()
    {
        $ret = new \stdClass();

        if(Auth::check())
        {
            switch(Auth::User() ->user_type)
            {
                case 1:

                    $ret = Employee::where('uid', '=', Auth::User() ->id) ->first();
                    break;

                case 2:

                    $ret = Employer::where('uid', '=', Auth::User() ->id) ->first();
                    break;
            }
            $ret ->stat = 1;
        }
        else
        {
            $ret ->stat = 0;
        }
        return json_encode($ret);
    }


    public function updatePersonalInfo()
    {
        $ret = new \stdClass();
        $ret ->stat = 0;

        if(Auth::check())
        {

            switch(Auth::User() ->user_type)
            {
                case 1:

                    $thisUser = Employee::where('uid', '=', Auth::User() ->id) ->first();
                    $thisUser ->firstname = Input::get('firstname');
                    $thisUser ->lastname = Input::get('lastname');
                    $thisUser ->studentID = Input::get('studentID');
                    $thisUser ->gender = Input::get('gender');
                    $thisUser ->phone = Input::get('phone');
                    $thisUser ->save();
                    break;

                case 2:

                    $thisUser = Employer::where('uid', '=', Auth::User() ->id) ->first();
                    $thisUser ->name = Input::get('name');
                    $thisUser ->EIN = Input::get('EIN'); // 公司統編
                    $thisUser ->email = Input::get('email'); // 人事部信箱
                    $thisUser ->phone = Input::get('phone'); // 人事部電話
                    $thisUser ->save();
                    break;

            }
            $ret ->stat = 1;
        }

        return json_encode($ret);
    }

    /****************
     * 回傳單筆徵才表
     * *************/
    public function getThisRecruitment($rid)
    {
        $ret = new \stdClass();
        $ret ->stat = 0;
        $thisRecruit = Recruitment::find($rid);

        if(Auth::check() and isset($thisRecruit))
        {
            $ret ->data = $thisRecruit;
            $ret ->stat = 1;
        }
        return json_encode($ret);
    }
}
