<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';

defineProps({
    galleries: Object,
});

const formatDate = (dateString) => {
    if (!dateString) return '-';
    return new Date(dateString).toLocaleDateString('id-ID', {
        day: 'numeric', month: 'long', year: 'numeric'
    });
};
</script>

<template>
    <Head title="Kelola Galeri - Admin Panel" />

    <div class="min-h-screen bg-gray-900 text-gray-100 p-8">
        <div class="mx-auto max-w-6xl">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-2xl font-bold text-white">Kelola Dokumentasi (Galeri)</h1>
                    <Link :href="route('dashboard')" class="text-sm text-indigo-400 hover:text-indigo-300">
                        &larr; Kembali ke Dashboard
                    </Link>
                </div>
                <Link 
                    :href="route('admin.galleries.create')" 
                    class="bg-indigo-600 hover:bg-indigo-500 text-white px-4 py-2 rounded-lg font-semibold transition"
                >
                    + Upload Foto
                </Link>
            </div>

            <!-- Flash Message -->
            <div v-if="$page.props.flash.message" class="mb-6 bg-emerald-500/20 border border-emerald-500 text-emerald-400 px-4 py-3 rounded-lg">
                {{ $page.props.flash.message }}
            </div>

            <!-- Grid Foto Galeri -->
            <div class="grid gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                <div v-for="item in galleries.data" :key="item.id" class="bg-gray-800 rounded-xl border border-gray-700 overflow-hidden group">
                    <img :src="`/storage/${item.image_path}`" :alt="item.title" class="h-40 w-full object-cover group-hover:scale-105 transition duration-300" />
                    <div class="p-4">
                        <h3 class="font-bold text-white text-sm truncate">{{ item.title }}</h3>
                        <p class="text-xs text-gray-400 mt-1">{{ item.activity_name || 'Kegiatan Umum' }}</p>
                        <p class="text-[10px] text-gray-500 mt-2">Diunggah: {{ formatDate(item.created_at) }}</p>
                        
                        <div class="mt-4 flex justify-end border-t border-gray-700 pt-3">
                            <Link 
                                :href="route('admin.news.destroy', item.id)" 
                                method="delete" 
                                as="button"
                                class="text-red-400 hover:text-red-300"
                                preserve-scroll
                                @click="!confirm('Yakin nih mau hapus berita ini, lek?') ? $event.preventDefault() : null"
                            >
                                Hapus Foto
                            </Link>
                        </div>
                    </div>
                </div>
            </div>

            <div v-if="!galleries.data.length" class="bg-gray-800 rounded-xl border border-gray-700 p-12 text-center text-gray-500">
                Belum ada foto dokumentasi yang diupload.
            </div>
        </div>
    </div>
</template>