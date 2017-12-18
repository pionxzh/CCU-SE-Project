<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**********Hawa**********/
use Auth;
use App\User;
use App\Employee;
use App\Employer;
use App\Recruitment;
use App\Resume;
use App\Language;
use App\Matching;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Collection;

class EmployeeController extends Controller
{


    /**************************
     * 確認學生帳號已授權啟用 *
     **************************/
    public function checkIfActive()
    {
        $thisUser = Employee::where('uid', '=', Auth::User() ->id) ->first();
        return (Auth::User() ->user_type === 1 and $thisUser ->is_active) ? true : false;
    }



    /**********************
     * 回傳外籍生寫的履歷 *
     **********************/
    public function getUserResume()
    {
        $ret = new \stdClass();
        $ret ->stat = 0;

        if(Auth::check() and Auth::User() ->user_type === 1 and $this ->checkIfActive())
        {
            $ret ->stat = 1;
            $ret ->data = Resume::where('uid', '=', Auth::User() ->id) ->first();
            $thisLanguage = Language::where('uid', '=', Auth::User() ->id) -> first();
            unset($thisLanguage ->id);
            unset($thisLanguage ->uid);
            unset($thisLanguage ->created_at);
            unset($thisLanguage ->updated_at);
            $ret ->language = $thisLanguage;
            //$ret ->language = Language::where('uid', '=', Auth::User() ->id) ->first();
        }
        return json_encode($ret);
    }


    /*******************
     *   更新基本資料  *
     ******************/
    public function updateBasic()
    {
        $ret = new \stdClass();
        $ret ->stat = 0;

        if(Auth::check() and Auth::User() ->user_type === 1 and $this ->checkIfActive())
        {
            $ret ->stat = 1;
            $thisResume = Resume::where('uid', '=', Auth::User() ->id) ->first();
            $thisResume ->firstName = Input::get('firstName');
            $thisResume ->lastName = Input::get('lastName');
            $thisResume ->gender = Input::get('gender');
            $thisResume ->birth = Input::get('birth');
            $thisResume ->nation = Input::get('nation');
            $thisResume ->email = Input::get('email');
            $thisResume ->phone = Input::get('phone');
            $thisResume ->save();
        }
        return json_encode($ret);
    }


    /*****************
     *   更新求職條件     *
     ******************/
    public function updateCondition()
    {
        $ret = new \stdClass();
        $ret ->stat = 0;

        if(Auth::check() and Auth::User() ->user_type === 1 and $this ->checkIfActive())
        {
            $ret ->stat = 1;
            $thisResume = Resume::where('uid', '=', Auth::User() ->id) ->first();
            $thisResume ->expectedJobName = Input::get('expectedJobName');
            $thisResume ->salaryFrom = Input::get('salaryFrom');
            $thisResume ->salaryTo = Input::get('salaryTo');
            $thisResume ->save();
        }
        return json_encode($ret);
    }


    /******************
     *    更新自傳    *
     *****************/
    public function updateBio()
    {
        $ret = new \stdClass();
        $ret ->stat = 0;
        if(Auth::check() and Auth::User() ->user_type === 1 and $this ->checkIfActive())
        {
            $ret ->stat = 1;
            $thisResume = Resume::where('uid', '=', Auth::User() ->id) ->first();
            $thisResume ->bio = Input::get('data');
            $thisResume ->save();
        }
        return json_encode($ret);
    }


    /******************
     *   更新學歷經驗 *
     ******************/
    public function updateBackground()
    {
        $ret = new \stdClass();
        $ret ->stat = 0;

        if(Auth::check() and Auth::User() ->user_type === 1 and $this ->checkIfActive())
        {
            $ret ->stat = 1;
            $thisResume = Resume::where('uid', '=', Auth::User() ->id) ->first();
            $thisResume ->background = Input::get('data');
            $thisResume ->save();
        }
        return json_encode($ret);
    }


    /***************
     *   更新技能  *
     ***************/
    public function updateSkill()
    {
        $ret = new \stdClass();
        $ret ->stat = 0;

        if(Auth::check() and Auth::User() ->user_type === 1 and $this ->checkIfActive())
        {
            $ret ->stat = 1;
            $thisResume = Resume::where('uid', '=', Auth::User() ->id) ->first();
            $thisResume ->skill = Input::get('data');
            $thisResume ->save();
        }
        return json_encode($ret);
    }


