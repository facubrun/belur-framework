<?php

namespace App\Controllers;

use App\Middlewares\AuthMiddleware;
use App\Models\Contact;
use Belur\Http\Controller;
use Belur\Http\Request;

class ContactController extends Controller {
    public function __construct()
    {
        $this->setMiddlewares([AuthMiddleware::class]);
    }

    public function create() {
        return view('contacts/create', []);
    }

    public function store(Request $request) {
        $data = $request->validate([
            'name' => 'required',
            'phone_number' => 'required',
        ]);

        Contact::create([...$data, 'user_id' => auth()->id()]);

        return redirect('/contacts');
    }

    public function index() {
        return view('contacts/index', ['contacts' => Contact::all()]);
    }
} 