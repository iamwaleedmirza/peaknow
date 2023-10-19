<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use DataTables;
use Auth;
use App\Http\Requests\Admin\User\StoreUserRequest;

class AdminUserList extends Controller
{
    public function __construct() {
      
    }
    public function index(Request $request)
    {
        $permissions = Controller::currentPermission();
        $data = Role::latest()->get();
        if ($request->ajax()) {
            $user = User::where('u_type','!=','patient')->where('id','!=',Auth::user()->id)->latest()->get();
            return Datatables::of($user)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="#" class="btn btn-light btn-active-light-primary btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" data-kt-menu-flip="top-end">
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
                                <!--begin::Menu item-->
                                <div class="menu-item px-3">
                                    <a href="#" class="menu-link px-3 edit" data-id="'.$row->id.'" data-kt-docs-table-filter="edit_row">
                                        Edit
                                    </a>
                                </div>
                                <div class="menu-item px-3">
                                    <a href="#" class="menu-link px-3 change_password" data-id="'.$row->id.'" data-kt-docs-table-filter="edit_row">
                                        Change Password
                                    </a>
                                </div>
                                <!--end::Menu item-->

                                <!--begin::Menu item-->
                                <div class="menu-item px-3">
                                    <a href="#" class="menu-link px-3 delete" data-kt-docs-table-filter="delete_row" data-id="'.$row->id.'">
                                        Delete
                                    </a>
                                </div>
                                <!--end::Menu item-->
                            </div>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        $title = 'Users List';
        return view('admin.pages.admin_user.index',compact('data','permissions','title'));
    }

    public function store(StoreUserRequest $request)
    {   
        try {
            $checkRole = User::where('id','!=',$request->id)->where('email',$request->email)->count();
            if($checkRole>0){
                return response()->json(['status' => false,'message' => 'This email is already exists in our system. Please use another email address.']);
            } else {
                $data = $request->all();
                if($data['password']!=''){
                    $data['password']  = Hash::make($data['password']);
                } else {
                    unset($data['password']);
                }   
                unset($data['confirm_password']);
                
                $data['u_type'] = Role::where('id',$request->role_id)->first()->name;
                if($request->id==0){
                    $data['phone_verified'] = 1;
                    $data['email_verified'] = 1;
                    $data['email_verified_at'] = date('Y-m-d H:i:s');
                    User::create($data);
                    return response()->json(['status' => true,'message' => 'User added successfully']);
                } else {
                    $role = User::find($request->id);
                    if($role){
                        $role->update($data);
                        return response()->json(['status' => true,'message' => 'User updated successfully']);
                    } else {
                        return response()->json(['status' => false,'message' => 'Role not found']);
                    }
                }
            }
        } catch (\Throwable $th) {
            return response()->json(['status' => false,'message' => 'Something went wrong','errorMessage' => $th->getMessage()]);
        }
    }

    public function show(Request $request)
    {
        try {

            $data = User::find($request->id);
            if($data){
                return response()->json(['status' => true,'message' => '','data' => $data]);
            } else {
                return response()->json(['status' => false,'message' => 'Invalida user']);
            }
            
        } catch (\Throwable $th) {
            return response()->json(['status' => false,'message' => 'Something went wrong','errorMessage' => $th->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function DeletePermission(Request $request)
    {
        try {
            $data = User::find($request->id);
            if($data){
                User::where('id',$request->id)->delete();
                return response()->json(['status' => true,'message' => 'User deleted successfully']);    
            } else {
                return response()->json(['status' => false,'message' => 'Invalida user']);
            }
            
        } catch (\Throwable $th) {
            return response()->json(['status' => false,'message' => 'Something went wrong','errorMessage' => $th->getMessage()]);
        }
    }

    public function adminChangeCustomerPassword(Request $request){
        $request->validate([
            'user_id' => ['required'],
            'password' => ['required', 'string', 'min:8'],
        ]);
        $user = User::find($request->user_id);
        $user->password = Hash::make($request->password);
        $user->save();
        return Response::json(array(
            'status' => true,
            'message' => 'Password has been updated successfully'
    
        ), 200);
    }
    
}
