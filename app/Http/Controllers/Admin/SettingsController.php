<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Utils\FileUploadController;
use App\Models\ContactUs;
use App\Models\GeneralSetting;
use App\Models\Plans;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Response;
use DataTables;

class SettingsController extends Controller
{

    public function settingView()
    {
        $permissions = Controller::currentPermission();
        $setting = GeneralSetting::first();
        $title = 'General Setting';
        return view('admin.pages.general_settings', compact('setting','permissions','title'));
    }

    public function settingUpdate(Request $request)
    {
        $request->validate([
            'site_logo_light' => 'mimes:jpeg,png,jpg',
            'site_logo_dark' => 'mimes:jpeg,png,jpg',
            'site_favicon' => 'mimes:jpeg,png,jpg,ico',
            'site_title' => 'required|string',
            'support_mail' => 'required|email',
            'allowed_states' => 'required|string',
        ]);

        $strAllowedStates = '';
        if (isset($request->allowed_states)) {
            $allowedStates = explode(',', $request->allowed_states);

            foreach ($allowedStates as $key => $state) {
                if ($key == 0) {
                    $strAllowedStates = $strAllowedStates . trim($state);
                } else {
                    $strAllowedStates = $strAllowedStates . ',' . trim($state);
                }
            }
        }

        $setting = GeneralSetting::first();
        $document = new FileUploadController();
        if ($setting) {
            $light_logo = $setting->site_logo_light;
            $dark_logo = $setting->site_logo_dark;
            $favicon = $setting->site_favicon;

            if ($request->has('site_logo_light')) {
                $name = 'site_logo_light' . Auth::user()->id . '_' . uniqid() . '.' . $request->file('site_logo_light')->extension();
                $light_logo = $document->upload($request, $name, 'site_logo_light', 'site_setting');
            }
            if ($request->has('site_logo_dark')) {
                $name = 'site_logo_dark' . Auth::user()->id . '_' . uniqid() . '.' . $request->file('site_logo_dark')->extension();
                $dark_logo = $document->upload($request, $name, 'site_logo_dark', 'site_setting');
            }
            if ($request->has('site_favicon')) {
                $name = 'site_favicon' . Auth::user()->id . '_' . uniqid() . '.' . $request->file('site_favicon')->extension();
                $favicon = $document->upload($request, $name, 'site_favicon', 'site_setting');
            }
            $setting->update([
                'site_title' => $request->site_title,
                'site_logo_light' => $light_logo,
                'site_logo_dark' => $dark_logo,
                'site_favicon' => $favicon,
                'footer_text' => $request->footer_text,
                'facebook_link' => $request->facebook_link,
                'twitter_link' => $request->twitter_link,
                'instagram_link' => $request->instagram_link,
                'support_mail' => $request->support_mail,
                'allowed_states' => $strAllowedStates,
            ]);
        } else {
            $light_logo = '';
            $dark_logo = '';
            $favicon = '';
            if ($request->has('site_logo_light')) {
                $name = 'site_logo_light' . Auth::user()->id . '_' . uniqid() . '.' . $request->file('site_logo_light')->extension();
                $light_logo = $document->upload($request, $name, 'site_logo_light', 'site_setting');
            }
            if ($request->has('site_logo_dark')) {
                $name = 'site_logo_dark' . Auth::user()->id . '_' . uniqid() . '.' . $request->file('site_logo_dark')->extension();
                $dark_logo = $document->upload($request, $name, 'site_logo_dark', 'site_setting');
            }
            if ($request->has('site_favicon')) {
                $name = 'site_favicon' . Auth::user()->id . '_' . uniqid() . '.' . $request->file('site_favicon')->extension();
                $favicon = $document->upload($request, $name, 'site_favicon', 'site_setting');
            }
            GeneralSetting::create([
                'site_title' => $request->site_title,
                'site_logo_light' => $light_logo,
                'site_logo_dark' => $dark_logo,
                'site_favicon' => $favicon,
                'footer_text' => $request->footer_text,
                'facebook_link' => $request->facebook_link,
                'twitter_link' => $request->twitter_link,
                'instagram_link' => $request->instagram_link,
                'support_mail' => $request->support_mail,
                'allowed_states' => $strAllowedStates,
            ]);
        }
        Session::flash('success', 'Setting Updated Successfully');
        return redirect()->back();
    }
    public function shippingSettingView()
    {
        $permissions = Controller::currentPermission();
        $setting = GeneralSetting::first();
        $title = 'Shipping Setting';
        return view('admin.pages.shipping_settings', compact('setting','permissions','title'));
    }
    public function shippingSettingUpdate(Request $request)
    {
        $setting = GeneralSetting::first();

        $request->validate([
            'shipping_cost' => 'required|numeric'
        ]);
        $setting->update([
            'shipping_cost' => $request->shipping_cost
        ]);
        Session::flash('success', 'Shipping Setting Updated Successfully');
        return redirect()->back();
    }
    public function allSubscribers(Request $request)
    {
        $permissions = Controller::currentPermission();
        
        if($request->ajax()) {
            $subscribers = Subscription::select('email','agreement','created_at','id');
            if($request->kt_ecommerce_sales_flatpickr){
                $dateArray = explode(' to ', $request->kt_ecommerce_sales_flatpickr);
                if(count($dateArray)==2){
                    $subscribers = $subscribers->whereDate('created_at','>=',$dateArray[0])->whereDate('created_at','<=',$dateArray[1]);
                }
            }
            $subscribers = $subscribers->orderBy('created_at','desc')->get();
            return Datatables::of($subscribers)
                ->addIndexColumn()
                ->editColumn('agreement', function ($row) {
                    return ($row->agreement==1) ? 'Yes' : 'No';
                })
                ->editColumn('created_at', function ($row) {
                    return \Carbon\Carbon::parse($row->created_at)->format('m/d/Y : H:i');
                })
                ->addColumn('action', function ($row) use($permissions) {
                    $action = '';
                    if(in_array('admin.delete.subscribers',$permissions) || Auth::user()->u_type=='superadmin') {
                        $route = route('admin.delete.subscribers');
                        $action .= '<button data-token="'.csrf_token().'" data-type="deleteSub"
                                    data-id="'.$row->id.'" data-uri="'.$route.'"
                                    data-kt-user-filter="delete_user"
                                    class="btn btn-danger deleteSubscriber">Delete</button>';
                    }
                    return $action;
                })
                ->rawColumns(['action','agreement','created_at'])
                ->make(true);
        }
        $title = 'PopUp Subscriber';
        return view('admin.pages.subscribers', compact('permissions','title'));
    }

