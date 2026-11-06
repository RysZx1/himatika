<script setup>
import { ref, computed, onMounted, onBeforeUnmount } from 'vue';
import { Head, Link, usePage } from '@inertiajs/vue3';

const page = usePage();
const user = computed(() => page.props.auth?.user);

defineProps({
    canLogin: { type: Boolean, default: true },
    canRegister: { type: Boolean, default: true },
    news: { type: Array, default: () => [] },
    events: { type: Array, default: () => [] },
    galleries: { type: Array, default: () => [] },
});

const formatDate = (dateString) => {
    if (!dateString) return '';
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    return new Date(dateString).toLocaleDateString('id-ID', options);
};

// State Modal Pop-up
const selectedNews = ref(null);
const selectedGallery = ref(null);

// Animasi Background Cyber Network
const networkCanvas = ref(null);
let animationFrameId;

onMounted(() => {
    const canvas = networkCanvas.value;
    if (!canvas) return;
    
    const ctx = canvas.getContext('2d');
    let width = canvas.width = window.innerWidth;
    let height = canvas.height = canvas.parentElement.clientHeight;

    const particles = [];
    // Jumlah titik cyber (bisa lu tambah/kurangin)
    const particleCount = 70;

    for (let i = 0; i < particleCount; i++) {
        particles.push({
            x: Math.random() * width,
            y: Math.random() * height,
            vx: (Math.random() - 0.5) * 1.5,
            vy: (Math.random() - 0.5) * 1.5,
            radius: Math.random() * 2 + 1
        });
    }

    const draw = () => {
        ctx.clearRect(0, 0, width, height);
        // Warna garis dan titik (Indigo)
        ctx.fillStyle = 'rgba(99, 102, 241, 0.8)';
        ctx.strokeStyle = 'rgba(99, 102, 241, 0.2)';
        ctx.lineWidth = 1;

        particles.forEach((p, index) => {
            p.x += p.vx;
            p.y += p.vy;

            // Pantulan kalau kena ujung layar
            if (p.x < 0 || p.x > width) p.vx *= -1;
            if (p.y < 0 || p.y > height) p.vy *= -1;

            ctx.beginPath();
            ctx.arc(p.x, p.y, p.radius, 0, Math.PI * 2);
            ctx.fill();

            // Tarik garis kalau jarak antar partikel dekat
            for (let j = index + 1; j < particles.length; j++) {
                const p2 = particles[j];
                const dx = p.x - p2.x;
                const dy = p.y - p2.y;
                const dist = Math.sqrt(dx * dx + dy * dy);

                if (dist < 130) {
                    ctx.beginPath();
                    ctx.moveTo(p.x, p.y);
                    ctx.lineTo(p2.x, p2.y);
                    ctx.stroke();
                }
            }
        });

        animationFrameId = requestAnimationFrame(draw);
    };

    draw();

    // Auto-resize kalau layar diubah ukurannya
    const handleResize = () => {
        width = canvas.width = window.innerWidth;
        height = canvas.height = canvas.parentElement.clientHeight;
    };
    window.addEventListener('resize', handleResize);

    onBeforeUnmount(() => {
        cancelAnimationFrame(animationFrameId);
        window.removeEventListener('resize', handleResize);
    });
});
</script>

