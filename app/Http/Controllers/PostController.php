<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with([
            'user:id,name,role,avatar',
            'comments.user:id,name,avatar',
            'likes:id,post_id,user_id',
        ])
        ->where('status', 'published')
        ->withCount(['comments', 'likes'])
        ->latest()
        ->paginate(15);

        return Inertia::render('Feed/Index', [
            'posts' => $posts
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:1000',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('posts', 'public');
        }

        Post::create([
            'user_id' => auth()->id(),
            'content' => $validated['content'],
            'image_path' => $imagePath,
            'status' => 'published',
        ]);

        return redirect()->back()->with('message', 'Postingan berhasil dibuat!');
    }

    public function toggleLike(Post $post)
    {
        $existingLike = Like::where('post_id', $post->id)
            ->where('user_id', auth()->id())
            ->first();

        if ($existingLike) {
            $existingLike->delete();
        } else {
            Like::create([
                'post_id' => $post->id,
                'user_id' => auth()->id(),
            ]);
        }

        return redirect()->back();
    }

    public function storeComment(Request $request, Post $post)
    {
        $validated = $request->validate([
            'comment' => 'required|string|max:500',
        ]);

        Comment::create([
            'post_id' => $post->id,
            'user_id' => auth()->id(),
            'comment' => $validated['comment'],
        ]);

        return redirect()->back();
    }

    public function destroy(Post $post)
    {
        // Hanya pembuat post atau admin/pengurus yang bisa hapus
        if ($post->user_id !== auth()->id() && !in_array(auth()->user()->role, ['super_admin', 'pengurus'])) {
            abort(403);
        }

        if ($post->image_path) {
            Storage::disk('public')->delete($post->image_path);
        }

        $post->delete();

        return redirect()->back()->with('message', 'Postingan berhasil dihapus!');
    }
}