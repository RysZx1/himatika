<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class MeetingController extends Controller
{
    public function index()
    {
        $userRole = auth()->user()->role;

        $query = Meeting::with('creator:id,name')->latest();

        // Jika mahasiswa biasa, hanya tampilkan rapat terbuka ('all')
        if ($userRole === 'mahasiswa') {
            $query->where('target_role', 'all');
        }

        $meetings = $query->paginate(10);

        return Inertia::render('Meetings/Index', [
            'meetings' => $meetings
        ]);
    }

    public function store(Request $request)
    {
        // Hanya Pengurus / Admin yang bisa menambah agenda/notulensi rapat
        if (!in_array(auth()->user()->role, ['super_admin', 'pengurus'])) {
            abort(403, 'Hanya pengurus yang dapat menambahkan agenda rapat.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'meeting_date' => 'required|date',
            'location' => 'required|string|max:255',
            'description' => 'nullable|string',
            'target_role' => 'required|in:all,pengurus',
            'minutes_file' => 'nullable|mimes:pdf,doc,docx|max:5120', // Maks 5MB
        ]);

        $filePath = null;
        if ($request->hasFile('minutes_file')) {
            $filePath = $request->file('minutes_file')->store('meeting-minutes', 'public');
        }

        Meeting::create([
            'title' => $validated['title'],
            'meeting_date' => $validated['meeting_date'],
            'location' => $validated['location'],
            'description' => $validated['description'] ?? null,
            'target_role' => $validated['target_role'],
            'minutes_file' => $filePath,
            'created_by' => auth()->id(),
        ]);

        return redirect()->back()->with('message', 'Jadwal rapat berhasil ditambahkan!');
    }
}
