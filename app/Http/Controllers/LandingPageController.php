<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Gallery;
use App\Models\News;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

class LandingPageController extends Controller
{
    public function index()
    {
        return Inertia::render('Welcome', [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            // Baris ini yang ngasih tau Vue kalau lu udah login:
            'auth' => [
                'user' => auth()->user()
            ],
            'news' => News::with('author:id,name')
                ->latest()
                ->take(3)
                ->get(),
            'events' => Event::where('status', '!=', 'completed')
                ->orderBy('event_date', 'asc')
                ->take(3)
                ->get(),
            'galleries' => Gallery::latest()
                ->take(6)
                ->get(),
        ]);
    }
}