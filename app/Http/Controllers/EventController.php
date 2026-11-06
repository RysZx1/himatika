<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::latest()->paginate(10);
        return Inertia::render('Admin/Events/Index', [
            'events' => $events
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Events/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'event_date' => 'required|date',
            'banner' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'max_participants' => 'nullable|integer|min:1',
            'status' => 'required|in:upcoming,ongoing,completed',
        ]);

        $bannerPath = null;
        if ($request->hasFile('banner')) {
            $bannerPath = $request->file('banner')->store('event-banners', 'public');
        }

        Event::create([
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']) . '-' . Str::random(5),
            'description' => $validated['description'],
            'location' => $validated['location'],
            'event_date' => $validated['event_date'],
            'banner' => $bannerPath,
            'max_participants' => $validated['max_participants'] ?? null,
            'status' => $validated['status'],
        ]);

        return redirect()->route('admin.events.index')->with('message', 'Event berhasil ditambahkan!');
    }

    public function edit(Event $event)
    {
        return Inertia::render('Admin/Events/Edit', [
            'event' => $event
        ]);
    }

    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'event_date' => 'required|date',
            'banner' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'max_participants' => 'nullable|integer|min:1',
            'status' => 'required|in:upcoming,ongoing,completed',
        ]);

        if ($request->hasFile('banner')) {
            if ($event->banner) {
                Storage::disk('public')->delete($event->banner);
            }
            $event->banner = $request->file('banner')->store('event-banners', 'public');
        }

        $event->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'location' => $validated['location'],
            'event_date' => $validated['event_date'],
            'max_participants' => $validated['max_participants'] ?? null,
            'status' => $validated['status'],
        ]);

        return redirect()->route('admin.events.index')->with('message', 'Event berhasil diperbarui!');
    }

    public function destroy(Event $event)
    {
        if ($event->banner) {
            Storage::disk('public')->delete($event->banner);
        }

        $event->delete();

        return redirect()->route('admin.events.index')->with('message', 'Event berhasil dihapus!');
    }
}