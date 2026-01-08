<?php
// Landing page publik SIG Desa
?>
<!doctype html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SIG Desa - Beranda</title>
    <link rel="stylesheet" href="./assets/css/style.css" />
  </head>
  <body>
    <header class="nav">
      <div class="nav-brand">
        <img src="/GIS/web/img/logo.svg" alt="logo" />
        <div>
          <div class="nav-title">SIG Desa</div>
          <div class="nav-sub">Sistem Informasi Geografis Desa</div>
        </div>
      </div>
      <nav>
        <a href="./index.php" class="active">Beranda</a>
        <a href="http://localhost/GIS/web/">Peta</a>
      </nav>
    </header>

    <main class="hero">
      <div class="hero-text">
        <p class="eyebrow">Sistem Informasi Geografis Desa</p>
        <h1>Desa Jambu Barat</h1>
        <p class="lead">
          Menyajikan informasi spasial desa: batas wilayah, fasilitas umum, dan data
          penting lainnya yang dapat diakses oleh masyarakat.
        </p>
        <div class="cta">
          <a class="btn" href="http://localhost/GIS/web/">Lihat Peta</a>
        </div>
      </div>
      <div class="hero-card">
        <div class="hero-card-title">Akses Cepat</div>
        <ul>
          <li><span>🗺️</span> Peta interaktif dengan marker fasilitas</li>
          <li><span>📍</span> Data batas desa terbaru</li>
          <li><span>ℹ️</span> Popup detail setiap fasilitas</li>
        </ul>
      </div>
    </main>

    <footer class="footer">
      <div>© <?php echo date('Y'); ?> SIG Desa. Semua hak cipta.</div>
      <div>Didukung oleh Leaflet & OpenStreetMap</div>
    </footer>
  </body>
</html>

