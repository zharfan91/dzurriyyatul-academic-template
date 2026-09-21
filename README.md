<div align="center">

# 🎓 Dzurriyyatul Qur'an Academic
### Tema WordPress Kustom

**Pusat Konsultasi & Pendampingan Akademik**
*"Mendampingi Proses, Mengembangkan Kompetensi."*

</div>

---

Tema produksi kustom untuk **DZURRIYYATUL QUR'AN ACADEMIC**, dibangun dari desain Google Stitch yang telah disetujui (`design/code.html` + `design/DESIGN.md`) sesuai master prompt Stage 1. Lihat `ANALYSIS.md` untuk audit pra-implementasi, pemetaan desain, dan alasan arsitektur secara lengkap.

| | |
|---|---|
| **Versi** | 1.0.0 |
| **Lisensi** | GPLv2 atau yang lebih baru |
| **Kebutuhan WordPress** | 6.4+ |
| **Kebutuhan PHP** | 8.0+ |
| **Plugin wajib** | Tidak ada — tema ini *self-contained* |

## 📑 Daftar Isi

1. [Kebutuhan Sistem](#-kebutuhan-sistem)
2. [Instalasi](#-instalasi)
3. [Checklist Sebelum Rilis](#-checklist-sebelum-rilis)
4. [Manajemen Konten](#-manajemen-konten)
5. [Konfigurasi WhatsApp](#-konfigurasi-whatsapp)
6. [Konfigurasi SEO](#-konfigurasi-seo)
7. [Alur Pengembangan](#-alur-pengembangan)
8. [Pengujian](#-pengujian)
9. [Penerapan (Deployment)](#-penerapan-deployment)

---

## 🧩 Kebutuhan Sistem

- WordPress **6.4** atau lebih baru
- PHP **8.0** atau lebih baru
- Database MySQL/MariaDB yang aktif (tema tidak bergantung pada layanan eksternal apa pun)

> **Catatan:** Tidak ada plugin yang wajib dipasang. Semua kebutuhan — custom post type, halaman pengaturan, formulir konsultasi, dan meta SEO — sudah dibangun langsung di dalam folder `inc/`.

---

## 🚀 Instalasi

1. **Salin tema** — copy folder `dzurriyyatul-academic` ke `wp-content/themes/` pada instalasi WordPress Anda.
2. **Aktivasi** — buka **Appearance → Themes**, lalu aktifkan **Dzurriyyatul Qur'an Academic**. Proses aktivasi otomatis menjalankan *flush rewrite rules*, sehingga permalink `/layanan/` dan `/paket/` langsung berfungsi tanpa langkah tambahan.
3. **Identitas Situs** — buka **Appearance → Customize → Site Identity**, lalu atur Judul Situs, Tagline, Logo, dan Site Icon (favicon). Bagian ini memakai Customizer bawaan WordPress, bukan pengaturan duplikat milik tema.
4. **Pengaturan Akademik** — buka **Appearance → Customize → Pengaturan Situs Akademik**, lalu lengkapi:
   - Nomor WhatsApp + pesan default
   - Email, telepon, alamat, dan jam operasional
   - Tautan media sosial (Instagram, Facebook, TikTok, YouTube, Google Maps)
   - Informasi legalitas (nama yayasan, nomor AHU, tanggal SK)
   - 3 slide hero (badge, judul, tagline, tag, deskripsi, CTA, gambar, metrik, kutipan)
5. **Menu Navigasi** — buka **Appearance → Menus**, lalu (opsional) buat menu untuk lokasi **Navigasi Utama** dan **Navigasi Footer**. Jika dilewati, halaman depan otomatis memakai navigasi anchor dalam-halaman yang sama seperti desain yang disetujui (Beranda/Layanan/Paket/Mentor/Diagnosa/Integritas/Tentang/FAQ).

---

## ✅ Checklist Sebelum Rilis

> **Penting:** Desain sumber hanya berisi **data placeholder/demo** (nomor WhatsApp fiktif, nama mentor, testimoni, dan metrik contoh). Sesuai persyaratan integritas proyek, tidak satu pun dari data tersebut ditanam langsung (*hard-coded*) ke dalam tema ini.

Sebelum situs tayang (go-live), pastikan:

- [ ] Konten asli sudah ditambahkan di **Layanan**, **Paket**, **Mentor**, **Testimoni**, dan **FAQ** (semuanya kosong saat tema pertama kali diaktifkan).
- [ ] Semua kolom di **Appearance → Customize → Pengaturan Situs Akademik** sudah terisi, termasuk ketiga slide hero.
- [ ] Nomor WhatsApp dan informasi legalitas/AHU sudah diverifikasi langsung dengan pemilik situs sebelum dipublikasikan.
- [ ] Foto asli (visual hero, foto mentor, logo) sudah diunggah lewat Media Library — tidak ada gambar yang di-*hotlink* dari CDN placeholder.

---

## 📚 Manajemen Konten

| Konten | Lokasi | Catatan |
|---|---|---|
| Slide Hero (3) | Appearance → Customize → Hero Slides | Setiap slide punya judul, tag, CTA, gambar, kutipan, dan metrik sendiri |
| Layanan | CPT **Layanan** | Urutan lewat kolom bawaan "Order" (*page-attributes*); tandai unggulan lewat centang di "Detail Layanan" |
| Paket | CPT **Paket** | Harga berupa angka biasa; tema otomatis memformatnya ke format Rupiah |
| Mentor | CPT **Mentor** | Gambar unggulan = foto mentor; kredensial = satu badge per baris |
| Testimoni | CPT **Testimoni** | Rating 1–5; kolom konten = isi kutipan testimoni |
| FAQ | CPT **FAQ** | Kolom konten = isi jawaban; tampil sebagai accordion yang aksesibel |
| Artikel | Pos WordPress standar | Memakai `archive.php` / `single.php` |
| Legal/kontak/sosial | Appearance → Customize → Pengaturan Situs Akademik | Menyuplai announcement bar, banner legalitas, dan footer |
| Logo/Favicon/Judul situs | Appearance → Customize → Site Identity | Bawaan WordPress, tidak diduplikasi |

Kelima custom post type mendukung kotak **Order** bawaan WordPress yang bisa diurutkan lewat drag-and-drop (via `page-attributes`), sehingga tidak diperlukan antarmuka pengurutan khusus.

---

## 💬 Konfigurasi WhatsApp

Setiap tautan WhatsApp di dalam tema dihasilkan oleh satu fungsi bantu: `dq_whatsapp_url( $message )` di `inc/helpers.php`. Fungsi ini membaca nomor dari Pengaturan Tema dan otomatis melakukan URL-encode pada pesannya.

> ⚠️ Jangan pernah menanam nomor WhatsApp atau tautan secara langsung di dalam template — selalu panggil fungsi bantu ini, sehingga nomor hanya perlu diperbarui di satu tempat.

---

## 🔍 Konfigurasi SEO

File `inc/seo.php` menghasilkan hal-hal berikut secara otomatis, tanpa perlu plugin tambahan:

- Meta description (dari excerpt, atau nilai default di Pengaturan Tema)
- URL kanonik
- Tag Open Graph + Twitter Card
- JSON-LD `Organization`/`EducationalOrganization` (khusus halaman depan, dibangun hanya dari kolom Pengaturan Tema yang sudah diisi)
- JSON-LD `FAQPage` (khusus halaman depan, dibangun hanya dari entri FAQ yang sudah dipublikasikan)

> **Tips:** Jika nantinya memasang plugin SEO khusus (Yoast, Rank Math, dll), nonaktifkan output yang tumpang tindih di `inc/seo.php` agar tidak terjadi duplikasi tag meta.

---

## 🛠️ Alur Pengembangan

- **CSS** berada di `assets/css/` dalam 5 lapisan berurutan (`tokens` → `base` → `components` → `sections` → `responsive`), yang di-enqueue sesuai urutan dependensi di `inc/enqueue.php`. Tambahkan token desain baru ke `tokens.css` terlebih dahulu — jangan pernah menanam nilai hex atau ukuran spasi langsung di file lain.
- **JS** berada di `js/` sebagai 4 modul independen tanpa dependensi (`navigation.js`, `hero.js`, `faq.js`, `main.js`), masing-masing dijaga agar elemen yang hilang cukup diabaikan (*no-op*), bukan memicu error.
- Skema field custom post type disimpan dalam satu array per topik (`dq_cpt_meta_schema()` di `inc/post-types.php`, `dq_settings_schema()`/`dq_hero_slide_schema()` di `inc/settings.php`) — tambahkan satu field di sana dan field tersebut otomatis dirender *dan* disanitasi.
- Jalankan `php -l` pada setiap file yang diubah sebelum melakukan commit; seluruh file PHP di repositori ini saat ini lolos lint bersih di PHP 8.4.

---

## 🧪 Pengujian

### Uji Positif (smoke test manual)
- [ ] Halaman depan menampilkan seluruh section secara berurutan tanpa PHP notice (`WP_DEBUG` aktif)
- [ ] Tautan navigasi desktop mengarah ke section yang tepat; drawer mobile terbuka/tertutup dan menahan fokus (*focus trap*); tombol `Escape` menutupnya
- [ ] Hero carousel: tab, dot, dan kedua pasang tombol panah semuanya berfungsi memindah slide; autoplay berjalan tiap 7 detik; berhenti saat di-hover atau saat difokus lewat keyboard; tombol panah keyboard berfungsi saat slide sedang fokus
- [ ] `prefers-reduced-motion: reduce` menonaktifkan autoplay dan langsung berganti slide tanpa animasi
- [ ] Archive dan halaman tunggal Layanan/Paket menampilkan konten CPT yang sesungguhnya; CTA WhatsApp terbuka dengan pesan yang sudah terisi
- [ ] Section Mentor dan Testimoni hanya menampilkan entri yang sudah dipublikasikan
- [ ] Accordion FAQ terbuka/tertutup lewat mouse maupun keyboard (`Tab` + `Enter`/`Space`), atribut `aria-expanded` berubah dengan benar
- [ ] Formulir konsultasi terkirim lewat AJAX dan menampilkan pesan sukses; admin menerima email
- [ ] Halaman 404 dan pencarian tanpa hasil menampilkan pesan kosong yang ramah, bukan halaman blank

### Uji Negatif / Keamanan
- [ ] Mengirim formulir konsultasi dengan hanya mengisi honeypot akan diterima secara diam-diam tanpa mengirim email
- [ ] Mengirim formulir tanpa nama/WhatsApp menampilkan pesan validasi yang sesuai, tanpa mengirim email
- [ ] Nonce AJAX yang dipalsukan/tidak ada menghasilkan kegagalan bertipe 403, bukan sukses secara diam-diam
- [ ] Menyimpan meta box CPT tanpa nonce yang valid, atau oleh pengguna tanpa kapabilitas `edit_posts`, tidak akan tersimpan
- [ ] Tag `<script>` pada field teks apa pun (judul layanan, kutipan testimoni, dll) dirender sebagai teks ter-escape yang tidak berbahaya, tidak pernah tereksekusi
- [ ] Setiap archive/grid menampilkan pesan empty-state yang jelas saat CPT-nya belum memiliki pos terpublikasi, bukan section blank atau fatal error
- [ ] Pos CPT yang berstatus draft/belum dipublikasikan tidak pernah muncul di query front-end mana pun

### Responsif / Visual
Bandingkan dengan `design/code.html` dan `design/screen.png` pada lebar: 360, 390, 430, 768, 1024, 1280, dan 1440px. Periksa tinggi header, proporsi hero, jarak antar-section, ukuran kartu, menu mobile, layout carousel, kolom footer, dan pastikan tidak ada scroll horizontal pada lebar berapa pun.

### Performa
- [ ] Gambar hero pada slide aktif dimuat secara *eager* dengan `fetchpriority="high"`; semua gambar lain memakai `loading="lazy"` dengan lebar/tinggi eksplisit untuk mencegah *layout shift*
- [ ] JS dimuat dengan `defer`; CSS dipecah menjadi beberapa lapisan yang bisa di-cache
- [ ] Jalankan Google PageSpeed Insights / Lighthouse setelah situs live di hosting sungguhan dan perbaiki regresi yang ditemukan

---

## 📦 Penerapan (Deployment)

1. Arahkan instalasi WordPress produksi ke hosting yang layak dengan HTTPS aktif.
2. Selesaikan **Checklist Sebelum Rilis** di atas.
3. Pertimbangkan plugin caching dan CDN untuk permintaan Google Fonts/Font Awesome jika target audiens memiliki koneksi internet terbatas — payload aset tema ini sudah sengaja diminimalkan (5 file CSS kecil, 4 file JS kecil, tanpa proses build).
4. Untuk penerimaan pembayaran (fase mendatang), integrasikan penyedia layanan (misalnya Midtrans/Xendit) di level plugin/gateway — tema ini sengaja **tidak** menyertakan kode pemrosesan pembayaran apa pun (§50).
