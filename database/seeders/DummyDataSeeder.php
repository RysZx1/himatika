<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Gallery;
use App\Models\News;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        // Admin Dummy
        $admin = User::create([
            'name' => 'Admin HIMATIKA',
            'nim' => '240101001',
            'email' => 'admin@himatika.test',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
        ]);

        // Berita Dummy
        News::create([
            'title' => 'Workshop Web Development Modern 2026',
            'slug' => 'workshop-web-development-modern-2026',
            'category' => 'kegiatan',
            'content' => 'HIMATIKA sukses menggelar workshop web dev menggunakan Laravel dan Inertia.js.',
            'author_id' => $admin->id,
            'published_at' => now(),
        ]);

        // Event Dummy
        Event::create([
            'title' => 'Informatics Competition 2026',
            'slug' => 'informatics-competition-2026',
            'description' => 'Lomba koding dan UI/UX desain antar mahasiswa Informatika.',
            'location' => 'Aula Utama Kampus',
            'event_date' => now()->addDays(7),
            'status' => 'upcoming',
        ]);

        // Galeri Dummy
        Gallery::create([
            'title' => 'Foto Bersama Pengurus HIMATIKA',
            'image_path' => 'https://via.placeholder.com/600x400',
            'activity_name' => 'Pelantikan Pengurus',
            'activity_date' => now()->subMonth(),
            'uploaded_by' => $admin->id,
        ]);
    }
}