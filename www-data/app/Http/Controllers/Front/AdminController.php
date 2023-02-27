<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Response;

class AdminController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return Response
     */
    public function index()
    {
        return view('front.admin');
    }
}
