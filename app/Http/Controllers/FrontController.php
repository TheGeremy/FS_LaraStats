<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FrontController extends Controller
{
    //
    public function home() {
    	return view('index', [
    		'active_mi' => 'home_overview'
    	]);
    }
}
