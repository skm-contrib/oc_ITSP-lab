<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FrontDocumentation extends Controller
{
    public function index()
    {
        //повертати index.html з файлу website/index.html
        return response()->file(base_path('website/index.html'));
    }
}
