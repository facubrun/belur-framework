<?php

namespace App\Controllers;

use Belur\Http\Controller;

class HomeController extends Controller {
    public function show() {
        return view('home', []);
    }
} 