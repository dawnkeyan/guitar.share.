<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Exceptions\JsonReturnException;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
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
        if($_SERVER['REQUEST_METHOD'] == 'GET'){
            $user = Auth::user();
            if(!empty($data['id'])){
                $data = DB::table('users')->where('id', $data['id'])->first();
            }
            else{
                $data = DB::table('users')->where('id', Auth::id())->first();
            }
            return view('user_info',['data'=>$data,'user'=>$user]);
        }
        else{
            $data['updated_at']= date('Y-m-d H:i:s');
            unset($data['_token']);
            DB::table('users')
                ->where('id', Auth::id())
                ->update($data);
            return redirect('/user_info');
        }
    }
}
