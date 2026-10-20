# TahuNutrifeed — Website PHP + MySQL

Website starter untuk proyek pemanfaatan limbah ampas tahu menjadi pakan ternak.

## Fitur
- Beranda, katalog, detail produk, profil usaha, dan halaman dampak.
- Pelanggan dapat mengirim formulir pesanan tanpa membuat akun; pesanan tersimpan di database dan WhatsApp terbuka untuk konfirmasi.
- Admin dapat login, mengelola produk/harga/stok, mencatat pesanan yang datang melalui WhatsApp, mengubah status pesanan, dan mengubah profil usaha.
- Ringkasan dashboard: total produk, stok, total pesanan, dan pesanan baru/diproses.

## Cara menjalankan di XAMPP
1. Ekstrak folder `tahunutrifeed` ke `C:\xampp\htdocs\tahunutrifeed`.
2. Jalankan Apache dan MySQL.
3. Import `database.sql` melalui phpMyAdmin. Jika memakai database lama, tabel baru `orders` dan `settings` perlu dibuat/import juga.
4. Buka `http://localhost/tahunutrifeed/admin/create_admin.php`, buat akun admin kuat, lalu hapus file itu.
5. Buka `http://localhost/tahunutrifeed/` atau `http://localhost/tahunutrifeed/admin/login.php`.
6. Ganti nomor contoh `6281234567890` dengan nomor WhatsApp UD. Tahu Yaya. Nomor masih ditulis langsung pada beberapa file.
7. Ubah data produk, harga, stok, dan foto sesuai kondisi sebenarnya.

## Penting
- Produk, harga, stok, dan informasi manfaat dalam paket ini adalah contoh dan perlu diverifikasi.
- Pesanan form website tercatat otomatis. Pesanan yang masuk langsung lewat WhatsApp harus dicatat manual oleh admin melalui menu Pesanan.
- Paket ini cocok untuk uji lokal/proyek pembelajaran, belum siap produksi internet tanpa HTTPS, proteksi CSRF, pembatasan percobaan login, backup, dan audit keamanan.
