<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class PaytmController extends Controller
{
    public function initiate()
    {
        return Inertia::render('Payment');
    }
    public function pay(Request $request)
    {
        dd($request);
    }
}
