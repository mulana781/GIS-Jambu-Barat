<?php
// Simple admin login page that calls /GIS/api/auth_login.php
?>
<!doctype html>
<html lang="id">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Admin Login - GIS Jambu</title>
    <link rel="stylesheet" href="./css/admin.css?v=1.0" />
  </head>
  <body class="auth-page">
    <div class="auth-shell">
      <div class="auth-hero">
        <div class="hero-overlay"></div>
        <div class="hero-content">
          <div class="pill">Dashboard Admin</div>
          <h1>SIG Desa Jambu Barat</h1>
          <p>
            Kelola data spasial desa dengan aman: perbarui fasilitas umum, batas desa, dan
            pantau informasi terbaru untuk publik.
          </p>
          <ul>
            <li>🔐 Akses aman & session login</li>
            <li>📍 Kelola data fasilitas dan batas</li>
            <li>🗺️ Sinkron dengan peta publik</li>
          </ul>
        </div>
      </div>

      <div class="auth-card glass">
        <div class="auth-brand">
          <img src="/GIS/web/img/logo.svg" alt="logo" />
          <div>
            <div class="auth-title">SIG Desa Jambu Barat</div>
            <div class="auth-subtitle">Masuk ke Dashboard</div>
          </div>
        </div>
        <form id="loginForm" class="auth-form card-padding">
          <div class="input-group">
            <label for="username">Username</label>
            <div class="input-wrap">
              <span class="input-icon">👤</span>
              <input id="username" name="username" autocomplete="username" required />
            </div>
          </div>

          <div class="input-group">
            <label for="password">Password</label>
            <div class="input-wrap">
              <span class="input-icon">🔒</span>
              <input id="password" name="password" type="password" autocomplete="current-password" required />
              <button type="button" id="togglePass" class="toggle-pass" aria-label="Tampilkan password">👁️</button>
            </div>
          </div>

          <button type="submit" class="btn full btn-with-spinner">
            <span class="btn-text">Login</span>
            <span class="spinner" aria-hidden="true"></span>
          </button>
          <div id="msg" class="auth-msg"></div>
        </form>
      </div>
    </div>

    <script>
      const form = document.getElementById('loginForm');
      const msg = document.getElementById('msg');
      const btn = form.querySelector('button[type="submit"]');
      const btnText = btn.querySelector('.btn-text');
      const spinner = btn.querySelector('.spinner');
      const passInput = document.getElementById('password');
      const togglePass = document.getElementById('togglePass');

      togglePass.addEventListener('click', () => {
        const isHidden = passInput.type === 'password';
        passInput.type = isHidden ? 'text' : 'password';
        togglePass.textContent = isHidden ? '🙈' : '👁️';
      });

      form.addEventListener('submit', (e) => {
        e.preventDefault();
        msg.textContent = '';
        btn.disabled = true;
        btn.classList.add('loading');
        btnText.textContent = 'Memproses...';

        fetch('/GIS/api/auth_login.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({
            username: document.getElementById('username').value.trim(),
            password: passInput.value,
          }),
        })
          .then((r) => r.json())
          .then((j) => {
            if (j && j.ok) {
              window.location = '/GIS/admin/dashboard.php';
            } else {
              msg.textContent = j.error || 'Login gagal';
            }
          })
          .catch(() => {
            msg.textContent = 'Network error';
          })
          .finally(() => {
            btn.disabled = false;
            btn.classList.remove('loading');
            btnText.textContent = 'Login';
          });
      });
    </script>
  </body>
</html>
