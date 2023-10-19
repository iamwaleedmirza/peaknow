<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\PeaksErrorDetail;
use App\Http\Controllers\Controller;
use DataTables;

class PeaksErrorDetailController extends Controller
{
    public function getPeaksErrorDetailView(Request $request)
    {
        $permissions = Controller::currentPermission();
        
        $title = 'Error Codes';
        if($request->ajax()){
            $errors = PeaksErrorDetail::all();
            return Datatables::of($errors)
                ->addIndexColumn()
                ->editColumn('error_code', function ($row) {
                    return ($row->error_code) ? $row->error_code : '-';  
                })
                ->editColumn('error_description', function ($row) {
                    return ($row->error_description) ? $row->error_description : '-';
                })
                ->rawColumns(['error_code','error_description'])
                ->make(true);
        }
        return view('admin.pages.peaks_errors', compact('permissions','title'));
    }
}
