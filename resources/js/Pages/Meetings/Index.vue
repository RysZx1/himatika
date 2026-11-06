<script setup>
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const user = computed(() => page.props.auth?.user);

defineProps({
    meetings: Object,
});

const form = useForm({
    title: '',
    meeting_date: '',
    location: '',
    description: '',
    target_role: 'pengurus', // Default internal
    minutes_file: null,
});

const submit = () => {
    form.post(route('meetings.store'), {
        onSuccess: () => form.reset(),
    });
};

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('id-ID', {
        day: 'numeric', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit'
    });
};
</script>

<template>
    <Head title="Informasi Rapat - HIMATIKA" />

    <div class="min-h-screen bg-gray-900 text-gray-100">
        <header class="border-b border-gray-800 bg-gray-900/90 py-4 px-6 sticky top-0 z-50 flex justify-between items-center">
            <h1 class="text-xl font-bold text-white">📅 Informasi & Notulensi Rapat</h1>
            <Link :href="route('dashboard')" class="text-sm text-gray-400 hover:text-white transition">
                &larr; Kembali ke Dashboard
            </Link>
        </header>

        <main class="mx-auto max-w-5xl px-6 py-10 grid gap-8 md:grid-cols-3">
            <!-- Kolom List Rapat (Kiri - Makan 2 Kolom) -->
            <div class="md:col-span-2 space-y-6">
                <div v-if="meetings.data && meetings.data.length > 0">
                    <div v-for="meeting in meetings.data" :key="meeting.id" class="bg-gray-800/40 border border-gray-800 rounded-2xl p-6 mb-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-xl font-bold text-white">{{ meeting.title }}</h3>
                                <p class="text-sm text-gray-400 mt-1">Oleh: {{ meeting.creator?.name }}</p>
                            </div>
                            <span :class="meeting.target_role === 'all' ? 'bg-emerald-500/20 text-emerald-400' : 'bg-red-500/20 text-red-400'" class="px-3 py-1 rounded-full text-xs font-bold uppercase">
                                {{ meeting.target_role === 'all' ? 'Terbuka' : 'Internal Pengurus' }}
                            </span>
                        </div>
                        <div class="mt-4 text-sm text-gray-300">
                            <p><strong>Waktu:</strong> {{ formatDate(meeting.meeting_date) }}</p>
                            <p><strong>Lokasi:</strong> {{ meeting.location }}</p>
                            <p class="mt-2 text-gray-400">{{ meeting.description }}</p>
                        </div>
                        <!-- Tombol Download Notulensi (Jika ada file) -->
                        <div v-if="meeting.minutes_file" class="mt-4 pt-4 border-t border-gray-700/50">
                            <a :href="`/storage/${meeting.minutes_file}`" target="_blank" class="text-sm text-indigo-400 font-semibold hover:text-indigo-300 flex items-center gap-2">
                                📄 Download File Notulensi
                            </a>
                        </div>
                    </div>
                </div>
                <div v-else class="bg-gray-800/30 border border-dashed border-gray-700 rounded-2xl p-12 text-center text-gray-500">
                    Belum ada agenda rapat yang dijadwalkan.
                </div>
            </div>

            <!-- Kolom Form Tambah Rapat (Kanan - Khusus Admin/Pengurus) -->
            <div v-if="user && ['super_admin', 'pengurus'].includes(user.role)">
                <div class="bg-gray-800/60 border border-gray-700 rounded-2xl p-6 sticky top-24">
                    <h2 class="text-lg font-bold text-white mb-4">+ Tambah Agenda Rapat</h2>
                    
                    <form @submit.prevent="submit" class="space-y-4">
                        <div>
                            <label class="block text-xs text-gray-400 mb-1">Judul Rapat</label>
                            <input v-model="form.title" type="text" class="w-full bg-gray-900 border border-gray-700 rounded-lg p-2.5 text-sm text-white focus:border-indigo-500" required />
                        </div>
                        <div>
                            <label class="block text-xs text-gray-400 mb-1">Waktu</label>
                            <input v-model="form.meeting_date" type="datetime-local" class="w-full bg-gray-900 border border-gray-700 rounded-lg p-2.5 text-sm text-white focus:border-indigo-500" required />
                        </div>
                        <div>
                            <label class="block text-xs text-gray-400 mb-1">Lokasi / Link Meet</label>
                            <input v-model="form.location" type="text" class="w-full bg-gray-900 border border-gray-700 rounded-lg p-2.5 text-sm text-white focus:border-indigo-500" required />
                        </div>
                        <div>
                            <label class="block text-xs text-gray-400 mb-1">Hak Akses</label>
                            <select v-model="form.target_role" class="w-full bg-gray-900 border border-gray-700 rounded-lg p-2.5 text-sm text-white focus:border-indigo-500">
                                <option value="pengurus">Internal Pengurus</option>
                                <option value="all">Terbuka (Semua Mahasiswa)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs text-gray-400 mb-1">Deskripsi Singkat</label>
                            <textarea v-model="form.description" rows="2" class="w-full bg-gray-900 border border-gray-700 rounded-lg p-2.5 text-sm text-white focus:border-indigo-500"></textarea>
                        </div>
                        <div>
                            <label class="block text-xs text-gray-400 mb-1">File Notulensi (PDF/Doc - Opsional)</label>
                            <input type="file" @input="form.minutes_file = $event.target.files[0]" accept=".pdf,.doc,.docx" class="text-xs text-gray-400 file:rounded file:border-0 file:bg-gray-700 file:text-white file:px-2 file:py-1" />
                        </div>
                        <button type="submit" :disabled="form.processing" class="w-full bg-indigo-600 hover:bg-indigo-500 text-white p-2.5 rounded-lg text-sm font-bold transition disabled:opacity-50 mt-2">
                            Simpan Agenda
                        </button>
                    </form>
                </div>
            </div>
        </main>
    </div>
</template>