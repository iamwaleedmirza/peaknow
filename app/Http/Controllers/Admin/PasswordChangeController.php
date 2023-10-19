<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class PasswordChangeController extends Controller
{

    public function passwordView()
    {
        $permissions = Controller::currentPermission();
        $title = 'Change Password';
        return view('admin.pages.change_password',compact('permissions','title'));
    }

    public function updatePassword(Request $request)
    {
        
        $validator=Validator::make($request->all(), [
            'old_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed', 'regex:/[a-z]/', 'regex:/[A-Z]/', 'regex:/[0-9]/'],
        ]);
        if($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput()->with('warning', 'There was some error updating your password!');
        }

        $user = Auth::user();
        $user = User::where('id', $user->id)->first();
        $old_password = Hash::check($request->old_password, $user->password);
        if ($old_password) {
            $user->update([
                'password' => Hash::make($request->password),
            ]);
            Session::flash('success', 'Password changed successfully.');
        } else {
            Session::flash('warning', 'Your old password does not matched!.');
        }
       
        return redirect()->route('admin.change-password');
    }
}
