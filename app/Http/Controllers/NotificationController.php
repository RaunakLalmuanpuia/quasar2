<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('Notification/Index', [
            'notifications' => $request->user()->notifications()->paginate(4)
        ]);
    }
}
