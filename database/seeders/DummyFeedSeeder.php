<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DummyFeedSeeder extends Seeder
{
    public function run(): void
    {
        // User Mahasiswa Dummy
        $mahasiswa = User::create([
            'name' => 'Budi Pratama',
            'nim' => '240101002',
            'email' => 'budi@student.himatika.test',
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
        ]);

        $admin = User::first();

        if ($admin) {
            // Post Dummy
            $post = Post::create([
                'user_id' => $mahasiswa->id,
                'content' => 'Ada yang punya rekomendasi tempat belajar Laravel + Inertia Vue yang asik ga lek? Lagi garap project himpunan nih!',
                'status' => 'published',
            ]);

            // Comment Dummy
            Comment::create([
                'post_id' => $post->id,
                'user_id' => $admin->id,
                'comment' => 'Semangat bro! Coba cek dokumentasi resmi Laravel Breeze + Inertia, udah lengkap banget.',
            ]);

            // Like Dummy
            Like::create([
                'post_id' => $post->id,
                'user_id' => $admin->id,
            ]);
        }
    }
}