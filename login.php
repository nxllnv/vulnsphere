<?php
$pageTitle = 'Sign In';
$bodyClass = 'auth-page';
$extraCss  = 'auth.css';
include __DIR__ . '/../layout/header.php';
$error   = $_GET['error']   ?? '';
$success = $_GET['success'] ?? '';
$prefill = htmlspecialchars($_GET['u'] ?? '');
?>

<div class="auth-bg-wrap"></div>

<div class="page">
  <div class="auth-brand">
    <div class="brand-mark">VS</div>
    <span class="brand-name" data-text="VulnSphere">VulnSphere</span>
    <span class="brand-tagline">Share your world.</span>
  </div>

  <?php if ($error): ?>
  <div class="alert alert-error"><span class="alert-icon">!</span><span><?= $error ?></span></div>
  <?php endif; ?>

  <?php if ($success): ?>
  <div class="alert alert-success"><span class="alert-icon">✓</span><span><?= htmlspecialchars($success) ?></span></div>
  <?php endif; ?>

  <form class="form-stack" method="POST" action="/index.php?page=do-login" id="loginForm">
    <div class="field">
      <label for="username">Username</label>
      <input type="text" id="username" name="username" value="<?= $prefill ?>" placeholder="your_handle" autocomplete="username" required>
    </div>

    <div class="field">
      <label for="password">Password</label>
      <div class="input-wrap">
        <input type="password" id="password" name="password" placeholder="••••••••" autocomplete="current-password" required>
        <button type="button" class="btn-eye" onclick="togglePw('password')" aria-label="show password">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
        </button>
      </div>
    </div>

    <label class="check-label">
      <input type="checkbox" name="remember" id="remember">
      <span class="check-box"></span>
      <span>Remember me</span>
    </label>

    <button type="submit" class="btn btn-primary btn-full" id="loginBtn">Sign In →</button>
  </form>

  <hr class="divider">

  <div class="auth-hint">
    <span class="hint-tag">demo</span>
    <span class="hint-text">admin / admin123</span>
  </div>

  <div class="auth-footer" style="margin-top:16px;">
    No account? <a href="/index.php?page=register" class="link">Create one</a>
  </div>
</div>

<script>
function togglePw(id) {
  const el = document.getElementById(id);
  el.type = el.type === 'password' ? 'text' : 'password';
}
document.getElementById('loginForm').addEventListener('submit', function() {
  const btn = document.getElementById('loginBtn');
  btn.disabled = true;
  btn.textContent = 'Signing in...';
});
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>
