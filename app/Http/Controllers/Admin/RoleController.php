<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use DataTables;
use App\Http\Requests\StorePermissionRequest;
use Auth;

class RoleController extends Controller
{
    public function __construct() {
      
    }
    public function index(Request $request)
    {
        $permissions = Controller::currentPermission();
        if ($request->ajax()) {
            $data = Role::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $edit = url('/admin/').'/role/edit/'.$row->id;
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
                                    <a href="'.$edit.'" class="menu-link px-3" data-kt-docs-table-filter="edit_row">
                                        Edit
                                    </a>
                                </div>

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
                ->editColumn('created_at', function ($row) {
                    return \Carbon\Carbon::parse($row->created_at)->format('m/d/Y : H:i');;
                })
                ->editColumn('updated_at', function ($row) {
                    return \Carbon\Carbon::parse($row->updated_at)->format('m/d/Y : H:i');;
                })
                ->rawColumns(['action','created_at','updated_at'])
                ->make(true);
        }
        $title = 'User Roles List';
        $data = Permission::oldest('title')->get();
        return view('admin.pages.role.index',compact('data','permissions','title'));
    }

    public function create(Request $request)
    {
        $permissions = Controller::currentPermission();
        $data = Permission::oldest('title')->get();
        $array = [];
        $permission_for  = getPermission();
        foreach ($permission_for as $permission_type) {
            $newdata['id'] = $permission_type['id'];
            $newdata['name'] = $permission_type['name'];
            $groupData = Permission::where('permission_for',$permission_type['id'])->groupBy('module_name')->get();
            $modules = [];
            // print_r($groupData);exit;
            foreach ($groupData as $moduleData) {
                $newdatas = $moduleData;
                $module_related_data = Permission::where('permission_for',$permission_type['id'])->where('module_name',$moduleData->module_name)->orderBy('title','asc')->get();
                $newdatas['module_sub_data'] = $module_related_data;
                $modules[] = $newdatas;       
            }
            $newdata['module'] = $modules;
            $array[] = $newdata;
        }
        $title = 'User Roles List';
        return view('admin.pages.role.create',compact('data','permissions','array','title'));
    }

    public function store(Request $request)
    {   
        try {
            $checkRole = Role::where('id','!=',$request->id)->where('name',$request->name)->count();
            if($checkRole>0){
                return response()->json(['status' => false,'message' => 'Role name already exists in our syste,']);
            } else {
                if($request->id==0){
                    $role = Role::create(['name' => $request->get('name')]);
                    $role->syncPermissions($request->get('permission'));
                    return response()->json(['status' => true,'message' => 'Role added successfully']);
                } else {
                    $role = Role::find($request->id);
                    if($role){
                        User::where('role_id',$request->id)->where('id','!=',Auth::user()->id)->update(['u_type'=>$role->name]);
                        $role->update($request->only('name'));
                        $role->syncPermissions($request->get('permission'));
                        return response()->json(['status' => true,'message' => 'Role updated successfully']);
                    } else {
                        return response()->json(['status' => false,'message' => 'Role not found']);
                    }
                }
            }
        } catch (\Throwable $th) {
            return response()->json(['status' => false,'message' => 'Something went wrong','errorMessage' => $th->getMessage()]);
        }
    }

    public function show($id)
    {
        try {
            $role = Role::find($id);
            if($role){
                $id = [];
                 $role->rolePermissions = $role->permissions;
                 foreach ($role->rolePermissions as $value) {
                     $id[] = $value->name;
                 }
                $permissions = $id;
                $data = Permission::oldest('title')->get();
                $array = [];
                $permission_for  = getPermission();
                foreach ($permission_for as $permission_type) {
                    $newdata['id'] = $permission_type['id'];
                    $newdata['name'] = $permission_type['name'];
                    $groupData = Permission::where('permission_for',$permission_type['id'])->groupBy('module_name')->get();
                    $modules = [];
                    // print_r($groupData);exit;
                    foreach ($groupData as $moduleData) {
                        $newdatas = $moduleData;
                        $module_related_data = Permission::where('permission_for',$permission_type['id'])->where('module_name',$moduleData->module_name)->orderBy('title','asc')->get();
                        $newdatas['module_sub_data'] = $module_related_data;
                        $modules[] = $newdatas;       
                    }
                    $newdata['module'] = $modules;
                    $array[] = $newdata;
                }
                $title = 'User Roles List';
                return view('admin.pages.role.create',compact('data','permissions','role','array','title'));
            } else {
                return response()->json(['status' => false,'message' => 'Invalida role']);
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
    public function DeleteRole(Request $request)
    {
        try {
            $data = Role::find($request->id);
            if($data){
                $checkroleExist = User::where('role_id',$request->id)->count();
                if($checkroleExist>0){
                    return response()->json(['status' => false,'message' => 'Oops, you are not able to delete user role. This role assign to another users']);
                } else {
                    Role::where('id',$request->id)->delete();
                    return response()->json(['status' => true,'message' => 'Role deleted successfully']);    
                }
                
            } else {
                return response()->json(['status' => false,'message' => 'Invalida user role']);
            }
            
        } catch (\Throwable $th) {
            return response()->json(['status' => false,'message' => 'Something went wrong','errorMessage' => $th->getMessage()]);
        }
    }
    
}
