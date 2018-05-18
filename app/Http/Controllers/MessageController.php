<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Exceptions\JsonReturnException;
use Illuminate\Support\Facades\Storage;

class MessageController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function privateLetter(Request $request){
        $request = $request->all();
        $user = Auth::user();
        $from_user =  DB::table('users')->where('id',$request['user_id'])->first();
        if($_SERVER['REQUEST_METHOD'] == 'GET'){
            //把此用户发给自己的信息标为已读
            DB::table('messages')->where([['to_user_id','=',$user->id],['status','=',0],
                    ['from_user_id','=',$request['user_id']]])
                ->update(['updated_at'=>date('Y-m-d H:i:s'),'status'=>1]);

            $data = DB::table('messages')->where([['from_user_id','=',$user->id],
                ['to_user_id','=',$request['user_id']]])
                ->orWhere([['to_user_id','=',$user->id],
                    ['from_user_id','=',$request['user_id']]])
                ->orderBy('created_at', 'desc')->get();
            return view('private_letter',['user'=>$user,'data'=>$data,'from_user'=>$from_user]);
        }
        else{
            if(!$request['message']){
                return response(['code'=>101,'message'=>'不能发送空信息']);
            }
            if($request['user_id'] == $user->id){
                return response(['code'=>101,'message'=>'自己给自己发信息不好玩']);
            }
            DB::table('messages')->insert(['to_user_id'=>$request['user_id'],
                'from_user_id'=>Auth::id(),'context'=>$request['message']
                ,'created_at'=>date('Y-m-d H:i:s')]);
            return response(['code'=>0,'message'=>'发送成功！']);
        }
    }

    public function privateLetterList(){
        $user = Auth::user();
        $to = DB::table('messages')->where('to_user_id',$user->id)
            ->orderBy('created_at', 'desc')->groupBy('from_user_id')->pluck('from_user_id');
        $to = $to->all();
        $from = DB::table('messages')->where('from_user_id',$user->id)
            ->orderBy('created_at', 'desc')->groupBy('to_user_id')->pluck('to_user_id');
        $from = $from->all();
        foreach ($from as $value){
            if(!in_array($value,$to)){
                $to[] = $value;
            }
        }
        $result = [];
        foreach ($to as $key=>$value){
            $lass_message =  DB::table('messages')->where([['from_user_id','=',$user->id],
                ['to_user_id','=',$value]])
                ->orWhere([['to_user_id','=',$user->id],
                    ['from_user_id','=',$value]])
                ->orderBy('created_at', 'desc')->first();
            $result[$key]['id'] = $value;
            $result[$key]['last_message'] = $lass_message->context;
            $result[$key]['user_name'] = DB::table('users')->where('id',$value)->value('name');
            $result[$key]['last_time'] = $lass_message->created_at;
            $result[$key]['wd_number'] = DB::table('messages')->where([['to_user_id','=',$user->id],
                    ['from_user_id','=',$value]])->where('status',0)->count();
        }
        return view('private_letter_list',['user'=>$user,'data'=>$result]);
    }

}
