<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function get($path)
    {
        if(!Storage::disk('local')->exists($path)){
            abort('404');
        }

        $response = response()->file(storage_path('app'.DIRECTORY_SEPARATOR.($path)));

        ob_end_clean();

        return $response;
    }
}
