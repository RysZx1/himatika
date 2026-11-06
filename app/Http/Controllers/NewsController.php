<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::with('author:id,name')->latest()->paginate(10);
        return Inertia::render('Admin/News/Index', [
            'news' => $news
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/News/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|in:kegiatan,teknologi,kampus,umum',
            'content' => 'required|string',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('news-thumbnails', 'public');
        }

        News::create([
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']) . '-' . Str::random(5),
            'category' => $validated['category'],
            'content' => $validated['content'],
            'thumbnail' => $thumbnailPath,
            'author_id' => auth()->id(),
            'published_at' => now(),
        ]);

        return redirect()->route('admin.news.index')->with('message', 'Berita berhasil diterbitkan!');
    }

    public function edit(News $news)
    {
        return Inertia::render('Admin/News/Edit', [
            'news' => $news
        ]);
    }

    public function update(Request $request, News $news)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|in:kegiatan,teknologi,kampus,umum',
            'content' => 'required|string',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('thumbnail')) {
            if ($news->thumbnail) {
                Storage::disk('public')->delete($news->thumbnail);
            }
            $news->thumbnail = $request->file('thumbnail')->store('news-thumbnails', 'public');
        }

        $news->update([
            'title' => $validated['title'],
            'category' => $validated['category'],
            'content' => $validated['content'],
        ]);

        return redirect()->route('admin.news.index')->with('message', 'Berita berhasil diperbarui!');
    }

    public function destroy(News $news)
    {
        if ($news->thumbnail) {
            Storage::disk('public')->delete($news->thumbnail);
        }

        $news->delete();

        return redirect()->route('admin.news.index')->with('message', 'Berita berhasil dihapus!');
    }
}
