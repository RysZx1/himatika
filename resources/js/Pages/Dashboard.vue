<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const user = computed(() => page.props.auth?.user);
</script>

<template>
    <Head title="Dashboard HIMATIKA" />

    <div class="min-h-screen bg-gray-900 text-gray-100">
        <!-- Topbar -->
        <header class="sticky top-0 z-50 border-b border-gray-800 bg-gray-900/90 backdrop-blur">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-4">
                <div class="flex items-center gap-3">
                    <Link :href="route('landing')" class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-600 font-bold text-white shadow-lg">HM</Link>
                    <span class="text-xl font-bold text-white">Dashboard HIMATIKA</span>
                </div>

                <div class="flex items-center gap-3">
                    <span v-if="user" class="text-sm text-gray-400 hidden sm:inline">
                        {{ user.name }} (<span class="font-semibold uppercase text-indigo-400">{{ user.role || 'mahasiswa' }}</span>)
                    </span>

                    <Link :href="route('landing')" class="rounded-lg border border-gray-700 bg-gray-800/80 px-3.5 py-2 text-sm font-semibold text-gray-300 transition hover:bg-gray-700 hover:text-white">
                        🌐 Web Utama
                    </Link>

                    <Link :href="route('logout')" method="post" as="button" class="rounded-lg border border-red-500/30 bg-red-600/20 px-3.5 py-2 text-sm font-semibold text-red-400 transition hover:bg-red-600 hover:text-white">
                        Keluar
                    </Link>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="mx-auto max-w-7xl px-6 py-10">
            <!-- Welcome Card -->
            <div class="mb-8 rounded-2xl border border-gray-800 bg-gray-800/40 p-8 backdrop-blur">
                <h1 class="text-2xl font-bold text-white">Halo, {{ user?.name || 'User' }}! 👋</h1>
                <p class="mt-2 text-gray-400">
                    NIM: <span class="font-medium text-gray-200">{{ user?.nim || 'Belum diisi' }}</span> | 
                    Email: <span class="font-medium text-gray-200">{{ user?.email || '-' }}</span>
                </p>
            </div>

            <!-- Shortcut Navigation Grid -->
            <h2 class="mb-4 text-lg font-bold text-white">Akses Cepat Fitur</h2>
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-2">
                <!-- Feed Komunitas -->
                <Link :href="route('feed.index')" class="group rounded-2xl border border-gray-800 bg-gray-800/30 p-6 transition hover:border-indigo-500/50 hover:bg-gray-800/60">
                    <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-600/20 text-xl font-bold text-indigo-400 transition group-hover:bg-indigo-600 group-hover:text-white">💬</div>
                    <h3 class="text-lg font-bold text-white">Community Feed</h3>
                    <p class="mt-2 text-sm text-gray-400">Lihat, buat postingan, dan diskusi bareng mahasiswa Informatika.</p>
                </Link>

                <!-- Informasi Rapat -->
                <Link :href="route('meetings.index')" class="group rounded-2xl border border-gray-800 bg-gray-800/30 p-6 transition hover:border-emerald-500/50 hover:bg-gray-800/60">
                    <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-600/20 text-xl font-bold text-emerald-400 transition group-hover:bg-emerald-600 group-hover:text-white">📅</div>
                    <h3 class="text-lg font-bold text-white">Informasi Rapat</h3>
                    <p class="mt-2 text-sm text-gray-400">Cek jadwal agenda rapat dan unduh berkas notulensi.</p>
                </Link>
            </div>

            <!-- Admin Panel Section (Khusus Super Admin & Pengurus) -->
            <div v-if="user && ['super_admin', 'pengurus'].includes(user.role)" class="mt-12 border-t border-gray-800/80 pt-8">
                <h2 class="mb-4 text-lg font-bold text-white">⚙️ Menu Admin Panel</h2>
                <div class="grid gap-6 md:grid-cols-3">
                    
                    <Link :href="route('admin.news.index')" class="group rounded-2xl border border-gray-800 bg-gray-800/30 p-6 transition hover:border-purple-500/50 hover:bg-gray-800/60">
                        <div class="mb-4 flex h-10 w-10 items-center justify-center rounded-lg bg-purple-600/20 text-lg font-bold text-purple-400 transition group-hover:bg-purple-600 group-hover:text-white">📰</div>
                        <h3 class="text-base font-bold text-white">Kelola Berita</h3>
                        <p class="mt-1 text-xs text-gray-400">Tulis dan edit artikel portal berita HIMATIKA.</p>
                    </Link>

                    <Link :href="route('admin.galleries.index')" class="group rounded-2xl border border-gray-800 bg-gray-800/30 p-6 transition hover:border-blue-500/50 hover:bg-gray-800/60">
                        <div class="mb-4 flex h-10 w-10 items-center justify-center rounded-lg bg-blue-600/20 text-lg font-bold text-blue-400 transition group-hover:bg-blue-600 group-hover:text-white">📸</div>
                        <h3 class="text-base font-bold text-white">Kelola Galeri</h3>
                        <p class="mt-1 text-xs text-gray-400">Upload foto dokumentasi kegiatan kampus.</p>
                    </Link>

                    <div class="group rounded-2xl border border-dashed border-gray-700 bg-gray-800/10 p-6 opacity-60">
                        <div class="mb-4 flex h-10 w-10 items-center justify-center rounded-lg bg-orange-600/20 text-lg font-bold text-orange-400">📢</div>
                        <h3 class="text-base font-bold text-white">Kelola Event</h3>
                        <p class="mt-1 text-xs text-gray-400">Menu sedang dalam perbaikan / pengembangan.</p>
                    </div>

                </div>
            </div>
        </main>
    </div>
</template>