<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Exceptions\JsonReturnException;
use Illuminate\Support\Facades\Storage;

class LikeController extends Controller
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

    public function myLike(){
        $like = DB::table('likes')->where('user_id',Auth::id())->orderBy('created_at', 'desc')->get();
        foreach ($like as $key =>$value){
            $like[$key]->detail = DB::table($value->category)->where('id',$value->category_id)->first();
        }
        return view('my_like',['data'=>$like]);
    }
}
