<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**********Hawa**********/
use Auth;
use App\User;
use App\Employee;
use App\Resume;
use App\Language;
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
            $ret ->data = Resume::where('uid', '=', Auth::User() ->id) ->first();
            $ret ->language = Language::where('uid', '=', Auth::User() ->id) ->get();
            $ret ->stat = 1;   //回傳 登入狀態 1成功 0失敗
            return json_encode($ret);
        }
        else
        {
            $ret ->stat = 0;
            return json_encode($ret);
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
            $thisResume ->firstName = Input::get('firstName');
            $thisResume ->lastName = Input::get('lastName');
            $thisResume ->pid = Input::get('pid');
            $thisResume ->gender = Input::get('gender');
            $thisResume ->birthday = Input::get('birthday');
            $thisResume ->nation = Input::get('nation');
            $thisResume ->email = Input::get('email');
            $thisResume ->cellphone = Input::get('cellphone');
            $thisResume ->address = Input::get('address');
            $thisResume ->save();
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
           $thisResume ->background = Input::get('data');
           $thisResume ->save();
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
   *   更新語言     *
   ******************/

   public function updateLanguage(Request $request)
   {
        $ret = new \stdClass();
        if(Auth::check() and Auth::User() ->user_type === 1)
        {
            DB::table('languages') ->whereIn('uid', Auth::User() ->id) -> delete();

            foreach($request->data as $key => $value)
            {
              $language = new Language;
              $language ->uid = Auth::User() ->id;
              $language ->language = $key;
              $language ->langAbility = $value;
              $language ->save();
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

    /*****************
    *   更新求職條件     *
    ******************/

    public function updateCondition()
    {
        $ret = new \stdClass();
        if(Auth::check() and Auth::User() ->user_type === 1)
        {
            $thisResume = Resume::where('uid', '=', Auth::User() ->id) ->first();
            $thisResume ->expectedJobName = Input::get('expectedJobName');
            $thisResume ->expectedJobType = Input::get('expectedJobType');
            $thisResume ->salaryFrom = Input::get('salaryFrom');
            $thisResume ->salaryTo = Input::get('salaryTo');
            $thisResume ->expectedJobDec = Input::get('expectedJobDec');
            $thisResume ->expectedJobCat = Input::get('expectedJobCat');
            $thisResume ->expectedJobArea = Input::get('expectedJobArea');
            $thisResume ->expectedJobTime = Input::get('expectedJobTime');
            $thisResume ->salaryType = Input::get('salaryType');
            $thisResume ->save();
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
            $thisResume ->skill = Input::get('data');
            $thisResume ->save();
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

    public function updateBio()
    {
        $ret = new \stdClass();
        if(Auth::check() and Auth::User() ->user_type === 1)
        {
            $thisResume = Resume::where('uid', '=', Auth::User() ->id) ->first();
            $thisResume ->bio = Input::get('data');
            $thisResume ->save();
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
