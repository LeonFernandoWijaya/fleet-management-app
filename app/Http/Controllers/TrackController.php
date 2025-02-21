<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TrackController extends Controller
{
    //
    public function index()
    {
        return view('track.index');
    }
}
