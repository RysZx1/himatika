<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';

defineProps({
    news: Object,
});

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('id-ID', {
        day: 'numeric', month: 'long', year: 'numeric'
    });
};

// State untuk Modal Hapus Keren
const isDeleteModalOpen = ref(false);
const itemToDelete = ref(null);

const openDeleteModal = (id) => {
    itemToDelete.value = id;
    isDeleteModalOpen.value = true;
};

const executeDelete = () => {
    if (itemToDelete.value) {
        router.delete(route('admin.news.destroy', itemToDelete.value), {
            preserveScroll: true,
            onSuccess: () => {
                isDeleteModalOpen.value = false;
                itemToDelete.value = null;
            }
        });
    }
};
</script>

<template>
    <Head title="Kelola Berita - Admin Panel" />

    <div class="min-h-screen bg-gray-900 text-gray-100 p-8 relative">
        <div class="mx-auto max-w-6xl">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-2xl font-bold text-white">Kelola Berita</h1>
                    <Link :href="route('dashboard')" class="text-sm text-indigo-400 hover:text-indigo-300">
                        &larr; Kembali ke Dashboard
                    </Link>
                </div>
                <Link :href="route('admin.news.create')" class="bg-indigo-600 hover:bg-indigo-500 text-white px-4 py-2 rounded-lg font-semibold transition">
                    + Tambah Berita
                </Link>
            </div>

            <!-- Flash Message -->
            <div v-if="$page.props.flash.message" class="mb-6 bg-emerald-500/20 border border-emerald-500 text-emerald-400 px-4 py-3 rounded-lg">
                {{ $page.props.flash.message }}
            </div>

            <!-- Tabel -->
            <div class="bg-gray-800 rounded-xl border border-gray-700 overflow-hidden">
                <table class="w-full text-left text-sm text-gray-300">
                    <thead class="bg-gray-900/50 text-xs uppercase text-gray-400">
                        <tr>
                            <th class="px-6 py-4">Judul Berita</th>
                            <th class="px-6 py-4">Kategori</th>
                            <th class="px-6 py-4">Tanggal</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in news.data" :key="item.id" class="border-b border-gray-700/50 hover:bg-gray-700/20">
                            <td class="px-6 py-4 font-medium text-white">{{ item.title }}</td>
                            <td class="px-6 py-4 capitalize">{{ item.category }}</td>
                            <td class="px-6 py-4">{{ formatDate(item.published_at) }}</td>
                            <td class="px-6 py-4 text-right flex justify-end gap-3">
                                <Link :href="route('admin.news.edit', item.id)" class="text-blue-400 hover:text-blue-300">Edit</Link>
                                <button @click="openDeleteModal(item.id)" class="text-red-400 hover:text-red-300 font-medium">Hapus</button>
                            </td>
                        </tr>
                        <tr v-if="!news.data.length">
                            <td colspan="4" class="px-6 py-8 text-center text-gray-500">Belum ada berita.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Custom Delete Modal -->
        <div v-if="isDeleteModalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm">
            <div class="bg-gray-800 border border-gray-700 rounded-2xl p-6 max-w-sm w-full mx-4 shadow-2xl">
                <h3 class="text-lg font-bold text-white mb-2">⚠️ Konfirmasi Hapus</h3>
                <p class="text-gray-400 text-sm mb-6">Yakin nih mau hapus berita ini lek? Data yang dihapus nggak bisa dibalikin lho!</p>
                <div class="flex justify-end gap-3">
                    <button @click="isDeleteModalOpen = false" class="px-4 py-2 text-sm font-semibold text-gray-300 hover:bg-gray-700 rounded-lg transition">Batal</button>
                    <button @click="executeDelete" class="px-4 py-2 text-sm font-semibold bg-red-600 hover:bg-red-500 text-white rounded-lg transition shadow-lg shadow-red-600/30">Ya, Hapus!</button>
                </div>
            </div>
        </div>
    </div>
</template>