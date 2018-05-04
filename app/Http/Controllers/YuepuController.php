<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Exceptions\JsonReturnException;
use Illuminate\Support\Facades\Storage;

class YuepuController extends Controller
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

    public function save(Request $request)
    {
        $data = $request->all();
        if(!Auth::id() && Auth::is_super()!=1){
            throw new JsonReturnException('权限不够', 401);
        }

        if($_SERVER['REQUEST_METHOD'] == 'GET'){
            if(isset($data['id'])){
                $data = DB::table('yuepus')->where('id', $data['id'])->first();
                return view('save_yuepu',['data'=>$data]);
            }
            return view('save_yuepu',['data'=>[]]);
        }
        else{
            /*$this->validate($request, [
                'name' => 'required|unique:yuepus',
                'context' => 'required',
                'cover' => 'required|image',
                'file' => 'required|file',
            ]);*/
            //上传文件
            unset($data['_token']);
            $data['user_id'] = Auth::id();
            if($data['id']){
                $data['updated_at']= date('Y-m-d H:i:s');
                DB::table('yuepus')
                    ->where('id', $data['id'])
                    ->update($data);
            }
            else{
                $data['created_at']= date('Y-m-d H:i:s');
                unset($data['id']);
                DB::table('yuepus')->insert($data);
            }
            return redirect('/yuepu');
        }

    }

    public function comment(Request $request)
    {
        $this->validate($request, [
            'context' => 'required',
        ]);
        $comment = DB::table('comments')->where([['category','yuepus'],['category_id',
            $request->id],['user_id',Auth::id()]])->first();
        if($comment){
            DB::table('comments')
                ->where('id', $comment->id)
                ->update(['context'=>$request->context,'updated_at'=>date('Y-m-d H:i:s')]);
        }
        else{
            DB::table('comments')->insert(['category'=>'yuepus','category_id'=>
                $request->id,'user_id'=>Auth::id(),'context'=>$request->context
                ,'created_at'=>date('Y-m-d H:i:s')]);
        }
        return redirect('/yuepu');

    }

    public function delete(Request $request)
    {
        if(!Auth::id() && Auth::is_super()!=1){
            throw new JsonReturnException('权限不够', 401);
        }
        DB::table('yuepus')->where('id', $request->id)->delete();
        return redirect('/yuepu');
    }

    public function recommend(Request $request)
    {
        if(!Auth::id() && Auth::is_super()!=1){
            throw new JsonReturnException('权限不够', 401);
        }
        $data = DB::table('yuepus')->where('id', $request->id)->first();
        $data->is_recommend = $data->is_recommend == 1 ? 0 : 1;
        DB::table('yuepus')->where('id', $request->id)->update(['is_recommend'=>$data->is_recommend]);
        return redirect('/yuepu');
    }

    public function like(Request $request)
    {
        $like = DB::table('likes')->where([['category','yuepus'],['category_id',
            $request->id],['user_id',Auth::id()]])->first();
        if($like){
            DB::table('likes')->where('id',$like->id)->delete();
        }
        else{
            DB::table('likes')->insert(['category'=>'yuepus','category_id'=> $request->id,'user_id'=>Auth::id()
                ,'created_at'=>date('Y-m-d H:i:s')]);
        }
        return redirect('/yuepu');
    }
}
