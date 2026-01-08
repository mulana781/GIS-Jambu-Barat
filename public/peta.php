<?php
// Halaman peta publik SIG
?>
<!doctype html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Peta SIG Desa</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <link rel="stylesheet" href="./assets/css/style.css" />
  </head>
  <body class="map-page">
    <header class="nav">
      <div class="nav-brand">
        <img src="/GIS/web/img/logo.svg" alt="logo" />
        <div>
          <div class="nav-title">SIG Desa</div>
          <div class="nav-sub">Sistem Informasi Geografis Desa</div>
        </div>
      </div>
      <nav>
        <a href="./index.php">Beranda</a>
        <a href="http://localhost/GIS/web/" class="active">Peta</a>
      </nav>
    </header>

    <main class="map-container">
      <div id="map"></div>
      <div class="map-legend">
        <div class="map-legend-title">Legenda</div>
        <div class="map-legend-item"><span class="map-legend-line"></span><span>Batas Desa</span></div>
        <div class="map-legend-item"><span class="map-legend-dot"></span><span>Fasilitas</span></div>
      </div>
    </main>

    <footer class="footer">
      <div>© <?php echo date('Y'); ?> SIG Desa. Semua hak cipta.</div>
      <div>Didukung oleh Leaflet & OpenStreetMap</div>
    </footer>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="./assets/js/map.js"></script>
  </body>
</html>

