<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    $user = \Illuminate\Support\Facades\Auth::user();
    $yuepu = listData (DB::table('yuepus')->where('is_recommend',1)->orderBy('created_at', 'desc')->get(),$user,'yuepus');
    $video = listData (DB::table('videos')->where('is_recommend',1)->orderBy('created_at', 'desc')->get(),$user,'videos');

    return view('welcome', ['yuepus' => $yuepu,'videos'=>$video,'user'=>$user]);
});
Route::get('/my', function () {
    return view('my');
});
Route::get('/about', function () {
    return view('about');
});

Auth::routes();

Route::get('/yuepu', function (\Illuminate\Http\Request $request) {

    $user = \Illuminate\Support\Facades\Auth::user();
    $request = $request->all();
    $yuepu = listData (DB::table('yuepus')->where(changeQuery($request))->orderBy('created_at', 'desc')->get(),$user,'yuepus');
    return view('yuepu', ['yuepus' => $yuepu,'user'=>$user,
        'name'=>isset($request['name'])?$request['name']:'',
        'stat_time'=>isset($request['stat_time'])?$request['stat_time']:'',
        'end_time'=>isset($request['end_time'])?$request['end_time']:'',
    ]);
});
Route::get('/save_yuepu{id?}', 'YuepuController@save');
Route::post('/save_yuepu{id?}', 'YuepuController@save');
Route::get('/delete_yuepu{id?}', 'YuepuController@delete');
Route::get('/recommend_yuepu{id?}', 'YuepuController@recommend');
Route::get('/like_yuepus{id?}', 'YuepuController@like');
Route::post('/comment_yuepus{id?}', 'YuepuController@comment');

Route::get('/video', function (\Illuminate\Http\Request $request) {
    $user = \Illuminate\Support\Facades\Auth::user();
    $request = $request->all();
    $video = listData (DB::table('videos')->where(changeQuery($request))->orderBy('created_at', 'desc')->get(),$user,'videos');
    return view('video', ['videos' => $video,'user'=>$user,
        'name'=>isset($request['name'])?$request['name']:'',
        'stat_time'=>isset($request['stat_time'])?$request['stat_time']:'',
        'end_time'=>isset($request['end_time'])?$request['end_time']:'',
    ]);
});
Route::get('/save_video{id?}', 'VideoController@save');
Route::post('/save_video{id?}', 'VideoController@save');
Route::get('/delete_video{id?}', 'VideoController@delete');
Route::get('/recommend_video{id?}', 'VideoController@recommend');
Route::get('/like_videos{id?}', 'VideoController@like');
Route::post('/comment_videos{id?}', 'VideoController@comment');
Route::post('/upload', 'VideoController@upload');

function changeQuery($request){
    $where = [];
    if(!empty($request['name'])){
        $where[] = ['name','like','%'.$request['name'].'%'];
    }
    if(!empty($request['start_time'])){
        $where[] = ['created_at','>=',date('Y-m-d H:i:s',strtotime($request['start_time']))];
    }
    if(!empty($request['end_time'])){
        $where[] = ['created_at','<=',date('Y-m-d H:i:s',strtotime($request['end_time']))];
    }
    return $where;
}

function listData ($yuepu,$user,$table){
    if($user){
        foreach ($yuepu as $key=>$value){
            if(DB::table('likes')->where([['category',$table],['category_id',
                $value->id],['user_id',$user->id]])->first()){
                $yuepu[$key]->is_like = 1;
            }
            else{
                $yuepu[$key]->is_like = 0;
            }
        }
    }
    return $yuepu;
}

Route::post('/comment_reply{id?}', 'CommentController@commentReply');
Route::get('/my_message_comment{type?}', 'CommentController@myMessageComment');
Route::get('/my_like', 'LikeController@myLike');
Route::get('/user_info{id?}', 'UserController@save');
Route::get('/private_letter{user_id?}', 'MessageController@privateLetter');
Route::get('/private_list', 'MessageController@privateLetterList');
Route::post('/private_letter', 'MessageController@privateLetter');
Route::post('/user_info', 'UserController@save');
Route::match(['get', 'post'],'/comment/{category_id?}/{category?}', 'CommentController@comment');

Route::get('/change_status_comment{id?}', function (\Illuminate\Http\Request $request) {
    DB::table('comments')->where('id',$request->input('id'))->update(['status'=>1,
        'updated_at'=>date('Y-m-d H:i:s')]);
    return response(['code'=>0,'message'=>'操作成功！']);
});