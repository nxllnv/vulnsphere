<?php
$pageTitle = 'Search';
$bodyClass = 'search-page';
include __DIR__ . '/layout/header.php';
$q     = $_GET['q'] ?? '';
$error = $error ?? '';
?>

<div class="page">

  <div class="section-head">
    <span class="section-title">Search Users</span>
  </div>

  <form class="search-bar" method="GET" action="/index.php">
    <input type="hidden" name="page" value="search">
    <div class="field" style="flex:1">
      <input type="text" name="q" value="<?= htmlspecialchars($q) ?>" placeholder="Search by username..." id="searchInput" autocomplete="off" autofocus>
    </div>
    <button type="submit" class="btn btn-primary btn-sm" style="align-self:flex-end;">Search</button>
  </form>

  <?php if ($error): ?>
  <div class="alert alert-error"><span class="alert-icon">!</span><span><?= $error ?></span></div>
  <?php endif; ?>

  <?php if (!empty($q)): ?>
  <p style="font-family:var(--mono);font-size:0.72rem;color:var(--gray);margin-bottom:14px;">
    Results for: <strong style="color:var(--text)"><?= $q ?></strong>
  </p>

  <?php if (empty($users)): ?>
  <div class="empty"><div class="empty-icon">∅</div>No users found for "<?= htmlspecialchars($q) ?>"</div>
  <?php else: ?>
  <?php foreach ($users as $u): ?>
  <a href="/index.php?page=profile&u=<?= htmlspecialchars($u['username']) ?>" class="user-card">
    <img src="<?= getAvatarUrl($u['avatar']) ?>" class="avatar-sm" alt="av">
    <div class="user-card-info">
      <div class="user-card-handle">@<?= htmlspecialchars($u['username']) ?></div>
      <?php if (!empty($u['bio'])): ?>
      <div class="user-card-bio"><?= htmlspecialchars(substr(strip_tags($u['bio']), 0, 80)) ?></div>
      <?php endif; ?>
    </div>
    <span class="user-card-arrow">→</span>
  </a>
  <?php endforeach; ?>
  <?php endif; ?>

  <?php else: ?>
  <div class="quick-tags">
    <span style="font-family:var(--mono);font-size:0.70rem;color:var(--gray);margin-right:4px;">Try:</span>
    <a href="?page=search&q=alice" class="qtag">alice</a>
    <a href="?page=search&q=bob" class="qtag">bob</a>
    <a href="?page=search&q=admin" class="qtag">admin</a>
    <a href="?page=search&q=dave" class="qtag">dave</a>
  </div>
  <div class="empty"><div class="empty-icon">⌕</div>Search for people on VulnSphere</div>
  <?php endif; ?>

</div>

<?php include __DIR__ . '/layout/footer.php'; ?>
