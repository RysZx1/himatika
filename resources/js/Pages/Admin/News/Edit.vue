<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    news: Object,
});

const form = useForm({
    // Trik Laravel Inertia: Kalau form ada upload file (image), method update harus diset _method: 'PUT'
    _method: 'PUT',
    title: props.news.title,
    category: props.news.category,
    content: props.news.content,
    thumbnail: null,
});

const submit = () => {
    form.post(route('admin.news.update', props.news.id));
};
</script>

<template>
    <Head title="Edit Berita - Admin Panel" />

    <div class="min-h-screen bg-gray-900 text-gray-100 p-8">
        <div class="mx-auto max-w-3xl bg-gray-800 rounded-xl border border-gray-700 p-8">
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-white">Edit Berita</h1>
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
                        class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2 text-white focus:border-indigo-500 outline-none"
                        required 
                    />
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
                        class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2 text-white focus:border-indigo-500 outline-none"
                        required 
                    ></textarea>
                </div>

                <!-- Thumbnail -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Ganti Thumbnail (Kosongkan jika tidak ingin ganti)</label>
                    <input 
                        type="file" 
                        @input="form.thumbnail = $event.target.files[0]" 
                        accept="image/*"
                        class="block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-gray-700 file:text-white hover:file:bg-gray-600"
                    />
                </div>

                <!-- Submit Button -->
                <div class="pt-4 flex justify-end">
                    <button 
                        type="submit" 
                        :disabled="form.processing"
                        class="bg-indigo-600 hover:bg-indigo-500 text-white px-6 py-2.5 rounded-lg font-bold transition disabled:opacity-50"
                    >
                        Update Berita
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>