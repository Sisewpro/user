<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CountTimeController extends Controller
{
    public function index()
    {
        return view('counttime'); // Ensure this view exists in resources/views
    }
}