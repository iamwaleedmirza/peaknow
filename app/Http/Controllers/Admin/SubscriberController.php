<?php

namespace App\Http\Controllers\Admin;

use App\Models\ContactUs;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class SubscriberController extends Controller
{
    public function deletePopUpUser(Request $request)
    {
        $data = Subscription::find($request->id);
        $data->delete();
        return 1;
    }
    public function deleteContactUsUser(Request $request)
    {
        $data = ContactUs::find($request->id);
        $data->delete();
        return 1;
    }
}
