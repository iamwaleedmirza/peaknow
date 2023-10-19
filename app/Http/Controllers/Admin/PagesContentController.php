<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GeneralSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PagesContentController extends Controller
{

    public function pageView($slug)
    {
        if ($slug == 'privacy-policy') {
            $permissions = Controller::currentPermission();
            $content = GeneralSetting::select('privacy_policy_page')->first();
            $title = 'Privacy Policy';
            return view('admin.pages.privacy_policy', compact('content','permissions','title'));
        }
        if ($slug == 'terms-conditions') {
            $permissions = Controller::currentPermission();
            $content = GeneralSetting::select('terms_condition_page')->first();
            $title = 'Terms & Conditions';
            return view('admin.pages.terms_condition', compact('content','permissions','title'));
        }
        if ($slug == 'cookie-policy') {
            $permissions = Controller::currentPermission();
            $content = GeneralSetting::select('cookie_policy_page')->first();
            $title = 'Cookie Policy';
            return view('admin.pages.cookie_policy', compact('content','permissions','title'));
        }
        if ($slug == 'telehealth-consent') {
            $permissions = Controller::currentPermission();
            $content = GeneralSetting::select('telehealth_consent_page')->first();
            $title = 'Telehealth Consent';
            return view('admin.pages.telehealth-consent', compact('content','permissions','title'));
        }
        if ($slug == 'refund-policy') {
            $permissions = Controller::currentPermission();
            $content = GeneralSetting::select('refund_policy_page')->first();
            $title = 'Refund Policy';
            return view('admin.pages.refund-policy', compact('content','permissions','title'));
        }
    }

    public function contentUpdate(Request $request, $slug)
    {
        $setting = GeneralSetting::first();
        if ($setting) {
            if ($slug == 'privacy_policy') {
                $setting->update([
                    'privacy_policy_page' => $request->page_content,
                ]);
            } else if ($slug == 'terms_condition') {
                $setting->update([
                    'terms_condition_page' => $request->page_content,
                ]);
            } else if ($slug == 'cookie_policy') {
                $setting->update([
                    'cookie_policy_page' => $request->page_content,
                ]);
            } else if ($slug == 'telehealth_consent') {
                $setting->update([
                    'telehealth_consent_page' => $request->page_content,
                ]);
            } else if ($slug == 'refund_policy') {
                $setting->update([
                    'refund_policy_page' => $request->page_content,
                ]);
            }
        } else {
            if ($slug == 'privacy_policy') {
                GeneralSetting::create([
                    'privacy_policy_page' => $request->page_content,
                ]);
            } else if ($slug == 'terms_condition') {
                GeneralSetting::create([
                    'terms_condition_page' => $request->page_content,
                ]);
            } else if ($slug == 'cookie_policy') {
                GeneralSetting::create([
                    'cookie_policy_page' => $request->page_content,
                ]);
            } else if ($slug == 'telehealth_consent') {
                GeneralSetting::create([
                    'telehealth_consent_page' => $request->page_content,
                ]);
            } else if ($slug == 'refund_policy') {
                GeneralSetting::create([
                    'refund_policy_page' => $request->page_content,
                ]);
            }
        }
        Session::flash('success', 'Content Updated Successfully');
        return redirect()->back();
    }
}
