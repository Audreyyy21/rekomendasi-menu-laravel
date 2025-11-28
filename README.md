## ⚡ Cara Installation

# 1. Install Dependencies
composer install, npm install

# 2. Setup Environment
cp .env.example .env, php artisan key:generate

# --- PENTING: Buat database di phpMyAdmin & atur file .env sebelum lanjut ---

# 3. Migrasi Database & Seed Data
php artisan migrate --seed

# 4. Jalankan Aplikasi
npm run dev
# (Buka terminal baru untuk menjalankan server)
php artisan serve
