<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


/*********Hawa*********/
use Auth;
use App\User;
use App\Employer;
use App\Recruitment;
use App\Resume;
use App\Language;
use App\Matching;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;


class EmployerController extends Controller
{



    /***************************
     *  確認該廠商已被授權啟用 *
     ***************************/
    public function checkIfActive()
    {
        return (Auth::User() ->user_type === 2 and Auth::User() ->is_active and Auth::User() ->is_verify) ? true : false;
    }


    /*********************************
     *  回傳所有該廠商發過得徵才資訊 *
     ********************************/
    public function getAllRecruitments()
    {
        $ret = new \stdClass();
        $ret ->stat = 0;

        if(Auth::check() and Auth::User() ->user_type === 2 and checkIfActive())
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

        if(Auth::check() and Auth::User() ->user_type === 2 and checkIfActive())
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


    /**********************
     * 檢查徵才表是否完整 *
     *********************/
    public function updateJobField($rid)
    {
        $ret = new \stdClass();
        $ret ->stat = 0;

        $thisRecruit = Recruitment::where('id', '=', $rid) ->first();
        if(Auth::check() and isset($thisRecruit) and $thisRecruit ->uid === Auth::User() ->id and checkIfActive())
        {
            $ret ->stat = 1;
            // Variable Assignment
            $thisRecruit ->title = Input::get('title');
            $thisRecruit ->jobname = Input::get('jobname');
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
        if(Auth::check() and isset($thisRecruit) and $thisRecruit ->uid === Auth::User() ->id and checkIfActive())
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
        if(Auth::check() and isset($thisRecruit) and $thisRecruit ->uid === Auth::User() ->id and checkIfActive())
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
        if(Auth::check() and isset($thisRecruit) and $thisRecruit ->uid === Auth::User() ->id and checkIfActive())
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
        if(Auth::check() and isset($thisRecruit) and $thisRecruit ->uid === Auth::User() ->id and checkIfActive())
        {
            $ret ->stat = 1;
            $thisRecruit ->contact = Input::get('data');
            $thisRecruit ->save();
            $this ->checkCompleteness($thisRecruit ->id);
            $ret ->is_complete = Recruitment::where('id', '=', $rid) ->first() ->is_complete;
        }
        return json_encode($ret);
    }



    /**********************
     * 回傳指定學生的履歷表 *
     *********************/
    public function getThisResume($uid)
    {
        $ret = new \stdClass();
        $ret ->stat = 0;
        $thisResume = Resume::where('uid', '=', $uid) ->first();
        if(Auth::check() and Auth::User() ->user_type === 2 and isset($thisResume) and checkIfActive())
        {
            // 如果廠商與學生之間有配對關係, 則回傳完整資訊, 且stat = 2
            if(Mathing::where([['cid', '=', Auth::User() ->id], ['uid', '=', $uid]]) ->exists())
            {
                $ret ->data = $thisResume;
                $ret ->stat = 2;
            }
            // 如果廠商與學生之間沒有配對關係, 則回傳部份預設公開資訊, 且stat = 1
            else
            {
                // erase 隱藏欄位
                $thisResume ->forget('lastname');
                $thisResume ->forget('birth');
                $thisResume ->forget('phone');
                $thisResume ->forget('email');
                $ret ->data = $thisResume;
                $ret ->stat = 1;
            }

        }
        // 駁回請求, stat = 0
        return json_encode($ret);
    }

}
