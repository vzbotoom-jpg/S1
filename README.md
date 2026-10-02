# NexusAI

<p align="center">
  <img src="docs/screenshots/nexusai-chat.png" alt="Antarmuka chat NexusAI" width="100%">
</p>

<p align="center">
  <strong>Platform asisten AI untuk percakapan, pengelolaan pengetahuan, dan produktivitas.</strong>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-13-red?logo=laravel" alt="Laravel 13">
  <img src="https://img.shields.io/badge/PHP-8.3%2B-777BB4?logo=php&logoColor=white" alt="PHP 8.3 atau lebih baru">
  <img src="https://img.shields.io/badge/UI-Tailwind_CSS-06B6D4?logo=tailwindcss&logoColor=white" alt="Tailwind CSS">
  <img src="https://img.shields.io/badge/AI-Google_Gemini-4285F4?logo=google" alt="Google Gemini">
</p>

## Deskripsi Proyek

NexusAI adalah aplikasi chatbot berbasis web yang menghubungkan pengguna dengan Google Gemini. Aplikasi ini menyediakan percakapan yang tersimpan, dashboard, autentikasi pengguna, serta pengelolaan akun dan administrasi.

## Fitur Utama

- Mengirim pesan ke asisten AI menggunakan Google Gemini.
- Membuat, melihat, mengubah, menandai, dan menghapus percakapan.
- Menghapus beberapa percakapan sekaligus.
- Mendaftar, masuk, memverifikasi email, dan mengatur ulang kata sandi.
- Mengelola profil, kata sandi, preferensi, dan data akun.
- Menyediakan dashboard dan halaman administrasi untuk mengelola pengguna, percakapan, dan analitik.

## Teknologi yang Digunakan

- **Backend:** PHP 8.3+, Laravel 13, Laravel Sanctum.
- **AI:** Google Gemini melalui integrasi `google-gemini-php/laravel`.
- **Frontend:** Blade, Bootstrap 5, Tailwind CSS 4, Sass, dan Vite.
- **Database:** MySQL secara default; konfigurasi Laravel juga menyediakan SQLite dan beberapa database lain.
- **Perangkat pengembangan:** Composer, Node.js, dan npm.

## Cara Instalasi dan Menjalankan Proyek

### Prasyarat

- PHP 8.3 atau lebih baru beserta ekstensi PHP yang dibutuhkan Laravel.
- Composer.
- Node.js dan npm.
- MySQL yang berjalan (atau database lain yang didukung dan sudah dikonfigurasi).
- API key Google Gemini dari [Google AI Studio](https://aistudio.google.com/app/apikey).

### Instalasi

1. Clone repositori dan masuk ke direktori proyek:

   ```bash
   git clone <URL_REPOSITORI>
   cd S1
   ```

2. Pasang dependensi PHP dan JavaScript:

   ```bash
   composer install
   npm install
   ```

3. Siapkan konfigurasi aplikasi:

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. Ubah `.env` dan sesuaikan `DB_DATABASE`, `DB_USERNAME`, serta `DB_PASSWORD` dengan database lokal. Isi `GEMINI_API_KEY` dengan API key Gemini Anda.

5. Jalankan migrasi database dan build aset frontend:

   ```bash
   php artisan migrate
   npm run build
   ```

### Menjalankan aplikasi

Jalankan server Laravel, worker antrean, log, dan Vite dalam mode pengembangan:

```bash
composer run dev
```

Aplikasi dapat diakses di `http://localhost:8000`. Untuk menjalankan pengujian:

```bash
composer test
```

## Lisensi

Proyek ini menggunakan lisensi MIT, sebagaimana tercantum pada metadata proyek di `composer.json`.
