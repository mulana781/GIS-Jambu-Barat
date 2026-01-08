<?php
session_start();
if (empty($_SESSION['user'])) {
    header('Location: /GIS/admin/login.php');
    exit;
}

$host = '127.0.0.1';
$db   = 'gis_jambu';
$user = 'root';
$pass = '';
$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";

try {
    $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
} catch (Exception $e) {
    echo 'DB error: ' . htmlspecialchars($e->getMessage());
    exit;
}

$stmt = $pdo->query('SELECT * FROM fasilitas ORDER BY id DESC');
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total = count($rows);
$categoryCounts = [
    'education' => 0,
    'health' => 0,
    'worship' => 0,
    'public' => 0,
];
foreach ($rows as $r) {
    $cat = $r['category'] ?? 'public';
    if (!isset($categoryCounts[$cat])) {
        $categoryCounts[$cat] = 0;
    }
    $categoryCounts[$cat]++;
}
?>
<!doctype html>
<html lang="id">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Dashboard - GIS Jambu</title>
    <link rel="stylesheet" href="./css/admin.css?v=1.0" />
  </head>
  <body>
    <div class="container">
      <div class="top">
        <div class="brand">
          <img src="/GIS/web/img/logo.svg" alt="logo" />
          <div>
            <h1>Dashboard</h1>
            <div class="muted">Signed in as <?php echo htmlspecialchars($_SESSION['user']['username']); ?></div>
          </div>
        </div>
        <div class="actions">
          <a class="btn" href="/GIS/web/" target="_blank" rel="noopener">Buka MAPS</a>
          <a class="btn ghost" href="/GIS/api/auth_logout.php" id="logout">Logout</a>
        </div>
      </div>

      <div class="stats">
        <div class="stat">
          <div class="label">Total Fasilitas</div>
          <div class="value"><?php echo $total; ?></div>
        </div>
        <div class="stat">
          <div class="label">Pendidikan</div>
          <div class="value"><?php echo $categoryCounts['education']; ?></div>
        </div>
        <div class="stat">
          <div class="label">Kesehatan</div>
          <div class="value"><?php echo $categoryCounts['health']; ?></div>
        </div>
        <div class="stat">
          <div class="label">Ibadah / Lainnya</div>
          <div class="value"><?php echo $categoryCounts['worship'] + $categoryCounts['public']; ?></div>
        </div>
      </div>

      <div class="filters">
        <input id="q" type="text" placeholder="Cari fasilitas..." />
        <a class="btn ghost" id="exportCsv" href="#">Export CSV</a>
      </div>

      <div class="table-wrapper">
        <table class="table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Nama</th>
              <th>Kategori</th>
              <th>Lat</th>
              <th>Lng</th>
              <th>Deskripsi</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody id="table-body">
            <?php foreach ($rows as $row): ?>
              <tr data-id="<?php echo (int)$row['id']; ?>">
                <td><?php echo (int)$row['id']; ?></td>
                <td class="cell-name"><?php echo htmlspecialchars($row['name']); ?></td>
                <td class="cell-cat">
                  <span class="badge badge-<?php echo htmlspecialchars($row['category']); ?>">
                    <?php echo htmlspecialchars($row['category']); ?>
                  </span>
                </td>
                <td class="cell-lat"><?php echo htmlspecialchars($row['lat']); ?></td>
                <td class="cell-lng"><?php echo htmlspecialchars($row['lng']); ?></td>
                <td class="cell-desc"><?php echo htmlspecialchars($row['description']); ?></td>
                <td>
                  <button class="btn small" type="button" onclick="openEditModal(<?php echo (int)$row['id']; ?>)">Edit</button>
                  <button class="btn small danger" type="button" onclick="del(<?php echo (int)$row['id']; ?>)">Hapus</button>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>

    <div id="toast" class="toast"></div>

    <div id="modalBackdrop" class="modal-backdrop">
      <div class="modal">
        <h3>Edit Fasilitas</h3>
        <form id="modalForm">
          <input type="hidden" id="m-id" />
          <div class="form-row">
            <div style="flex:1">
              <label for="m-name">Nama</label>
              <input id="m-name" type="text" required />
            </div>
          </div>
          <div class="form-row">
            <div style="flex:1">
              <label for="m-cat">Kategori</label>
              <select id="m-cat">
                <option value="education">Pendidikan</option>
                <option value="health">Kesehatan</option>
                <option value="worship">Tempat Ibadah</option>
                <option value="public">Lainnya</option>
              </select>
            </div>
            <div style="flex:1">
              <label for="m-lat">Lat</label>
              <input id="m-lat" type="number" step="any" required />
            </div>
            <div style="flex:1">
              <label for="m-lng">Lng</label>
              <input id="m-lng" type="number" step="any" required />
            </div>
          </div>
          <div class="form-row">
            <div style="flex:1">
              <label for="m-desc">Deskripsi</label>
              <textarea id="m-desc" rows="3"></textarea>
            </div>
          </div>
          <div class="form-actions">
            <button type="button" class="btn ghost" id="modalCancel">Batal</button>
            <button type="submit" class="btn">Simpan</button>
          </div>
        </form>
      </div>
    </div>

    <script>
      function showToast(msg) {
        const t = document.getElementById('toast');
        t.textContent = msg;
        t.style.display = 'block';
        setTimeout(() => (t.style.display = 'none'), 2200);
      }

      function openEditModalPopulate(data) {
        document.getElementById('m-id').value = data.id;
        document.getElementById('m-name').value = data.name || '';
        document.getElementById('m-cat').value = data.category || 'public';
        document.getElementById('m-lat').value = data.lat || '';
        document.getElementById('m-lng').value = data.lng || '';
        document.getElementById('m-desc').value = data.description || '';
        document.getElementById('modalBackdrop').style.display = 'flex';
      }

      function openEditModal(id) {
        const row = document.querySelector('#table-body tr[data-id="' + id + '"]');
        if (row) {
          const data = {
            id: id,
            name: row.querySelector('.cell-name').textContent.trim(),
            category: row.querySelector('.cell-cat').textContent.trim(),
            lat: row.querySelector('.cell-lat').textContent.trim(),
            lng: row.querySelector('.cell-lng').textContent.trim(),
            description: row.querySelector('.cell-desc').textContent.trim(),
          };
          openEditModalPopulate(data);
        } else {
          fetch('/GIS/api/facilities.php?id=' + encodeURIComponent(id))
            .then((r) => r.json())
            .then((d) => openEditModalPopulate(d))
            .catch(() => alert('Gagal ambil data'));
        }
      }

      function closeModal() {
        document.getElementById('modalBackdrop').style.display = 'none';
      }

      document.getElementById('modalCancel').addEventListener('click', function () {
        closeModal();
      });

      document.getElementById('modalForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const id = document.getElementById('m-id').value;
        const payload = {
          name: document.getElementById('m-name').value.trim(),
          category: document.getElementById('m-cat').value,
          lat: parseFloat(document.getElementById('m-lat').value),
          lng: parseFloat(document.getElementById('m-lng').value),
          description: document.getElementById('m-desc').value.trim(),
        };
        fetch('/GIS/api/facilities.php?id=' + encodeURIComponent(id), {
          method: 'PUT',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(payload),
        })
          .then((r) => r.json())
          .then(() => {
            const row = document.querySelector('#table-body tr[data-id="' + id + '"]');
            if (row) {
              row.querySelector('.cell-name').textContent = payload.name;
              row.querySelector('.cell-cat').textContent = payload.category;
              row.querySelector('.cell-lat').textContent = payload.lat;
              row.querySelector('.cell-lng').textContent = payload.lng;
              row.querySelector('.cell-desc').textContent = payload.description;
            }
            closeModal();
            showToast('Perubahan disimpan');
          })
          .catch(() => alert('Gagal menyimpan'));
      });

      function del(id) {
        if (!confirm('Hapus fasilitas ID ' + id + '?')) return;
        fetch('/GIS/api/facilities.php?id=' + encodeURIComponent(id), { method: 'DELETE' })
          .then((r) => r.json())
          .then(() => {
            const row = document.querySelector('#table-body tr[data-id="' + id + '"]');
            if (row) row.remove();
            showToast('Terhapus');
          })
          .catch(() => alert('Error'));
      }

      document.getElementById('logout').addEventListener('click', function (e) {
        e.preventDefault();
        fetch('/GIS/api/auth_logout.php').then(() => (window.location = '/GIS/admin/login.php'));
      });

      // simple client-side filter
      document.getElementById('q').addEventListener('input', function () {
        const v = this.value.trim().toLowerCase();
        const rows = document.querySelectorAll('#table-body tr');
        rows.forEach((tr) => {
          const text = tr.textContent.toLowerCase();
          tr.style.display = text.indexOf(v) > -1 ? '' : 'none';
        });
      });

      // Export CSV
      document.getElementById('exportCsv').addEventListener('click', function (e) {
        e.preventDefault();
        const rows = Array.from(document.querySelectorAll('#table-body tr'));
        const cols = ['id', 'name', 'category', 'lat', 'lng', 'description'];
        const csv = [cols.join(',')];
        rows.forEach((r) => {
          const id = r.querySelector('td').textContent.trim();
          const name = r.querySelector('.cell-name').textContent.trim().replace(/"/g, '""');
          const cat = r.querySelector('.cell-cat').textContent.trim();
          const lat = r.querySelector('.cell-lat').textContent.trim();
          const lng = r.querySelector('.cell-lng').textContent.trim();
          const desc = r.querySelector('.cell-desc').textContent.trim().replace(/"/g, '""');
          csv.push([id, '"' + name + '"', cat, lat, lng, '"' + desc + '"'].join(','));
        });
        const blob = new Blob([csv.join('\n')], { type: 'text/csv' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'fasilitas.csv';
        a.click();
        URL.revokeObjectURL(url);
      });
    </script>
  </body>
</html>
