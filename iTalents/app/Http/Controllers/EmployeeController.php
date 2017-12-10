<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**********Hawa**********/
use Auth;
use App\User;
use App\Employee;
use App\Resume;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;


//新增 修改 顯示 刪除
class EmployeeController extends Controller
{
    /**************************
     * 回傳外籍生寫的履歷 *
     **************************/
    
    public function getAllResumes()
    {
        $ret = new \stdClass();
        if(Auth::check() and Auth::User() ->user_type === 1)
        {
            $ret ->data = Resume::where('uid', '=', Auth::User() ->id);
            $ret ->stat = 1;   //回傳 登入狀態 1成功 0失敗
            return json_encode($ret);
        }
        else
        {
            $ret ->stat = 0;
            return json_encode($ret);
        }
    }

     /**********************
     *     檢查擁有者    *
     *********************/

    public function checkOwn(&$thisResume)
    {
        if(!empty($thisResume) and $thisResume ->uid === Auth::User() ->id)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /*****************
    *   更新基本資料     *
    ******************/

    public function updateBasic()
    {
        $ret = new \stdClass();
        if(Auth::check() and Auth::User() ->user_type === 1)
        {
            $thisResume = Resume::where('uid', '=', Auth::User() ->id) ->first();
            if(!$this ->checkOwn($thisResume))
            {
                $ret ->stat = 0;
                return json_encode($ret);
            }
            $thisResume ->firstName = Input::get('firstName');
            $thisResume ->lastName = Input::get('lastName');
            $thisResume ->pid = Input::get('pid');
            $thisResume ->gender = Input::get('gender');
            $thisResume ->birthday = Input::get('birthday');
            $thisResume ->nation = Input::get('nation');
            $thisResume ->email = Input::get('email');
            $thisResume ->cellphone = Input::get('cellphone');
            $thisResume ->address = Input::get('address');
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

    /*****************
    *   更新求職條件     *
    ******************/

    public function updateCondition()
    {
        $ret = new \stdClass();
        if(Auth::check() and Auth::User() ->user_type === 1)
        {
            $thisResume = Resume::where('uid', '=', Auth::User() ->id) ->first();
            if(!$this ->checkOwn($thisResume))
            {
                $ret ->stat = 0;
                return json_encode($ret);
            }
            $thisResume ->expectedJobName = Input::get('expectedJobName');
            $thisResume ->expectedJobType = Input::get('expectedJobType');
            $thisResume ->SalaryFrom = Input::get('SalaryFrom');
            $thisResume ->SalaryTo = Input::get('SalaryTo');
            $thisResume ->expectedJobDec = Input::get('expectedJobDec');
            $thisResume ->expectedJobCat = Input::get('expectedJobCat');
            $thisResume ->expectedJobArea = Input::get('expectedJobArea');
            $thisResume ->expectedJobTime = Input::get('expectedJobTime');
            $thisResume ->SalaryType = Input::get('SalaryType');
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

     /*****************
    *   更新學歷經驗     *
    ******************/

    public function updateBackground()
    {
        $ret = new \stdClass();
        if(Auth::check() and Auth::User() ->user_type === 1)
        {
            $thisResume = Resume::where('uid', '=', Auth::User() ->id) ->first();
            if(!$this ->checkOwn($thisResume))
            {
                $ret ->stat = 0;
                return json_encode($ret);
            }
            $thisResume ->background = Input::get('background');
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

     /*****************
    *   更新技能與證照     *
    ******************/

    public function updateSkill()
    {
        $ret = new \stdClass();
        if(Auth::check() and Auth::User() ->user_type === 1)
        {
            $thisResume = Resume::where('uid', '=', Auth::User() ->id) ->first();
            if(!$this ->checkOwn($thisResume))
            {
                $ret ->stat = 0;
                return json_encode($ret);
            }
            $thisResume ->cert = Input::get('cert');
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
    
 /*****************
    *   更新自傳     *
    ******************/

    public function updateAutobiography()
    {
        $ret = new \stdClass();
        if(Auth::check() and Auth::User() ->user_type === 1)
        {
            $thisResume = Resume::where('uid', '=', Auth::User() ->id) ->first();
            if(!$this ->checkOwn($thisResume))
            {
                $ret ->stat = 0;
                return json_encode($ret);
            }
            $thisResume ->bio = Input::get('bio');
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

        public function newLanguage()
        {
            $ret = new \stdClass();
            if(Auth::check() and Auth::User() ->user_type === 1)
            {
                $newLanguage = new Language;
                $newLanguage ->uid = Auth::User() ->id;
                foreach ($newLanguage as $language)
                {
                $language ->langCat = Input::get('langCat');
                $language ->langAbility = Input::get('langAbility');
                }
                $newLanguage ->save();
                $ret ->stat = 1;
                $ret ->rid = $newLanguage ->id;
                return json_encode($ret);
    
            }
            else
            {
                $ret ->stat = 0;
                return json_encode($ret);
            }
        }



}
