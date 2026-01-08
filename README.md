
# GIS Sederhana (PHP + Leaflet)

Ringkasan:
Proyek ini adalah contoh aplikasi GIS sederhana berbasis PHP untuk menampilkan peta interaktif, boundary wilayah (GeoJSON), dan marker fasilitas. Dirancang untuk berjalan pada lingkungan lokal seperti Laragon atau XAMPP.

Fitur utama:
- Peta interaktif (Leaflet) dengan boundary GeoJSON dan marker fasilitas
- Endpoint API ringan untuk mengambil data marker dan boundary
- Panel admin sederhana untuk otentikasi

Teknologi:
- PHP (backend ringan)
- MySQL / MariaDB (opsional untuk menyimpan fasilitas)
- Leaflet (frontend peta)

Persyaratan:
- PHP 7.2+ (disarankan 7.4+)
- Web server lokal (Laragon, XAMPP, atau setara)
- MySQL / MariaDB jika ingin menggunakan fitur persistence

Quick Start (lokal)
1. Pastikan Laragon atau XAMPP berjalan.
2. Letakkan seluruh folder proyek di folder web server, misal `C:\laragon\www\GIS`.
3. Jika menggunakan database, import skema: jalankan SQL pada `api/schema.sql` dan `api/schema_users.sql`.
	 - Contoh (via terminal MySQL):

```
mysql -u root -p < api\schema.sql
mysql -u root -p < api\schema_users.sql
```

4. Edit konfigurasi koneksi database jika perlu: buka `api/db.php` dan sesuaikan kredensial.
5. Akses aplikasi di browser:
- Halaman PHP (dynamic): `http://localhost/GIS/public/peta.php`
- Versi statis contoh: `http://localhost/GIS/web/index.html`

Konfigurasi (singkat)
- `api/db.php` : file koneksi database. Jika Anda memakai konfigurasi lain, sesuaikan nama host, user, password, dan nama database.
- GeoJSON batas wilayah: `batas desa.geojson` berada di root proyek dan digunakan oleh endpoint boundary.

Endpoint API (contoh)
- `api/get_markers.php`
	- Method: GET
	- Respons: JSON array marker { id, name, lat, lng, type }
	- Contoh curl:

```
curl "http://localhost/GIS/api/get_markers.php"
```

- `api/get_boundaries.php`
	- Method: GET
	- Respons: GeoJSON (FeatureCollection)
	- Contoh curl:

```
curl "http://localhost/GIS/api/get_boundaries.php"
```

- Auth (admin):
	- `api/auth_login.php` : POST (email/password) -> sesi PHP
	- `api/auth_check.php` : GET -> validasi sesi
	- `api/auth_logout.php` : GET/POST -> destroy sesi

Struktur proyek (ringkas)
- `api/` : endpoint backend dan skema SQL
- `public/` : halaman PHP yang dipakai untuk deployment
- `web/` : versi statis / demo (HTML + JS + CSS)
- `admin/` : panel admin, halaman login dan asset
- `batas desa.geojson` : data GeoJSON boundary

Tips setup cepat
- Jika tidak ingin menggunakan DB, Anda dapat menyesuaikan `api/get_markers.php` untuk membaca data marker dari file JSON statis di `web/` atau root.
- Pastikan folder dan file dapat diakses oleh web server (permission pada Linux/macOS).

Kontribusi
- Fork repository ini dan buat branch untuk perubahan fitur
- Buat PR dengan penjelasan singkat dan langkah untuk menguji perubahan

Lanjutan yang bisa ditambahkan
- Contoh file konfigurasi `.env` dan skrip import SQL otomatis
- Dokumentasi API lebih lengkap (OpenAPI / Postman collection)

Lisensi
Tambahkan file `LICENSE` sesuai kebutuhan (mis. MIT).



