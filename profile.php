<?php
$pageTitle = isset($user) ? '@'.$user['username'] : 'Profile';
$bodyClass = 'profile-page';
$extraCss  = 'profile.css';
$extraJs   = 'profile.js';
include __DIR__ . '/layout/header.php';
?>

<div class="page">

  <?php if (isset($error) && !isset($user)): ?>
  <div class="alert alert-error"><span class="alert-icon">!</span><span>User not found: <?= htmlspecialchars($error) ?></span></div>
  <?php elseif (isset($user)): ?>

  <!-- Profile header -->
  <div class="profile-header">
    <div class="profile-cover"></div>
    <div class="profile-row">
      <img src="<?= getAvatarUrl($user['avatar']) ?>" class="profile-av" id="profileAvatar" alt="avatar">
      <div class="profile-info">
        <div class="profile-handle">@<?= htmlspecialchars($user['username']) ?></div>
        <div class="profile-detail"><?= htmlspecialchars($user['email']) ?> · joined <?= date('M Y', strtotime($user['created_at'])) ?></div>
        <div class="profile-detail" style="margin-top:2px;"><?= count($posts) ?> posts</div>
      </div>
      <?php if ($currentUser['user_id'] == $user['id']): ?>
      <button class="btn btn-outline btn-sm" id="editBtn" onclick="toggleEdit()">Edit</button>
      <?php endif; ?>
    </div>
    <!-- Bio rendered raw for rich text support -->
    <div class="profile-bio">
      <?php if (!empty($user['bio'])): ?>
        <?= $user['bio'] ?>
      <?php else: ?>
        <span class="bio-empty">No bio yet.</span>
      <?php endif; ?>
    </div>
  </div>

  <!-- Edit form -->
  <?php if ($currentUser['user_id'] == $user['id']): ?>
  <div class="edit-form" id="editForm" style="display:none">
    <form method="POST" action="/index.php?page=edit-profile" enctype="multipart/form-data" class="form-stack">
      <div class="field">
        <label>Bio</label>
        <textarea name="bio" rows="4" maxlength="300"><?= htmlspecialchars($user['bio']) ?></textarea>
      </div>
      <div class="field">
        <label>Avatar</label>
        <input type="file" name="avatar" id="avatarInput" accept="image/*">
      </div>
      <div style="display:flex;gap:8px;justify-content:flex-end">
        <button type="button" class="btn btn-ghost btn-sm" onclick="toggleEdit()">Cancel</button>
        <button type="submit" class="btn btn-primary btn-sm">Save</button>
      </div>
    </form>
  </div>
  <?php endif; ?>

  <!-- Posts -->
  <div class="section-head" style="margin-top:20px;">
    <span class="section-title">Posts</span>
    <span style="font-family:var(--mono);font-size:0.70rem;color:var(--gray)"><?= count($posts) ?></span>
  </div>

  <?php if (empty($posts)): ?>
  <div class="empty"><div class="empty-icon">◎</div>No posts yet.</div>
  <?php else: ?>

  <?php foreach ($posts as $post): ?>
  <article class="post-card">
    <div class="post-top">
      <div class="post-user">
        <img src="<?= getAvatarUrl($user['avatar']) ?>" class="avatar-xs" alt="av">
        <div class="post-meta">
          <span class="post-handle">@<?= htmlspecialchars($user['username']) ?></span>
          <span class="post-time"><?= timeAgo($post['created_at']) ?></span>
        </div>
      </div>
      <?php if ($currentUser['user_id']==$user['id'] || $currentUser['role']==='admin'): ?>
      <a href="/index.php?page=post-delete&id=<?= $post['id'] ?>" class="btn btn-danger btn-xs" onclick="return confirm('Delete?')">Delete</a>
      <?php endif; ?>
    </div>
    <div class="post-body">
      <p class="post-text"><?= htmlspecialchars($post['content']) ?></p>
      <?php if ($post['image']): ?>
      <div class="post-img-wrap">
        <img src="/public/uploads/posts/<?= htmlspecialchars($post['image']) ?>" class="post-img" alt="img" loading="lazy">
      </div>
      <?php endif; ?>
    </div>
    <div class="post-actions">
      <span class="act-btn">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
        <?= $post['likes'] ?>
      </span>
    </div>
  </article>
  <?php endforeach; ?>

  <?php endif; ?>
  <?php endif; ?>

</div>

<?php include __DIR__ . '/layout/footer.php'; ?>
