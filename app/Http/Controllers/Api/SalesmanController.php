<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class SalesmanController extends Controller
{
    public function dashboard()
    {
        return response()->json(['message' => 'Welcome to Salesman Dashboard!']);
    }
}
