<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TestAuthController extends Controller
{
    public function testLogin()
    {
        return view('auth.login');
    }
    
    public function testRegister()
    {
        return view('auth.register');
    }
}
