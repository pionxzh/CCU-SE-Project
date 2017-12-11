<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


/*********Hawa*********/
use Auth;
use App\User;
use App\Employer;
use App\Recruitment;
use App\Matching;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;


class EmployerController extends Controller
{


     /**********************
     * 回傳單筆徵才表資料 *
     *********************/
    public function getThisRecruitment($rid)
    {
        $ret = new \stdClass();
        $ret ->stat = 0;
        $thisRecruit = Recruitment::find($rid);

        if(Auth::check() and Auth::User() ->id === $thisRecruit ->uid)
        {
            $ret ->data = $thisRecruit;
            $ret ->stat = 1;
        }
        return json_encode($ret);

    }


    /*********************************
     *  回傳所有該廠商發過得徵才資訊 *
     ********************************/
    public function getAllRecruitments()
    {
        $ret = new \stdClass();
        $ret ->stat = 0;

        if(Auth::check() and Auth::User() ->user_type === 2)
        {
            $ret ->data = Recruitment::where('uid', '=', Auth::User() ->id) ->get();
            $ret ->stat = 1;
        }
        return json_encode($ret);
    }





    /**********************
     * 新增一筆徵才record *
     *********************/
    public function newRecruitment()
    {
        $ret = new \stdClass();
        $ret ->stat = 0;

        if(Auth::check() and Auth::User() ->user_type === 2)
        {
            $newRecruit = new Recruitment;
            $newRecruit ->uid = Auth::User() ->id;
            $newRecruit ->save();
            $ret ->stat = 1;
            $ret ->rid = $newRecruit ->id;
        }
        return json_encode($ret);
    }






    /**********************
     * 檢查徵才表是否完整 *
     *********************/
    public function checkCompleteness($rid)
    {
        $thisRecruit = Recruitment::where('id', '=', $rid) ->first();
        foreach($thisRecruit ->toArray() as $key => $val)
        {
            if($key !== "is_complete" and !isset($val))
            {
                $thisRecruit ->is_complete = false;
                $thisRecruit ->save();
                return;
            }
        }
        $thisRecruit ->is_complete = true;
        $thisRecruit ->save();
    }



    public function updateJobField($rid)
    {
        $ret = new \stdClass();
        $ret ->stat = 0;

        $thisRecruit = Recruitment::where('id', '=', $rid) ->first();
        if(Auth::check() and isset($thisRecruit) and $thisRecruit ->uid === Auth::User() ->id)
        {
            $ret ->stat = 1;
            // Variable Assignment
            $thisRecruit ->title = Input::get('title');
            $thisRecruit ->jobname = Input::get('jobname');
            $thisRecruit ->jobtype = Input::get('jobtype');
            $thisRecruit ->lang = Input::get('lang');
            $thisRecruit ->upay = Input::get('upay');
            $thisRecruit ->dpay = Input::get('dpay');
            $thisRecruit ->save();
            // 檢查是否完整
            $this ->checkCompleteness($thisRecruit ->id);
            $ret ->is_complete = Recruitment::where('id', '=', $rid) ->first() ->is_complete;
        }
        return json_encode($ret);
    }




    /**********************
     * 更新徵才表工作描述 *
     *********************/
    public function updateJobInfo($rid)
    {
        $ret = new \stdClass();
        $ret ->stat = 0;

        $thisRecruit = Recruitment::where('id', '=', $rid) ->first();
        if(Auth::check() and isset($thisRecruit) and $thisRecruit ->uid === Auth::User() ->id)
        {
            $ret ->stat = 1;
            $thisRecruit ->jobinfo = Input::get('data');
            $thisRecruit ->save();
            $this ->checkCompleteness($thisRecruit ->id);
            $ret ->is_complete = Recruitment::where('id', '=', $rid) ->first() ->is_complete;
        }
        return json_encode($ret);
    }



    /**********************
     * 更新徵才表條件要求 *
     *********************/
    public function updateJobRequire($rid)
    {
        $ret = new \stdClass();
        $ret ->stat = 0;

        $thisRecruit = Recruitment::where('id', '=', $rid) ->first();
        if(Auth::check() and isset($thisRecruit) and $thisRecruit ->uid === Auth::User() ->id)
        {
            $ret ->stat = 1;
            $thisRecruit ->jobrequire = Input::get('data');
            $thisRecruit ->save();
            $this ->checkCompleteness($thisRecruit ->id);
            $ret ->is_complete = Recruitment::where('id', '=', $rid) ->first() ->is_complete;
        }
        return json_encode($ret);
    }





    /**********************
     * 更新徵才表福利內容 *
     *********************/
    public function updateCompanyBenefits($rid)
    {
        $ret = new \stdClass();
        $ret ->stat = 0;

        $thisRecruit = Recruitment::where('id', '=', $rid) ->first();
        if(Auth::check() and isset($thisRecruit) and $thisRecruit ->uid === Auth::User() ->id)
        {
            $ret ->stat = 1;
            $thisRecruit ->benefits = Input::get('data');
            $thisRecruit ->save();
            $this ->checkCompleteness($thisRecruit ->id);
            $ret ->is_complete = Recruitment::where('id', '=', $rid) ->first() ->is_complete;
        }
        return json_encode($ret);
    }






    /**********************
     * 更新徵才表聯繫方式 *
     *********************/
    public function updateCompanyContact($rid)
    {
        $ret = new \stdClass();
        $ret ->stat = 0;

        $thisRecruit = Recruitment::where('id', '=', $rid) ->first();
        if(Auth::check() and isset($thisRecruit) and $thisRecruit ->uid === Auth::User() ->id)
        {
            $ret ->stat = 1;
            $thisRecruit ->contact = Input::get('data');
            $thisRecruit ->save();
            $this ->checkCompleteness($thisRecruit ->id);
            $ret ->is_complete = Recruitment::where('id', '=', $rid) ->first() ->is_complete;
        }
        return json_encode($ret);
    }
}