    /***************
     *   更新證照  *
     ***************/
    public function updateCertificate()
    {
        $ret = new \stdClass();
        $ret ->stat = 0;

        if(Auth::check() and Auth::User() ->user_type === 1 and $this ->checkIfActive())
        {
            $ret ->stat = 1;
            $thisResume = Resume::where('uid', '=', Auth::User() ->id) ->first();
            $thisResume ->certificate = Input::get('data');
            $thisResume ->save();
        }
        return json_encode($ret);
    }


    /*************
     *  更新語言 *
     *************/
    public function updateLanguage()
    {
        $ret = new \stdClass();
        $ret ->stat = 0;
        if(Auth::check() and Auth::User() ->user_type === 1 and $this ->checkIfActive())
        {
            $thisLanguage = Language::where('uid', '=', Auth::User() ->id) ->first();

            foreach(Input::get('data') as $key => $value)
            {
                if(!Schema::hasColumn('languages', $key))
                {
                    // 惡意偵測
                    return $ret;
                }
                $thisLanguage ->{$key} = $value;
            }
            $thisLanguage ->save();
        }
        $ret ->stat = 1;
        return json_encode($ret);
    }


    /************
     * 配對職缺 *
     * *********/
    public function getResumeMatch()
    {
        
        $ret = new \stdClass();
        $ret ->stat = 0;
        $thisResume = Resume::where('uid', '=', Auth::User() ->id) ->first();
        $thisLang = Language::where('uid', '=', Auth::User() ->id) ->first();
        
        if(Auth::check() and Auth::User() ->user_type === 1 and isset($thisResume) and isset($thisLang) and $this ->checkIfActive())
        {
            $ret ->stat = 1;
            $ret ->data = Recruitment::where([['jobname', '=', $thisResume ->expectedJobName], ['dpay', '>=', $thisResume ->salaryFrom],
                ['upay', '>=', $thisResume ->salaryTo]]) ->get();
            foreach($ret ->data as $offset => $recruit)
            {
                if($thisLang ->{$recruit ->lang} === 0)
                {
                    unset($ret ->data ->$offset);
                }
            }
        }
        return json_encode($ret);
    }


    /************
     * 投擲履歷 *
     * *********/
    public function throwThisRecruitment()
    {
        $ret = new \stdClass();
        $ret ->stat = 0;
        $thisRecruit = Recruitment::find(Input::get('rid'));
        // given $rid;
        if(Auth::check() and Auth::User() ->user_type === 1 and isset($thisRecruit) and $this ->checkIfActive())
        {
            $ret ->stat = 1;
            $thisMatch = Matching::where([['uid', '=', Auth::User() ->id], ['rid', '=', Input::get('rid')]]) ->first();
            if(isset($thisMatch))
            {
                $thisMatch ->employeeCheck = 1;
                $thisMatch ->save();
            }
            else
            {
                $newMatch = new Matching;
                $newMatch ->cid = $thisRecruit ->uid;
                $newMatch ->rid = $thisRecruit ->id;
                $newMatch ->uid = Auth::User() ->id;
                $newMatch ->employeeCheck = 1;
                $newMatch ->employerCheck = 1;
                $newMatch ->save();
            }
        }
        return json_encode($ret);
    }


    /************************
     * 查看學生履歷投擲歷史 *
     ***********************/
    public function getResumeHistory()
    {
        $ret = new \stdClass();
        $ret ->stat = 0;

        if(Auth::check() and Auth::User() ->user_type === 1 and $this ->checkIfActive())
        {
            $ret ->stat = 1;
            $ret ->data = Matching::where('uid', '=', Auth::User() ->id) ->get();
            foreach($ret ->data as $offset => $recruit)
            {
                
                $thisRecruit = Recruitment::find($recruit ->rid);
                if(isset($thisRecruit)) // just in case 
                {
                    (($ret ->data)[$offset]) ->title = $thisRecruit ->title;
                    (($ret ->data)[$offset]) ->jobname = $thisRecruit ->jobname;
                    (($ret ->data)[$offset]) ->dpay = $thisRecruit ->dpay;
                    (($ret ->data)[$offset]) ->upay = $thisRecruit ->upay;
                }    
            }
        }
        return json_encode($ret);
    }


}
