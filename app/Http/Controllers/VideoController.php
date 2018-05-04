<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Exceptions\JsonReturnException;
use Illuminate\Support\Facades\Storage;

class VideoController extends Controller
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
        if(!Auth::id() && Auth::is_super()!=1){
            throw new JsonReturnException('权限不够', 401);
        }
        $data = $request->all();
        if($_SERVER['REQUEST_METHOD'] == 'GET'){
            if(isset($data['id'])){
                $data = DB::table('videos')->where('id', $data['id'])->first();
                return view('save_video',['data'=>$data]);
            }
            return view('save_video',['data'=>[]]);
        }
        else{
            /*$this->validate($request, [
                'name' => 'required|unique:videos',
                'context' => 'required',
                'cover' => 'required',
                'file' => 'required',
            ]);*/
            unset($data['_token']);
            $data['user_id'] = Auth::id();
            if($data['id']){
                $data['updated_at']= date('Y-m-d H:i:s');
                DB::table('videos')
                    ->where('id', $data['id'])
                    ->update($data);
            }
            else{
                unset($data['id']);
                $data['created_at']= date('Y-m-d H:i:s');
                DB::table('videos')->insert($data);
            }
            return redirect('/video');
        }
    }

    public function comment(Request $request)
    {
        $this->validate($request, [
            'context' => 'required',
        ]);
        $comment = DB::table('comments')->where([['category','videos'],['category_id',
            $request->id],['user_id',Auth::id()]])->first();
        if($comment){
            DB::table('comments')
                ->where('id', $comment->id)
                ->update(['context'=>$request->context,'updated_at'=>date('Y-m-d H:i:s')]);
        }
        else{
            DB::table('comments')->insert(['category'=>'videos','category_id'=>
                $request->id,'user_id'=>Auth::id(),'context'=>$request->context
                ,'created_at'=>date('Y-m-d H:i:s')]);
        }
        return redirect('/video');

    }

    public function delete(Request $request)
    {
        if(!Auth::id() && Auth::is_super()!=1){
            throw new JsonReturnException('权限不够', 401);
        }
        DB::table('videos')->where('id', $request->id)->delete();
        return redirect('/video');
    }

    public function recommend(Request $request)
    {
        if(!Auth::id() && Auth::is_super()!=1){
            throw new JsonReturnException('权限不够', 401);
        }
        $data = DB::table('videos')->where('id', $request->id)->first();
        $data->is_recommend = $data->is_recommend == 1 ? 0 : 1;
        DB::table('videos')->where('id', $request->id)->update(['is_recommend'=>$data->is_recommend]);
        return redirect('/video');
    }

    public function like(Request $request)
    {
        $like = DB::table('likes')->where([['category','videos'],['category_id',
            $request->id],['user_id',Auth::id()]])->first();
        if($like){
            DB::table('likes')->where('id',$like->id)->delete();
        }
        else{
            DB::table('likes')->insert(['category'=>'videos','category_id'=> $request->id,'user_id'=>Auth::id()
                ,'created_at'=>date('Y-m-d H:i:s')]);
        }
        return redirect('/video');
    }

    public function upload(Request $request){
        //获取文件的绝对路径，但是获取到的在本地不能打开
        $file = $request->file('file');
        $path = $file->getRealPath();

        //要保存的文件名 时间+扩展名
        $filename=date('Y-m-d-H-i-s') . '_' . uniqid() .'.'.$file->getClientOriginalExtension();
        //保存文件          配置文件存放文件的名字  ，文件名，路径
        Storage::disk('public')->put($filename,file_get_contents($path));
        $path =  '/storage/'.$filename;
        return response(['path'=>$path]);
    }
}