    public function contactUsData(Request $request)
    {
        $permissions = Controller::currentPermission();
        if($request->ajax()) {
            $contactUs = ContactUs::select('*');
            if($request->kt_ecommerce_sales_flatpickr){
                $dateArray = explode(' to ', $request->kt_ecommerce_sales_flatpickr);
                if(count($dateArray)==2){
                    $contactUs = $contactUs->whereDate('created_at','>=',$dateArray[0])->whereDate('created_at','<=',$dateArray[1]);
                }
            }
            $contactUs = $contactUs->orderBy('created_at','desc')->get();
            return Datatables::of($contactUs)
                ->addIndexColumn()
                ->addColumn('name', function ($row) {
                    return $row->first_name.' '.$row->last_name;
                })
                ->editColumn('created_at', function ($row) {
                    return \Carbon\Carbon::parse($row->created_at)->format('m/d/Y : H:i');
                })
                ->addColumn('action', function ($row) use($permissions) {
                    $route = route('admin.view.contact-us');
                    $delete = route('admin.delete.contact');
                    $action = '';
                    if(in_array('admin.view.contact-us',$permissions) || Auth::user()->u_type=='superadmin') {
                        $action .= '<div class="menu-item px-3">
                                    <a href="javascript:void(0)" class="menu-link px-3 view_subscriber" data-id="'.$row->id.'">
                                        View
                                    </a>
                                </div>';
                    }
                    if(in_array('admin.delete.contact',$permissions) || Auth::user()->u_type=='superadmin') {
                        $action .= '<div class="menu-item px-3">
                                    <a href="javascript:void(0)" class="menu-link px-3 deleteSubscriber" data-id="'.$row->id.'" >Delete</a>
                                </div>';
                    }
                    if($action==''){
                        return '-';
                    } else {
                        return '<a href="#" class="btn btn-light btn-active-light-primary btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" data-kt-menu-flip="top-end">
                                    Actions
                                    <span class="svg-icon svg-icon-5 m-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <polygon points="0 0 24 0 24 24 0 24"></polygon>
                                                <path d="M6.70710678,15.7071068 C6.31658249,16.0976311 5.68341751,16.0976311 5.29289322,15.7071068 C4.90236893,15.3165825 4.90236893,14.6834175 5.29289322,14.2928932 L11.2928932,8.29289322 C11.6714722,7.91431428 12.2810586,7.90106866 12.6757246,8.26284586 L18.6757246,13.7628459 C19.0828436,14.1360383 19.1103465,14.7686056 18.7371541,15.1757246 C18.3639617,15.5828436 17.7313944,15.6103465 17.3242754,15.2371541 L12.0300757,10.3841378 L6.70710678,15.7071068 Z" fill="currentColor" fill-rule="nonzero" transform="translate(12.000003, 11.999999) rotate(-180.000000) translate(-12.000003, -11.999999)"></path>
                                            </g>
                                        </svg>
                                    </span>
                                </a>
                                <!--begin::Menu-->
                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-125px py-4" data-kt-menu="true">
                                    '.$action.'
                                </div>';
                    }    
                    
                })
                ->rawColumns(['action','name','created_at'])
                ->make(true);
        }
        $title = 'Contact Us Subscribers';
        return view('admin.pages.contact-us-data', compact('permissions','title'));
    }

    public function viewcontactUsData(Request $request){
        $contactUs = ContactUs::where('id',$request->id)->first();
        if($contactUs){
            $contactUs->date = \Carbon\Carbon::parse($contactUs->created_at)->format('m/d/Y : H:i');
            return Response::json(['status'=>true,'info' => $contactUs], 200);    
        }
        return Response::json(['status'=>false,'message' => 'Invalid Data'], 200);    
    }

    public function BelugaSetting()
    {
        $permissions = Controller::currentPermission();
        $setting = GeneralSetting::first();
        $title = 'Beluga Setting';
        return view('admin.pages.beluga_setting', compact('setting','permissions','title'));
    }

    public function UpdateConsultationFee(Request $request)
    {
        $setting = GeneralSetting::first();
        $request->validate([
            'consultation_fee' => 'required|numeric'
        ]);
        $setting->update([
            'consultation_fee' => $request->consultation_fee
        ]);
        return response()->json([
            'status' => true,
            'message' => 'Consultation Fees Updated Successfully',
        ]);
    }
}
