<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class ChangePasswordController extends Controller
{
    public function getView()
    {
        if (auth()->user()->phone_verified == 0) {

            return redirect()->route('otp-verify');
        }

        if (auth()->user()->email_verified == 0) return redirect()->route('email-verify');

        return view('user.password.change-password');
    }

    public function changePassword(Request $request)
    {
        $user = User::where('id', Auth::user()->id)->first();
        if ($user->password == null) {
            $validator = Validator::make($request->all(), [
                'password' => ['required', 'string', 'min:8', 'confirmed', 'regex:/[a-z]/', 'regex:/[A-Z]/', 'regex:/[0-9]/'],
            ]);
        }else{
            $validator = Validator::make($request->all(), [
                'current_password' => ['required', 'string'],
                'password' => ['required', 'string', 'min:8', 'confirmed', 'regex:/[a-z]/', 'regex:/[A-Z]/', 'regex:/[0-9]/'],
            ]);
        }

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('warning', 'Something went wrong while updating your password.');
        }

        if ($user->password == null) {
            $old_password = true;
        }else{
            $old_password = Hash::check($request->current_password, $user->password);
        }

        if ($old_password) {
            $user->update([
                'password' => Hash::make($request->password),
            ]);

        } else {
            return redirect()->back()->with('warning', 'Your old password is incorrect.');
        }
        return redirect()->route('account-home')->with('success','Password has been updated successfully.');

    }
}
