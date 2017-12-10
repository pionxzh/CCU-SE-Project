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


     /**********************
     * 回傳單筆徵才表資料 *
     *********************/
    public function getThisRecruitment($rid)
    {
        $ret = new \stdClass();
        $thisRecruit = Recruitment::find($rid);
        if(Auth::check() and Auth::User() ->id === $thisRecruit ->uid)
        {
            $ret ->data = $thisRecruit;
            $ret ->stat = 1;
            return json_encode($ret);          
        }
        else
        {
            $ret ->stat = 0;
            return json_encode($ret);
        }

    }


    public function getAllRecruitments()
    {
        $ret = new \stdClass();
        if(Auth::check() and Auth::User() ->user_type === 2)
        {
            $ret ->data = Recruitment::where('uid', '=', Auth::User() ->id) ->get();
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

    public function checkCompleteness($rid)
    {
        $thisRecruit = Recruitment::where('id', '=', $rid) ->first();
        foreach($thisRecruit ->toArray() as $key => $val)
        {
            if($key !== "is_complete" and empty($val))
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
     *     檢查擁有者    *
     *********************/

    public function checkOwn($rid)
    {
        $thisRecruit = Recruitment::where('id', '=', $rid) -> first();
        return (!empty($thisRecruit) and $thisRecruit ->uid === Auth::User() ->id);
    }




    public function updateJobField($rid)
    {
        $ret = new \stdClass();

        if(Auth::check() and Auth::User() ->user_type === 2)
        {
            $thisRecruit = Recruitment::where('id', '=', $rid) ->first();
            if(!$this ->checkOwn($thisRecruit ->id))
            {
                $ret ->stat = 0;
                return json_encode($ret);
            }
            $thisRecruit ->title = Input::get('title');
            $thisRecruit ->jobname = Input::get('jobname');
            $thisRecruit ->jobtype = Input::get('jobtype');
            $thisRecruit ->lang = Input::get('lang');
            $thisRecruit ->upay = Input::get('upay');
            $thisRecruit ->dpay = Input::get('dpay');
            $thisRecruit ->save();
            $this ->checkCompleteness($thisRecruit ->id);
            $thisRecruit = Recruitment::where('id', '=', $rid) ->first();
            $ret ->stat = 1;
            $ret ->is_complete = $thisRecruit ->is_complete;
            return json_encode($ret);
        }
        else
        {
            $ret ->stat = 0;
            return json_encode($ret);
        }
    }


    /**********************
     * 更新徵才表工作描述 *
     *********************/

    public function updateJobInfo($rid)
    {
        $ret = new \stdClass();

        if(Auth::check() and Auth::User() ->user_type === 2)
        {
            $thisRecruit = Recruitment::where('id', '=', $rid) ->first();
            if(!$this ->checkOwn($thisRecruit ->id))
            {
                $ret ->stat = 0;
                return json_encode($ret);
            }
            
            $thisRecruit ->jobinfo = Input::get('data');
            $thisRecruit ->save();
            $this ->checkCompleteness($thisRecruit ->id);
            $thisRecruit = Recruitment::where('id', '=', $rid) ->first();
            $ret ->stat = 1;
            $ret ->is_complete = $thisRecruit ->is_complete;
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
    public function updateJobRequire($rid)
    {
        $ret = new \stdClass();
        if(Auth::check() and Auth::User() ->user_type === 2)
        {
            $thisRecruit = Recruitment::where('id', '=', $rid) ->first();
            if(!$this ->checkOwn($thisRecruit ->id))
            {
                $ret ->stat = 0;
                return json_encode($ret);
            }
            $thisRecruit ->jobrequire = Input::get('data');
            $thisRecruit ->save();
            $this ->checkCompleteness($thisRecruit ->id);
            $thisRecruit = Recruitment::where('id', '=', $rid) ->first();
            $ret ->stat = 1;
            $ret ->is_complete = $thisRecruit ->is_complete;
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
    public function updateCompanyBenefits($rid)
    {
        $ret = new \stdClass();
        if(Auth::check() and Auth::User() ->user_type === 2)
        {
            $thisRecruit = Recruitment::where('id', '=', $rid) ->first();
            if(!$this ->checkOwn($thisRecruit ->id))
            {
                $ret ->stat = 0;
                return json_encode($ret);
            }
            $thisRecruit ->benefits = Input::get('data');
            $thisRecruit ->save();
            $this ->checkCompleteness($thisRecruit ->id);
            $thisRecruit = Recruitment::where('id', '=', $rid) ->first();
            $ret ->stat = 1;
            $ret ->is_complete = $thisRecruit ->is_complete;
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
    public function updateCompanyContact($rid)
    {
        $ret = new \stdClass();
        if(Auth::check() and Auth::User() ->user_type === 2)
        {
            $thisRecruit = Recruitment::where('id', '=',$rid) ->first();
            if(!$this ->checkOwn($thisRecruit ->id))
            {
                $ret ->stat = 0;
                return json_encode($ret);
            }
            $thisRecruit ->contact = Input::get('data');
            $thisRecruit ->save();
            $this ->checkCompleteness($thisRecruit ->id);
            $thisRecruit = Recruitment::where('id', '=', $rid) ->first();
            $ret ->stat = 1;
            $ret ->is_complete = $thisRecruit ->is_complete;
            return json_encode($ret);
        }
        else
        {
            $ret ->stat = 0;
            return json_encode($ret);
        }
    }
}
