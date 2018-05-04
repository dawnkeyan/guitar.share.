<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Exceptions\JsonReturnException;
use Illuminate\Support\Facades\Storage;

class CommentController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function myMessageComment(Request $request){
        $this->middleware('auth');
        $user = Auth::user();
        $request = $request->all();
        if($request['type'] == 'reply'){
            $comments = DB::table('comments')->where('be_user_id',$user->id)->orderBy('status', 'asc')
                ->orderBy('created_at', 'desc')->get();
        }
        else{
            if($user->is_super!=1) {
                throw new JsonReturnException('没有权限！',403);
            }
            $comments = DB::table('comments')->whereNull('be_user_id')->orderBy('status', 'asc')
                ->orderBy('created_at', 'desc')->get();
        }
        foreach ($comments as $key =>$value){
            $comments[$key]->detail = DB::table($value->category)->where('id',$value->category_id)->first();
            if(!$comments[$key]->detail){
                unset($comments[$key]);
                continue;
            }
            $comments[$key]->user_name = DB::table('users')->where('id',$value->user_id)->value('name');
        }
        return view('my_message_comment',['data'=>$comments,'user'=>$user, 'type'=>$request['type']]);
    }

    public function comment(Request $request){
        $this->validate($request, [
                'category' => 'required',
                'category_id' => 'required',
            ]);
        $user = Auth::user();
        $request = $request->all();
        if($_SERVER['REQUEST_METHOD'] == 'GET'){
            $detail  = DB::table($request['category'])->where('id', $request['category_id'])->first();
            $comment = DB::table('comments')->where([['category', '=', $request['category']],
                ['category_id', '=', $request['category_id']]])->orderBy('created_at', 'desc')->get();
            foreach ($comment as $key=>$value){
                $comment[$key]->user_name = DB::table('users')->where('id', $value->user_id)->value('name');
                if($value->be_user_id){
                    $comment[$key]->be_user_name = DB::table('users')->where('id', $value->be_user_id)->value('name');

                }
            }
            return view('comment',['data'=>$detail,'user'=>$user,'comment'=>$comment]);
        }
        else{
            if($user->id == $request['be_user_id']){
                return response(['code'=>100,'message'=>'自己回复自己不好玩']);
            }
            DB::table('comments')->insert(['category'=>$request['category'],'category_id'=>$request['category_id'],
                'user_id'=>Auth::id(),'context'=>$request['context'],'be_user_id'=>$request['be_user_id'],
                'created_at'=>date('Y-m-d H:i:s')]);
            return response(['code'=>0,'message'=>'评论成功']);
        }
    }
}
