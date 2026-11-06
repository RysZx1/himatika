<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    title: '',
    category: 'umum',
    content: '',
    thumbnail: null,
});

const submit = () => {
    form.post(route('admin.news.store'));
};
</script>

<template>
    <Head title="Tambah Berita - Admin Panel" />

    <div class="min-h-screen bg-gray-900 text-gray-100 p-8">
        <div class="mx-auto max-w-3xl bg-gray-800 rounded-xl border border-gray-700 p-8">
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-white">Tambah Berita Baru</h1>
                <Link :href="route('admin.news.index')" class="text-sm text-indigo-400 hover:text-indigo-300">
                    &larr; Batal & Kembali
                </Link>
            </div>

            <form @submit.prevent="submit" class="space-y-6">
                <!-- Judul -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Judul Berita</label>
                    <input 
                        v-model="form.title" 
                        type="text" 
                        class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2 text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none"
                        required 
                    />
                    <div v-if="form.errors.title" class="mt-1 text-sm text-red-400">{{ form.errors.title }}</div>
                </div>

                <!-- Kategori -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Kategori</label>
                    <select 
                        v-model="form.category" 
                        class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2 text-white focus:border-indigo-500 outline-none"
                    >
                        <option value="kegiatan">Kegiatan</option>
                        <option value="teknologi">Teknologi</option>
                        <option value="kampus">Kampus</option>
                        <option value="umum">Umum</option>
                    </select>
                </div>

                <!-- Konten -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Isi Konten</label>
                    <textarea 
                        v-model="form.content" 
                        rows="6" 
                        class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2 text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none"
                        required 
                    ></textarea>
                    <div v-if="form.errors.content" class="mt-1 text-sm text-red-400">{{ form.errors.content }}</div>
                </div>

                <!-- Thumbnail -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Thumbnail Foto (Opsional)</label>
                    <input 
                        type="file" 
                        @input="form.thumbnail = $event.target.files[0]" 
                        accept="image/*"
                        class="block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-gray-700 file:text-white hover:file:bg-gray-600"
                    />
                </div>

                <!-- Submit Button -->
                <div class="pt-4 flex justify-end">
                    <button 
                        type="submit" 
                        :disabled="form.processing"
                        class="bg-indigo-600 hover:bg-indigo-500 text-white px-6 py-2.5 rounded-lg font-bold transition disabled:opacity-50"
                    >
                        Simpan Berita
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>