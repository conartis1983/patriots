<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Hier kannst du (später) Daten an die View übergeben
        return view('index');
    }
}