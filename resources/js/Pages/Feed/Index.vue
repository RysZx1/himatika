<script setup>
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const currentUser = computed(() => page.props.auth?.user);

defineProps({
    posts: Object,
});

// Form Tambah Postingan Baru
const form = useForm({
    content: '',
    image: null,
});

const submitPost = () => {
    form.post(route('feed.store'), {
        onSuccess: () => form.reset(),
    });
};

// Form Tambah Komentar
const commentForms = {};

const submitComment = (postId) => {
    if (!commentForms[postId]) return;
    
    commentForms[postId].post(route('feed.comment', postId), {
        onSuccess: () => commentForms[postId].reset(),
    });
};

const getCommentForm = (postId) => {
    if (!commentForms[postId]) {
        commentForms[postId] = useForm({ comment: '' });
    }
    return commentForms[postId];
};

const formatDate = (dateString) => {
    if (!dateString) return '';
    return new Date(dateString).toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'short',
        hour: '2-digit',
        minute: '2-digit',
    });
};
</script>

<template>
    <Head title="Community Feed - HIMATIKA" />

    <div class="min-h-screen bg-gray-900 text-gray-100">
        <!-- Topbar -->
        <header class="sticky top-0 z-50 border-b border-gray-800 bg-gray-900/90 backdrop-blur">
            <div class="mx-auto flex max-w-4xl items-center justify-between px-6 py-4">
                <div class="flex items-center gap-3">
                    <Link :href="route('dashboard')" class="flex h-9 w-9 items-center justify-center rounded-xl bg-indigo-600 font-bold text-white shadow-lg">
                        HM
                    </Link>
                    <span class="text-xl font-bold text-white">Student Community Feed</span>
                </div>
                <Link :href="route('dashboard')" class="text-sm font-medium text-gray-400 hover:text-white transition">
                    ← Kembali ke Dashboard
                </Link>
            </div>
        </header>

        <main class="mx-auto max-w-3xl px-4 py-8">
            <!-- Form Buat Postingan Baru -->
            <div class="mb-8 rounded-2xl border border-gray-800 bg-gray-800/40 p-6 backdrop-blur">
                <h2 class="text-lg font-bold text-white mb-4">💬 Buat Postingan Baru</h2>
                <form @submit.prevent="submitPost">
                    <textarea
                        v-model="form.content"
                        rows="3"
                        placeholder="Apa yang lagi lu pikirin/tanyain seputar kampus & informatika, lek?"
                        class="w-full rounded-xl border border-gray-700 bg-gray-900 p-4 text-sm text-white placeholder-gray-500 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"
                        required
                    ></textarea>

                    <div class="mt-4 flex items-center justify-between">
                        <input
                            type="file"
                            @input="form.image = $event.target.files[0]"
                            accept="image/*"
                            class="text-xs text-gray-400 file:mr-4 file:rounded-lg file:border-0 file:bg-gray-700 file:px-3 file:py-2 file:text-xs file:font-semibold file:text-gray-200 hover:file:bg-gray-600"
                        />
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="rounded-xl bg-indigo-600 px-6 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-500 disabled:opacity-50 shadow-lg shadow-indigo-600/30"
                        >
                            Publish Post
                        </button>
                    </div>
                </form>
            </div>

            <!-- List Feed Postingan -->
            <div v-if="posts.data && posts.data.length > 0" class="space-y-6">
                <div v-for="post in posts.data" :key="post.id" class="rounded-2xl border border-gray-800 bg-gray-800/30 p-6">
                    <!-- User Header -->
                    <div class="flex items-start justify-between">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-indigo-500/20 text-indigo-400 font-bold">
                                {{ post.user?.name?.charAt(0) || 'U' }}
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <h3 class="font-bold text-white text-sm">{{ post.user?.name }}</h3>
                                    <span class="rounded bg-indigo-500/10 px-2 py-0.5 text-[10px] font-semibold text-indigo-400 uppercase">
                                        {{ post.user?.role }}
                                    </span>
                                </div>
                                <span class="text-xs text-gray-500">{{ formatDate(post.created_at) }}</span>
                            </div>
                        </div>

                        <!-- Tombol Hapus (Hanya untuk pemilik post atau Admin) -->
                        <Link
                            v-if="currentUser && (currentUser.id === post.user_id || ['super_admin', 'pengurus'].includes(currentUser.role))"
                            :href="route('feed.destroy', post.id)"
                            method="delete"
                            as="button"
                            class="text-xs text-red-400 hover:text-red-300"
                        >
                            Hapus
                        </Link>
                    </div>

                    <!-- Konten Post -->
                    <p class="mt-4 text-sm leading-relaxed text-gray-300 whitespace-pre-line">{{ post.content }}</p>

                    <!-- Gambar Post (Jika Ada) -->
                    <div v-if="post.image_path" class="mt-4 overflow-hidden rounded-xl border border-gray-800">
                        <img :src="`/storage/${post.image_path}`" alt="Post Image" class="max-h-96 w-full object-cover" />
                    </div>

                    <!-- Tombol Like & Info Komentar -->
                    <div class="mt-6 flex items-center gap-6 border-t border-gray-800/80 pt-4 text-xs">
                        <Link
                            :href="route('feed.like', post.id)"
                            method="post"
                            as="button"
                            class="flex items-center gap-2 font-medium text-gray-400 hover:text-indigo-400 transition"
                        >
                            <span>❤️ {{ post.likes_count }} Like</span>
                        </Link>
                        <span class="text-gray-400">💬 {{ post.comments_count }} Komentar</span>
                    </div>

                    <!-- Section Komentar -->
                    <div class="mt-4 border-t border-gray-800/60 pt-4 space-y-3">
                        <!-- List Komentar -->
                        <div v-for="comment in post.comments" :key="comment.id" class="rounded-xl bg-gray-900/40 p-3 text-xs">
                            <div class="flex items-center justify-between">
                                <span class="font-semibold text-indigo-400">{{ comment.user?.name }}</span>
                                <span class="text-[10px] text-gray-500">{{ formatDate(comment.created_at) }}</span>
                            </div>
                            <p class="mt-1 text-gray-300">{{ comment.comment }}</p>
                        </div>

                        <!-- Input Tambah Komentar -->
                        <form @submit.prevent="submitComment(post.id)" class="flex gap-2 pt-2">
                            <input
                                v-model="getCommentForm(post.id).comment"
                                type="text"
                                placeholder="Tulis komentar..."
                                class="flex-1 rounded-lg border border-gray-700 bg-gray-900 px-3 py-2 text-xs text-white placeholder-gray-500 focus:border-indigo-500 focus:outline-none"
                                required
                            />
                            <button
                                type="submit"
                                class="rounded-lg bg-gray-700 px-4 py-2 text-xs font-semibold text-white hover:bg-indigo-600 transition"
                            >
                                Kirim
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div v-else class="rounded-2xl border border-dashed border-gray-800 p-12 text-center text-gray-500">
                Belum ada postingan. Jadilah yang pertama posting di feed!
            </div>
        </main>
    </div>
</template>