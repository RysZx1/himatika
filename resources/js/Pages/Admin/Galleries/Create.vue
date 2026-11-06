<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    title: '',
    activity_name: '',
    activity_date: '',
    image: null,
});

const submit = () => {
    form.post(route('admin.galleries.store'));
};
</script>

<template>
    <Head title="Upload Foto - Admin Panel" />

    <div class="min-h-screen bg-gray-900 text-gray-100 p-8">
        <div class="mx-auto max-w-3xl bg-gray-800 rounded-xl border border-gray-700 p-8">
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-white">Upload Foto Kegiatan</h1>
                <Link :href="route('admin.galleries.index')" class="text-sm text-indigo-400 hover:text-indigo-300">
                    &larr; Batal & Kembali
                </Link>
            </div>

            <form @submit.prevent="submit" class="space-y-6">
                <!-- Judul Foto -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Judul Foto / Momen</label>
                    <input 
                        v-model="form.title" 
                        type="text" 
                        placeholder="Contoh: Penyerahan Piala Juara 1"
                        class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2 text-white focus:border-indigo-500 outline-none"
                        required 
                    />
                </div>

                <!-- Nama Kegiatan -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Nama Kegiatan (Opsional)</label>
                    <input 
                        v-model="form.activity_name" 
                        type="text" 
                        placeholder="Contoh: Lomba Koding Nasional"
                        class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2 text-white focus:border-indigo-500 outline-none"
                    />
                </div>

                <!-- Tanggal Kegiatan -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Tanggal Kegiatan (Opsional)</label>
                    <input 
                        v-model="form.activity_date" 
                        type="date" 
                        class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2 text-white focus:border-indigo-500 outline-none"
                    />
                </div>

                <!-- File Foto -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">File Foto (Wajib)</label>
                    <input 
                        type="file" 
                        @input="form.image = $event.target.files[0]" 
                        accept="image/*"
                        class="block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-gray-700 file:text-white hover:file:bg-gray-600"
                        required
                    />
                </div>

                <!-- Submit -->
                <div class="pt-4 flex justify-end">
                    <button 
                        type="submit" 
                        :disabled="form.processing"
                        class="bg-indigo-600 hover:bg-indigo-500 text-white px-6 py-2.5 rounded-lg font-bold transition disabled:opacity-50"
                    >
                        Upload Foto
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>