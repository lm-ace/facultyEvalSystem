<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index()
    {
        // You can pass data to the view here later
        return view('welcome');
    }
}
