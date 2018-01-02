<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


/*********Hawa*********/
use Auth;
use App\User;
use App\Employee;
use App\Employer;
use App\Recruitment;
use App\Resume;
use App\Language;
use App\Matching;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;


class EmployerController extends Controller
{



    /***************************
     *  確認該廠商已被授權啟用 *
     ***************************/
    public function checkIfActive()
    {
        $thisUser = Employer::where('uid', '=', Auth::User() ->id) ->first();
        return (Auth::User() ->user_type === 2 and $thisUser ->is_active and $thisUser ->is_verify) ? true : false;
    }


    /*********************************
     *  回傳所有該廠商發過得徵才資訊 *
     ********************************/
    public function getAllRecruitments()
    {
        $ret = new \stdClass();
        $ret ->stat = 0;

        if(Auth::check() and Auth::User() ->user_type === 2 and $this ->checkIfActive())
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

        if(Auth::check() and Auth::User() ->user_type === 2 and $this ->checkIfActive())
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
        if(Auth::check() and isset($thisRecruit) and $thisRecruit ->uid === Auth::User() ->id and $this ->checkIfActive())
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
        if(Auth::check() and isset($thisRecruit) and $thisRecruit ->uid === Auth::User() ->id and $this ->checkIfActive())
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
        if(Auth::check() and isset($thisRecruit) and $thisRecruit ->uid === Auth::User() ->id and $this ->checkIfActive())
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
        if(Auth::check() and isset($thisRecruit) and $thisRecruit ->uid === Auth::User() ->id and $this ->checkIfActive())
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
        if(Auth::check() and isset($thisRecruit) and $thisRecruit ->uid === Auth::User() ->id and $this ->checkIfActive())
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
        $thisLang = Language::where('uid', '=', $uid) ->first();
        if(Auth::check() and Auth::User() ->user_type === 2 and isset($thisResume) and isset($thisLang) and $this ->checkIfActive())
        {
            // 回傳學生語言資訊
            unset($thisLang ->id);
            unset($thisLang ->uid);
            unset($thisLang ->updated_at);
            unset($thisLang ->created_at);
            $ret ->language = $thisLang;
            // 如果廠商與學生之間有配對關係, 則回傳完整資訊, 且stat = 2
            if(Matching::where([['cid', '=', Auth::User() ->id], ['uid', '=', $uid], ['employeeCheck', '=', 1]]) ->exists())
            {
                $ret ->data = $thisResume;
                $ret ->stat = 2;
            }
            // 如果廠商與學生之間沒有配對關係, 則回傳部份預設公開資訊, 且stat = 1
            else
            {
                // erase 隱藏欄位
                unset($thisResume ->lastName);
                unset($thisResume ->birth);
                unset($thisResume ->phone);
                unset($thisResume ->email);
                $ret ->data = $thisResume;
                $ret ->stat = 1;
            }

        }
        // 駁回請求, stat = 0
        return json_encode($ret);
    }


    /**********************
     * 刪除指定的徵才表 *
     *********************/
    public function deleteThisRecruit($rid)
    {
        $ret = new \stdClass();
        $ret ->stat = 0;
        $thisRecruit = Recruitment::find($rid);
        if(Auth::check() and isset($thisRecruit) and $thisRecruit ->uid === Auth::User() ->id and $this ->checkIfActive())
        {
            $ret ->stat = 1;
            // 刪除相關配對紀錄
            Matching::where('rid', '=', $rid) ->delete();
            $thisRecruit ->delete();
        }
        return json_encode($ret);
    }


    /******************************************
     * 徵才配對,目前只能選擇一種語言且無程度之分 *
     ******************************************/
    public function getRecruitMatch()
    {
        $ret = new \stdClass();
        $ret ->stat = 0;
        $thisRecruit = Recruitment::find(Input::get('rid'));
        if(Auth::check() and isset($thisRecruit) and Schema::hasColumn('languages', $thisRecruit ->lang)
            and Auth::User() ->id === $thisRecruit ->uid and $this ->checkIfActive())
        {
            $ret ->stat = 1;
            // 配對
            $ret ->data = Resume::select('uid', 'lastName', 'gender', 'nation', 'background', 'birth') ->where([
                ['expectedJobName', '=', $thisRecruit ->jobname],
                ['salaryFrom', '<=', $thisRecruit ->dpay],
                ['salaryTo', '<=', $thisRecruit ->upay]]) ->get();

            // 篩選語言
            foreach($ret ->data as $offset => $resume)
            {
                $thisLang = Language::select('en', 'ch', 'fr', 'jp') ->where('uid', '=', $resume ->uid) ->first();
                if($thisLang ->{$thisRecruit ->lang} ===  0)
                {
                    // 如果不會的話
                    unset(($ret ->data)[$offset]);
                }
                else
                {
                    $ret ->data[$offset] ->lang = $thisLang;
                    $ret ->data[$offset] ->birth = floor((time() - strtotime($ret ->data[$offset] ->birth)) / 31556926);
                }
            }
        }
        return json_encode($ret);
    }

    /************
     * 寄送邀請 *
     ************/
    public function inviteThisEmployee($uid)
    {
        // given $uid & $rid
        $ret = new \stdClass();
        $ret ->stat = 0;
        $thisRecruit = Recruitment::find(Input::get('rid'));
        if(Auth::check() and isset($thisRecruit) and Auth::User() ->id === $thisRecruit ->uid and Employee::where('uid', '=', $uid) ->exists()
            and $this ->checkIfActive())
        {
            $ret ->stat = 1;
            if(!Matching::where([['rid', '=', Input::get('rid')], ['uid', '=', $uid]]) ->exists())
            {
                $newMatching = new Matching;
                $newMatching ->cid = Auth::User() ->id;
                $newMatching ->rid = Input::get('rid');
                $newMatching ->uid = $uid;
                $newMatching ->employerCheck = 1;
                $newMatching ->save();
            }
        }
        return json_encode($ret);
    }



    /*******************************
     * 查看單筆徵才廣告的徵才資訊 *
     ******************************/
    public function getRecruitHistory()
    {
        // given $rid
        $ret = new \stdClass();
        $ret ->stat = 0;
        $thisRecruit = Recruitment::find(Input::get('rid'));
        if(Auth::check() and isset($thisRecruit) and $thisRecruit ->uid === Auth::User() ->id and $this ->checkIfActive())
        {
            $ret ->stat = 1;
            $ret ->data = Matching::select('uid', 'employeeCheck', 'employerCheck', 'updated_at') ->where('rid', '=', Input::get('rid')) ->get();
            foreach($ret ->data as $key => $value)
            {
                $thisResume = Resume::where('uid', '=', $value ->uid) ->first();
                if(isset($thisResume)) 
                {
                    ($ret ->data)[$key] ->lastName = $thisResume ->lastName;
                    ($ret ->data)[$key] ->nation = $thisResume ->nation;
                    ($ret ->data)[$key] ->background = $thisResume ->background;
                    ($ret ->data)[$key] ->birth =  floor((time() - strtotime($thisResume ->birth)) / 31556926);
                    ($ret ->data)[$key] ->gender = $thisResume ->gender;
                }
            }
        }
        return json_encode($ret);
    }

}
