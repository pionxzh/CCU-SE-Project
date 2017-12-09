<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


/*********Hawa*********/
use Auth;
use App\User;
use App\Employer;
use App\Recruitment;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;


class EmployerController extends Controller
{


    /*********************************
     *  回傳所有該廠商發過得徵才資訊 *
     ********************************/

    public function getAllRecruitments()
    {
        if(Auth::check() and Auth::User() ->user_type === 2)
        {
            $ret = Recruitment::where('uid', '=', Auth::User() ->id);
            $ret ->stat = 1;
            return json_encode($ret);
        }
        else
        {
            $ret = new \stdClass();
            $ret ->stat = 0;
            return json_encode($ret);
        }
    }

    /**********************
     * 新增一筆徵才record *
     *********************/

    public function newRecruitment()
    {
        $ret = new \stdClass();
        if(Auth::check() and Auth::User() ->user_type === 2)
        {
            $newRecruit = new Recruitment;
            $newRecruit ->uid = Auth::User() ->id;
            $newRecruit ->is_complete = false;
            $newRecruit ->save();
            $ret ->stat = 1;
            $ret ->rid = $newRecruit ->id;
            return json_encode($ret);

        }
        else
        {
            $ret ->stat = 0;
            return json_encode($ret);
        }
    }

    /**********************
     * 檢查徵才表是否完整 *
     *********************/

    public function checkCompleteness(&$thisRecruit)
    {
        foreach($thisRecruit as $key => $val)
        {
            if(empty($val))
            {
                $thisRecruit ->is_complete = false;
                return;
            }
        }
        $thisRecruit ->is_complete = true;
    }



    /**********************
     *     檢查擁有者    *
     *********************/

    public function checkOwn(&$thisRecruit)
    {
        if(!empty($thisRecruit) and $thisRecruit ->uid === Auth::User() ->id)
        {
            return true;
        }
        else
        {
            return false;
        }
    }


    /**********************
     * 更新徵才表工作描述 *
     *********************/

    public function updateJobInfo()
    {
        $ret = new \stdClass();

        if(Auth::check() and Auth::User() ->user_type === 2)
        {
            $thisRecruit = Recruitment::where('id', '=', Input::get('rid')) ->first();
            if(!$this ->checkOwn($thisRecruit))
            {
                $ret ->stat = 0;
                return json_encode($ret);
            }
            $thisRecruit ->title = Input::get('');
            $thisRecruit ->jobname = Input::get('');
            $thisRecruit ->jobtype = Input::get('');
            $thisRecruit ->lang = Input::get('');
            $thisRecruit ->upay = Input::get('');
            $thisRecruit ->dpay = Input::get('');
            $thisRecruit ->jobinfo = Input::get('');
            $this ->checkCompleteness($thisRecruit);
            $thisRecruit ->save();
            $ret ->stat = 1;
            return json_encode($ret);
        }
        else
        {
            $ret ->stat = 0;
            return json_encode($ret);
        }
    }


    /**********************
     * 更新徵才表條件要求 *
     *********************/
    public function updateJobRequire()
    {
        $ret = new \stdClass();
        if(Auth::check() and Auth::User() ->user_type === 2)
        {
            $thisRecruit = Recruitment::where('id', '=', Input::get('rid')) ->first();
            if(!$this ->checkOwn($thisRecruit))
            {
                $ret ->stat = 0;
                return json_encode($ret);
            }
            $thisRecruit ->jobrequire = Input::get('');
            $this ->checkCompleteness($thisRecruit);
            $this ->save();
            $ret ->stat = 1;
            return json_encode($ret);
        }
        else
        {
            $ret ->stat = 0;
            return json_encode($ret);
        }
    }

    /**********************
     * 更新徵才表福利內容 *
     *********************/
    public function updateCompanyBenefits()
    {
        $ret = new \stdClass();
        if(Auth::check() and Auth::User() ->user_type === 2)
        {
            $thisRecruit = Recruitment::where('id', '=', Input::get('rid')) ->first();
            if(!$this ->checkOwn($thisRecruit))
            {
                $ret ->stat = 0;
                return json_encode($ret);
            }
            $thisRecruit ->benefits = Input::get('');
            $this ->checkCompleteness($thisRecruit);
            $this ->save();
            $ret ->stat = 1;
            return json_encode($ret);
        }
        else
        {
            $ret ->stat = 0;
            return json_encode($ret);
        }
    }


    /**********************
     * 更新徵才表聯繫方式 *
     *********************/
    public function updateCompanyContact()
    {
        $ret = new \stdClass();
        if(Auth::check() and Auth::User() ->user_type === 2)
        {
            $thisRecruit = Recruitment::where('id', '=', Input::get('rid')) ->first();
            if(!$this ->checkOwn($thisRecruit))
            {
                $ret ->stat = 0;
                return json_encode($ret);
            }
            $thisRecruit ->contact = Input::get('');
            $this ->checkCompleteness($thisRecruit);
            $this ->save();
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