<template>
    <Head title="HIMATIKA - Himpunan Mahasiswa Informatika" />

    <div class="min-h-screen bg-gray-900 text-gray-100 selection:bg-indigo-500 selection:text-white">
        <!-- Navbar -->
        <header class="sticky top-0 z-50 border-b border-gray-800 bg-gray-900/80 backdrop-blur-md">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-4">
                <div class="flex items-center gap-3">
                    <Link :href="route('landing')" class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-600 font-bold text-white shadow-lg shadow-indigo-500/30">HM</Link>
                    <span class="text-xl font-bold tracking-wide text-white">HIMATIKA</span>
                </div>
                <nav class="hidden items-center gap-6 md:flex">
                    <a href="#profil" class="text-sm font-medium text-gray-300 hover:text-indigo-400">Profil</a>
                    <a href="#berita" class="text-sm font-medium text-gray-300 hover:text-indigo-400">Berita</a>
                    <a href="#agenda" class="text-sm font-medium text-gray-300 hover:text-indigo-400">Agenda</a>
                    <a href="#galeri" class="text-sm font-medium text-gray-300 hover:text-indigo-400">Galeri</a>
                </nav>
                <div class="flex items-center gap-3">
                    <Link v-if="user" :href="route('dashboard')" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500 shadow-lg shadow-indigo-600/20">Ke Dashboard</Link>
                    <template v-else>
                        <Link v-if="canLogin" :href="route('login')" class="rounded-lg px-4 py-2 text-sm font-medium text-gray-300 hover:text-white">Masuk</Link>
                        <Link v-if="canRegister" :href="route('register')" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500 shadow-lg shadow-indigo-600/20">Daftar</Link>
                    </template>
                </div>
            </div>
        </header>

        <!-- Hero Section (DITAMBAH BACKGROUND CANVAS CYBER) -->
        <section class="relative overflow-hidden py-24 text-center min-h-[85vh] flex items-center justify-center">
            <!-- CANVAS BACKGROUND DISINI -->
            <canvas ref="networkCanvas" class="absolute inset-0 z-0 h-full w-full opacity-60"></canvas>
            
            <!-- Glow Latar Belakang -->
            <div class="absolute left-1/2 top-1/2 z-0 h-[400px] w-[600px] -translate-x-1/2 -translate-y-1/2 rounded-full bg-indigo-600/20 blur-[120px] pointer-events-none"></div>
            
            <!-- Konten Text Hero -->
            <div class="mx-auto max-w-4xl px-6 relative z-10">
                <span class="inline-block rounded-full bg-indigo-500/10 px-4 py-1.5 text-xs font-semibold tracking-wide text-indigo-400 ring-1 ring-indigo-500/20 backdrop-blur-sm">
                    Portal Resmi Mahasiswa Informatika
                </span>
                <h1 class="mt-6 text-4xl font-extrabold tracking-tight text-white sm:text-6xl drop-shadow-lg">
                    Himpunan Mahasiswa Informatika
                </h1>
                <p class="mt-6 text-lg leading-relaxed text-gray-300 drop-shadow-md max-w-2xl mx-auto">
                    Wadah kreativitas, kolaborasi, dan inovasi mahasiswa Informatika. Tempat berbagi ilmu, dokumentasi kegiatan, serta informasi akademik kampus.
                </p>
                <div class="mt-8 flex justify-center gap-4">
                    <a href="#berita" class="rounded-xl bg-indigo-600 px-6 py-3 font-semibold text-white hover:bg-indigo-500 shadow-lg shadow-indigo-600/30 transition transform hover:-translate-y-1">Jelajahi Berita</a>
                    <a href="#profil" class="rounded-xl border border-gray-600 bg-gray-900/50 backdrop-blur-md px-6 py-3 font-semibold text-gray-300 hover:bg-gray-800 hover:text-white transition transform hover:-translate-y-1">Tentang Kami</a>
                </div>
            </div>
        </section>

        <!-- Profil & Visi Misi Section -->
        <section id="profil" class="border-t border-gray-800 bg-gray-900/50 py-20 relative z-20">
            <div class="mx-auto max-w-7xl px-6">
                <div class="text-center">
                    <h2 class="text-3xl font-bold text-white">Profil & Visi Misi</h2>
                    <p class="mt-2 text-gray-400">Membangun mahasiswa Informatika yang berdaya saing dan berintegritas.</p>
                </div>
                <div class="mt-12 grid gap-8 md:grid-cols-2">
                    <div class="rounded-2xl border border-gray-800 bg-gray-800/40 p-8 backdrop-blur">
                        <h3 class="text-xl font-bold text-indigo-400">🎯 Visi</h3>
                        <p class="mt-4 leading-relaxed text-gray-300">Menjadi himpunan mahasiswa yang solutif, inovatif, serta unggul dalam bidang teknologi informasi di tingkat nasional maupun internasional.</p>
                    </div>
                    <div class="rounded-2xl border border-gray-800 bg-gray-800/40 p-8 backdrop-blur">
                        <h3 class="text-xl font-bold text-indigo-400">🚀 Misi</h3>
                        <ul class="mt-4 space-y-3 text-gray-300">
                            <li class="flex items-start gap-2"><span class="text-indigo-400">✓</span> Menyelenggarakan kegiatan pengembangan skill koding dan riset.</li>
                            <li class="flex items-start gap-2"><span class="text-indigo-400">✓</span> Mempererat rasa kekeluargaan antar mahasiswa Informatika.</li>
                            <li class="flex items-start gap-2"><span class="text-indigo-400">✓</span> Membangun jaringan kemitraan dengan industri TI.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <!-- Berita & Informasi Section -->
        <section id="berita" class="border-t border-gray-800 py-20 relative z-20">
            <div class="mx-auto max-w-7xl px-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-3xl font-bold text-white">Berita & Informasi</h2>
                        <p class="mt-1 text-gray-400">Kabar terbaru seputar HIMATIKA dan teknologi.</p>
                    </div>
                </div>

                <div v-if="news.length > 0" class="mt-10 grid gap-8 md:grid-cols-3">
                    <article v-for="item in news" :key="item.id" @click="selectedNews = item" class="cursor-pointer flex flex-col overflow-hidden rounded-2xl border border-gray-800 bg-gray-800/30 transition hover:border-indigo-500/50 hover:bg-gray-800/60 group">
                        <div class="h-48 w-full bg-gray-800 flex items-center justify-center overflow-hidden relative">
                            <img v-if="item.thumbnail" :src="`/storage/${item.thumbnail}`" :alt="item.title" class="h-full w-full object-cover transition duration-500 group-hover:scale-110" />
                            <span v-else class="text-gray-600 font-semibold">Tanpa Thumbnail</span>
                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition duration-300"></div>
                        </div>
                        <div class="flex flex-1 flex-col p-6">
                            <span class="text-xs font-semibold uppercase tracking-wider text-indigo-400">{{ item.category }}</span>
                            <h3 class="mt-2 text-xl font-bold text-white line-clamp-2">{{ item.title }}</h3>
                            <p class="mt-3 text-sm text-gray-400 line-clamp-3">{{ item.content }}</p>
                            <div class="mt-auto pt-6 flex items-center justify-between text-xs text-gray-500 border-t border-gray-800/60">
                                <span>Penulis: {{ item.author?.name || 'Admin' }}</span>
                                <span>{{ formatDate(item.published_at) }}</span>
                            </div>
                        </div>
                    </article>
                </div>
                <div v-else class="mt-10 rounded-xl border border-dashed border-gray-800 p-12 text-center text-gray-500">Belum ada berita yang dipublikasikan.</div>
            </div>
        </section>

        <!-- Event & Agenda Section -->
        <section id="agenda" class="border-t border-gray-800 bg-gray-900/50 py-20 relative z-20">
            <div class="mx-auto max-w-7xl px-6">
                <div>
                    <h2 class="text-3xl font-bold text-white">Event & Agenda</h2>
                    <p class="mt-1 text-gray-400">Jadwal kegiatan dan acara mahasiswa mendatang.</p>
                </div>

                <div v-if="events.length > 0" class="mt-10 grid gap-6 md:grid-cols-2">
                    <div v-for="event in events" :key="event.id" class="flex flex-col justify-between rounded-2xl border border-gray-800 bg-gray-800/40 p-6 backdrop-blur transition hover:border-emerald-500/50">
                        <div>
                            <div class="flex items-center justify-between">
                                <span class="rounded-full bg-emerald-500/10 px-3 py-1 text-xs font-medium text-emerald-400 ring-1 ring-emerald-500/20">{{ event.status }}</span>
                                <span class="text-xs text-gray-400">📅 {{ formatDate(event.event_date) }}</span>
                            </div>
                            <h3 class="mt-4 text-xl font-bold text-white">{{ event.title }}</h3>
                            <p class="mt-2 text-sm text-gray-400">{{ event.description }}</p>
                        </div>
                        <div class="mt-6 flex items-center justify-between border-t border-gray-800/80 pt-4 text-xs text-gray-400">
                            <span>📍 {{ event.location }}</span>
                            <span class="font-semibold text-indigo-400">Buka Pendaftaran</span>
                        </div>
                    </div>
                </div>
                <div v-else class="mt-10 rounded-xl border border-dashed border-gray-800 p-12 text-center text-gray-500">Belum ada agenda mendatang.</div>
            </div>
        </section>

        <!-- Galeri Dokumentasi -->
        <section id="galeri" class="border-t border-gray-800 py-20 relative z-20">
            <div class="mx-auto max-w-7xl px-6">
                <div>
                    <h2 class="text-3xl font-bold text-white">Dokumentasi Kegiatan</h2>
                    <p class="mt-1 text-gray-400">Kumpulan momen kegiatan workshop, lomba, dan pengabdian.</p>
                </div>

                <div v-if="galleries.length > 0" class="mt-10 grid gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                    <div v-for="gallery in galleries" :key="gallery.id" @click="selectedGallery = gallery" class="cursor-pointer group relative overflow-hidden rounded-2xl border border-gray-800 bg-gray-800">
                        <img :src="`/storage/${gallery.image_path}`" :alt="gallery.title" class="h-64 w-full object-cover transition duration-500 group-hover:scale-110 group-hover:opacity-80" />
                        <div class="absolute inset-0 bg-gradient-to-t from-gray-950 via-gray-950/20 to-transparent p-6 flex flex-col justify-end opacity-90 transition group-hover:opacity-100">
                            <span class="text-xs text-indigo-400 font-semibold">{{ gallery.activity_name || 'Kegiatan' }}</span>
                            <h3 class="text-base font-bold text-white mt-1 leading-tight">{{ gallery.title }}</h3>
                        </div>
                    </div>
                </div>
                <div v-else class="mt-10 rounded-xl border border-dashed border-gray-800 p-12 text-center text-gray-500">Belum ada foto kegiatan di galeri.</div>
            </div>
        </section>

        <footer class="border-t border-gray-800 py-8 text-center text-sm text-gray-500 relative z-20 bg-gray-900">
            &copy; 2026 HIMATIKA - Himpunan Mahasiswa Informatika. All rights reserved.
        </footer>

        <!-- ================= MODALS (POP-UP) ================= -->
        
        <!-- Modal Baca Berita Lengkap -->
        <div v-if="selectedNews" class="fixed inset-0 z-[60] flex items-center justify-center bg-black/80 backdrop-blur-sm p-4" @click.self="selectedNews = null">
            <div class="bg-gray-900 border border-gray-700 rounded-2xl max-w-3xl w-full max-h-[90vh] flex flex-col shadow-2xl overflow-hidden relative animate-fade-in">
                <button @click="selectedNews = null" class="absolute top-4 right-4 bg-black/60 hover:bg-red-500 text-white h-10 w-10 rounded-full flex items-center justify-center transition z-10 text-xl font-bold">&times;</button>
                <img v-if="selectedNews.thumbnail" :src="`/storage/${selectedNews.thumbnail}`" class="w-full h-72 object-cover flex-shrink-0" />
                <div class="p-8 overflow-y-auto">
                    <span class="text-indigo-400 text-xs font-bold uppercase tracking-wider bg-indigo-500/10 px-3 py-1 rounded-full">{{ selectedNews.category }}</span>
                    <h2 class="text-3xl font-bold text-white mt-4 leading-tight">{{ selectedNews.title }}</h2>
                    <div class="flex items-center gap-4 text-gray-500 text-sm mt-3 mb-6 pb-6 border-b border-gray-800">
                        <span>Ditulis oleh <strong class="text-gray-300">{{ selectedNews.author?.name || 'Admin' }}</strong></span>
                        <span>•</span>
                        <span>{{ formatDate(selectedNews.published_at) }}</span>
                    </div>
                    <p class="text-gray-300 leading-relaxed whitespace-pre-line text-lg">{{ selectedNews.content }}</p>
                </div>
            </div>
        </div>

        <!-- Modal Lihat Zoom Foto Galeri -->
        <div v-if="selectedGallery" class="fixed inset-0 z-[60] flex items-center justify-center bg-black/95 backdrop-blur-md p-4" @click="selectedGallery = null">
            <div class="relative max-w-5xl w-full text-center flex flex-col items-center animate-zoom-in" @click.stop>
                <button @click="selectedGallery = null" class="absolute -top-6 -right-2 md:-right-12 bg-gray-800 hover:bg-red-500 text-white h-10 w-10 rounded-full flex items-center justify-center transition z-10 text-xl font-bold">&times;</button>
                <img :src="`/storage/${selectedGallery.image_path}`" class="max-h-[75vh] w-auto mx-auto rounded-lg shadow-[0_0_50px_rgba(0,0,0,0.5)] border border-gray-700 mb-6" />
                <div class="bg-gray-900/90 backdrop-blur inline-block px-8 py-4 rounded-2xl border border-gray-700 shadow-xl">
                    <h3 class="text-2xl font-bold text-white">{{ selectedGallery.title }}</h3>
                    <p class="text-gray-400 mt-2 text-sm flex items-center justify-center gap-2">
                        <span class="bg-indigo-500/20 text-indigo-400 px-2 py-0.5 rounded">{{ selectedGallery.activity_name || 'Dokumentasi' }}</span> 
                        <span>•</span>
                        <span>{{ formatDate(selectedGallery.created_at) }}</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>