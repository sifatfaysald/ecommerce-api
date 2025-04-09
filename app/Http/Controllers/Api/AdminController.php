<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    public function dashboard()
    {
        return response()->json(['message' => 'Welcome to Admin Dashboard!']);
    }
}
