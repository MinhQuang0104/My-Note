<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'notes' => $user->notes()->latest()->take(10)->get(),
            'goals' => $user->goals()->latest()->take(10)->get(),
            'entries' => $user->goalEntries()->latest()->take(10)->get(),
        ]);
    }
}
