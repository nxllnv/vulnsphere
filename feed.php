<?php
$pageTitle = 'Feed';
$bodyClass = 'feed-page';
$extraCss  = 'profile.css';
$extraJs   = 'feed.js';
include __DIR__ . '/layout/header.php';
$error = $_GET['error'] ?? '';
?>

<div class="page">

  <?php if ($error): ?>
  <div class="alert alert-error"><span class="alert-icon">!</span><span><?= htmlspecialchars($error) ?></span></div>
  <?php endif; ?>

  <!-- Composer -->
  <div class="composer" style="margin-bottom:16px;">
    <form method="POST" action="/index.php?page=post-create" enctype="multipart/form-data" id="composerForm">
      <div class="composer-row">
        <img src="<?= getAvatarUrl($currentUser['avatar']??'default.png') ?>" class="avatar-sm" alt="av">
        <textarea
          class="composer-textarea"
          name="content"
          id="composerInput"
          placeholder="What's on your mind?"
          maxlength="500"
          rows="2"
        ></textarea>
      </div>
      <div class="composer-bar">
        <div class="composer-tools">
          <label class="tool-btn" for="postImage" title="Attach image">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="1"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
          </label>
          <input type="file" id="postImage" name="post_image" accept="image/*" style="display:none" onchange="previewImage(this)">
          <span class="char-count" id="charCount">0/500</span>
        </div>
        <button type="submit" class="btn btn-primary btn-sm" id="postBtn">Post</button>
      </div>
      <div id="imgPreview" class="image-preview" style="display:none">
        <img id="previewImg" src="" alt="preview">
        <button type="button" class="remove-img" onclick="removePreview()">✕</button>
      </div>
    </form>
  </div>

  <!-- Feed -->
  <div class="section-head">
    <span class="section-title">Recent posts</span>
    <span style="font-family:var(--mono);font-size:0.70rem;color:var(--gray)"><?= $total ?> total</span>
  </div>

  <?php if (empty($posts)): ?>
  <div class="empty"><div class="empty-icon">◎</div>No posts yet. Be first.</div>
  <?php else: ?>

  <?php foreach ($posts as $post): ?>
  <article class="post-card" id="post-<?= $post['id'] ?>">
    <div class="post-top">
      <a href="/index.php?page=profile&u=<?= htmlspecialchars($post['username']) ?>" class="post-user">
        <img src="<?= getAvatarUrl($post['avatar']) ?>" class="avatar-xs" alt="av">
        <div class="post-meta">
          <span class="post-handle">@<?= htmlspecialchars($post['username']) ?></span>
          <span class="post-time"><?= timeAgo($post['created_at']) ?></span>
        </div>
      </a>
      <?php if ($currentUser['role']==='admin' || $currentUser['user_id']==$post['user_id']): ?>
      <div class="post-menu">
        <button class="kebab" onclick="toggleDrop(<?= $post['id'] ?>)">⋯</button>
        <div class="drop" id="drop-<?= $post['id'] ?>">
          <a href="/index.php?page=post-delete&id=<?= $post['id'] ?>" class="drop-item danger" onclick="return confirm('Delete?')">Delete post</a>
        </div>
      </div>
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
      <button class="act-btn" id="likeBtn-<?= $post['id'] ?>" data-post="<?= $post['id'] ?>" onclick="likePost(<?= $post['id'] ?>)">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
        <span id="likeCount-<?= $post['id'] ?>"><?= formatCount($post['likes']) ?></span>
      </button>
      <button class="act-btn" onclick="toggleComments(<?= $post['id'] ?>)">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>
        <span id="cmtCount-<?= $post['id'] ?>"><?= count($post['comments']) ?></span>
      </button>
    </div>

    <!-- Comments -->
    <div class="comments-wrap" id="comments-<?= $post['id'] ?>" style="display:none">
      <div class="comments-list" id="cmtList-<?= $post['id'] ?>">
        <?php foreach ($post['comments'] as $c): ?>
        <div class="comment">
          <img src="<?= getAvatarUrl($c['avatar']) ?>" class="avatar-xs" alt="av">
          <div class="comment-body">
            <div class="comment-user">@<?= htmlspecialchars($c['username']) ?></div>
            <!-- Render comment text -->
            <div class="comment-text"><?= $c['content'] ?></div>
            <div class="comment-ts"><?= timeAgo($c['created_at']) ?></div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
      <div class="comment-form-wrap">
        <img src="<?= getAvatarUrl($currentUser['avatar']??'default.png') ?>" class="avatar-xs" alt="av">
        <input type="text" class="comment-input" id="cmtInput-<?= $post['id'] ?>" placeholder="Add a comment..." autocomplete="off">
        <button class="comment-send" onclick="postComment(<?= $post['id'] ?>)">→</button>
      </div>
    </div>
  </article>
  <?php endforeach; ?>

  <?php endif; ?>

  <!-- Pagination -->
  <?php if ($total > 10): ?>
  <div class="pager">
    <?php if ($page > 1): ?>
    <a href="?page=feed&p=<?= $page-1 ?>" class="pager-btn">← prev</a>
    <?php endif; ?>
    <span class="pager-info"><?= $page ?> / <?= ceil($total/10) ?></span>
    <?php if ($page*10 < $total): ?>
    <a href="?page=feed&p=<?= $page+1 ?>" class="pager-btn">next →</a>
    <?php endif; ?>
  </div>
  <?php endif; ?>

</div>

<?php include __DIR__ . '/layout/footer.php'; ?>
