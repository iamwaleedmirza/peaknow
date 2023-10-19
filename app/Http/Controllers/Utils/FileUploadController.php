<?php

namespace App\Http\Controllers\Utils;
use PDF;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class FileUploadController extends Controller
{
    public function upload($request, $name, $request_file, $folder)
    {
        $env = config('app.env');
        if ($env == 'local') {
            return $this->storeDocument($request, $name, $request_file, $folder);
        }
        $bucket_folder = $env != 'production' ? 'test/' : 'prod/';
        $path = $bucket_folder . $folder . '/' . $name;
        try {
            Storage::disk('s3')->put($path, file_get_contents($request->file($request_file)));
        } catch (\Exception $e) {
            Log::error("Error on document uploading : " . json_encode($e));
        }

        return $path;
    }

    public function storeDocument($request, $name, $request_file, $folder)
    {
        return $request->file($request_file)->storeAs($folder, $name);
    }
    
    public function uploadInvoice($paymentDetails, $pdf, $pdf_path, $name, $folder) {
        $env = config('app.env');
        if($env == 'local')
        {
            $path = $folder.'/'.$name;
            $pdf->save(storage_path('app/'.$path));
            return $path;
        }
        $bucket_folder = $env != 'production' ? 'test/' : 'prod/';
        $path = $bucket_folder.$folder.'/'.$name;
        $pdf = PDF::loadView($pdf_path, compact('paymentDetails'));
        try {
            Storage::disk('s3')->put($path, $pdf->output());
        } catch (\Exception $e) {
            Log::error("Error on document uploading : " . json_encode($e));
        }
        
        return $path;
    }
}
