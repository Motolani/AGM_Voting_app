<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    //
    public function adminDashboard()
    {
        return view('admin/dashboard');
    }
    
    public function registerView()
    {
        # code...
        return view('admin/auth/registerAdmin');
    }
    public function register(Request $request)
    {
        # code...
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->is_admin = 1;
        $saved = $user->save();
        if($saved){
            $admin = new Admin();
            $admin->name = $request->name;
            $admin->email = $request->email;
            $admin->password = Hash::make($request->password);
            $admin->user_id = $user->id;
            $saved2 = $admin->save();
            
            if($saved2){
                
                return redirect()->back()->with('success', 'admin successfully created');
            }else{
                return redirect()->back()->with('error', 'failed to create admin');
            }
        }else{
            return redirect()->back()->with('error', 'failed to create admin user');
        }
        
    }
    
    public function loginView()
    {
        # code...
        return view('admin/auth/login');
    }
    
    public function viewShareholders()
    {
        # code...
        $shareholders = User::where('is_admin', 0)->get();
        return view('admin/shareholders/view', compact('shareholders'));
    }
    
    public function createShareholder()
    {
        # code...
        return view('admin/shareholders/create');
    }
    
    public function createShareholderPost(Request $request)
    {
        # code...
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'shares' => ['required', 'integer'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->shares = $request->shares;
        $user->password = Hash::make($request->password);
        $saved = $user->save();
        
        if($saved){
                
            return redirect()->away('home')->with('success', 'admin successfully created');
        }else{
            return redirect()->back()->with('error', 'failed to create admin');
        }
    }
       
    public function dropShareholder(Request $request)
    {
        # code...
        $id = $request->id;
        // dd($id);
        $user = User::find($id);    
        $user->delete();
        
        return redirect()->back()->with('success', 'shareholder successfully deleted');
    }

}
