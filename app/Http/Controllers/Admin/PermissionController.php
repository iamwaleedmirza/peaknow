<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use DataTables;
use App\Http\Requests\Admin\Permission\StorePermissionRequest;

class PermissionController extends Controller
{
    public function __construct() {
      
    }
    public function index(Request $request)
    {
        $permissions = Controller::currentPermission();
        $permission_for  = getPermission();
        $title = 'Permissions List';
        if ($request->ajax()) {
            $permission_data = getPermission();
            $data = Permission::oldest('title')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('permission_for', function ($row) use ($permission_data){
                    if($row->permission_for){
                        $key = array_search($row->permission_for, array_column($permission_data, 'id'));
                        return $permission_data[$key]['name'];
                    } else {
                        return '';
                    }
                    
                })
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
                            </div>';
                    return $btn;
                })
                ->rawColumns(['action','permission_for'])
                ->make(true);
        }
        return view('admin.pages.permission.index',compact('permissions','permission_for','title'));
    }

    public function store(StorePermissionRequest $request)
    {   
        try {
            if($request->id==0){
                Permission::create($request->only('name','title','permission_for','module_name'));
                return response()->json(['status' => true,'message' => 'Successfuly added permission']);
            } else {
                $credential = Permission::find($request->id);
                if($credential){
                    Permission::where('id',$request->id)->update(['title'=>$request->title,'permission_for'=>$request->permission_for,'module_name'=>$request->module_name]);
                    return response()->json(['status' => true,'message' => 'Permission updated successfully']);
                } else {
                    return response()->json(['status' => false,'message' => 'Permission not found']);
                }
            }
        } catch (\Throwable $th) {
            return response()->json(['status' => false,'message' => 'Something went wrong','errorMessage' => $th->getMessage()]);
        }
    }

    public function show(Request $request)
    {
        try {

            $data = Permission::find($request->id);
            if($data){
                return response()->json(['status' => true,'message' => '','data' => $data]);
            } else {
                return response()->json(['status' => false,'message' => 'Invalida permission']);
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

            $data = Permission::find($request->id);
            if($data){
                Permission::where('id',$request->id)->delete();
                return response()->json(['status' => true,'message' => 'Successfuly deleted permission']);
            } else {
                return response()->json(['status' => false,'message' => 'Invalida permission']);
            }
            
        } catch (\Throwable $th) {
            return response()->json(['status' => false,'message' => 'Something went wrong','errorMessage' => $th->getMessage()]);
        }
    }
    
}
