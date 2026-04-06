<?php
$pageTitle = 'Create Account';
$bodyClass = 'auth-page';
$extraCss  = 'auth.css';
include __DIR__ . '/../layout/header.php';
$error = $_GET['error'] ?? '';
?>

<div class="auth-bg-wrap"></div>

<div class="page">
  <div class="auth-brand">
    <div class="brand-mark">VS</div>
    <span class="brand-name" data-text="VulnSphere">VulnSphere</span>
    <span class="brand-tagline">Join the network.</span>
  </div>

  <?php if ($error): ?>
  <div class="alert alert-error"><span class="alert-icon">!</span><span><?= $error ?></span></div>
  <?php endif; ?>

  <form class="form-stack" method="POST" action="/index.php?page=do-register" id="regForm">
    <div class="field">
      <label for="username">Username</label>
      <input type="text" id="username" name="username" placeholder="your_handle" maxlength="30" autocomplete="username" required>
    </div>

    <div class="field">
      <label for="email">Email</label>
      <input type="email" id="email" name="email" placeholder="you@example.com" autocomplete="email" required>
    </div>

    <div class="field">
      <label for="password">Password</label>
      <div class="input-wrap">
        <input type="password" id="password" name="password" placeholder="Min 6 characters" minlength="6" autocomplete="new-password" required>
        <button type="button" class="btn-eye" onclick="togglePw('password')" aria-label="show password">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
        </button>
      </div>
      <div class="pw-bar-wrap"><div class="pw-bar" id="pwBar"></div></div>
    </div>

    <div class="field">
      <label for="bio">Bio <span style="color:var(--gray);font-weight:normal;">(optional)</span></label>
      <textarea id="bio" name="bio" placeholder="Tell the world something about yourself..." rows="3" maxlength="300"></textarea>
      <span class="field-hint" id="bioCount">0 / 300</span>
    </div>

    <button type="submit" class="btn btn-primary btn-full" id="regBtn">Create Account →</button>
  </form>

  <div class="auth-footer" style="margin-top:16px;">
    Have an account? <a href="/index.php?page=login" class="link">Sign in</a>
  </div>
</div>

<script>
function togglePw(id) {
  const el = document.getElementById(id);
  el.type = el.type === 'password' ? 'text' : 'password';
}

document.getElementById('password').addEventListener('input', function() {
  const v = this.value, bar = document.getElementById('pwBar');
  let s = 0;
  if (v.length >= 6) s++;
  if (v.length >= 12) s++;
  if (/[A-Z]/.test(v)) s++;
  if (/[0-9]/.test(v)) s++;
  if (/[^A-Za-z0-9]/.test(v)) s++;
  bar.style.width = (s / 5 * 100) + '%';
  bar.style.background = s <= 2 ? '#ff4444' : s <= 3 ? '#ffd700' : '#00e87a';
});

document.getElementById('bio').addEventListener('input', function() {
  document.getElementById('bioCount').textContent = this.value.length + ' / 300';
});

document.getElementById('regForm').addEventListener('submit', function() {
  const btn = document.getElementById('regBtn');
  btn.disabled = true;
  btn.textContent = 'Creating...';
});
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>
