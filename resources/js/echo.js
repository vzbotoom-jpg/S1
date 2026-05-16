/**
 * echo.js
 * Inisialisasi Laravel Echo dengan Pusher untuk realtime broadcasting.
 * File ini harus di-load SEBELUM chat.js.
 */

import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

// Pasang Pusher ke window agar Echo bisa menggunakannya
window.Pusher = Pusher;

/**
 * Buat instance Echo hanya jika konfigurasi Pusher tersedia.
 */
const pusherKey     = window.ChatConfig?.pusherKey;
const pusherCluster = window.ChatConfig?.pusherCluster;

if (pusherKey) {
    window.Echo = new Echo({
        broadcaster:   'pusher',
        key:            pusherKey,
        cluster:        pusherCluster || 'ap1',
        forceTLS:       true,
        encrypted:      true,
        authEndpoint:   '/broadcasting/auth',
        auth: {
            headers: {
                'X-CSRF-TOKEN': window.ChatConfig?.csrfToken
                    ?? document.querySelector('meta[name="csrf-token"]')?.content,
            },
        },
    });

    console.log('[Echo] Laravel Echo berhasil diinisialisasi.');
} else {
    console.warn('[Echo] Pusher key tidak ditemukan. Realtime broadcasting dinonaktifkan.');
    // Buat dummy Echo agar chat.js tidak error saat memanggil window.Echo
    window.Echo = {
        private: () => ({
            listen: () => ({}),
            stopListening: () => ({}),
        }),
        leave: () => {},
    };
}

export default window.Echo;