/**
 * VulnSphere app.js — core interactions
 */

const DEBUG = true;
const log = (...a) => DEBUG && console.log('[VS]', ...a);

log('init', navigator.userAgent);

// ── On ready ──
document.addEventListener('DOMContentLoaded', () => {
    setupComposer();
    setupDropCloseOnClick();
    setupToasts();
    log('ready');
});

// ── Composer char counter ──
function setupComposer() {
    const ta = document.getElementById('composerInput');
    const ct = document.getElementById('charCount');
    if (!ta || !ct) return;
    ta.addEventListener('input', () => {
        const n = ta.value.length;
        ct.textContent = n + '/500';
        ct.style.color = n > 450 ? '#ff4444' : n > 400 ? '#ffd700' : '';
        ta.style.height = 'auto';
        ta.style.height = Math.min(ta.scrollHeight, 220) + 'px';
    });
}

// ── Dropdown menus ──
function toggleDrop(postId) {
    const drop = document.getElementById('drop-' + postId);
    if (!drop) return;
    const open = drop.classList.contains('open');
    document.querySelectorAll('.drop.open').forEach(d => d.classList.remove('open'));
    if (!open) drop.classList.add('open');
}

function setupDropCloseOnClick() {
    document.addEventListener('click', e => {
        if (!e.target.closest('.post-menu')) {
            document.querySelectorAll('.drop.open').forEach(d => d.classList.remove('open'));
        }
    });
}

// ── Like ──
async function likePost(postId) {
    const btn = document.getElementById('likeBtn-' + postId);
    const cnt = document.getElementById('likeCount-' + postId);
    if (!btn || btn.dataset.busy) return;
    btn.dataset.busy = '1';

    const prev = parseInt(cnt.textContent) || 0;
    cnt.textContent = prev + 1;
    btn.classList.add('liked', 'pop');

    try {
        const res = await fetch('/index.php?page=post-like', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'post_id=' + postId
        });
        const data = await res.json();
        if (data.ok) {
            cnt.textContent = data.likes;
            log('liked post', postId);
        }
    } catch (e) {
        cnt.textContent = prev;
        btn.classList.remove('liked');
        showToast('Error liking post', 'error');
    } finally {
        delete btn.dataset.busy;
        setTimeout(() => btn.classList.remove('pop'), 300);
    }
}

// ── Toggle comments ──
function toggleComments(postId) {
    const el = document.getElementById('comments-' + postId);
    if (!el) return;
    const visible = el.style.display !== 'none';
    el.style.display = visible ? 'none' : 'block';
    if (!visible) {
        const inp = document.getElementById('cmtInput-' + postId);
        if (inp) setTimeout(() => inp.focus(), 40);
    }
}

// ── Post comment ──
async function postComment(postId) {
    const inp = document.getElementById('cmtInput-' + postId);
    const content = inp ? inp.value.trim() : '';
    if (!content) return;

    try {
        const res = await fetch('/index.php?page=comment-add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: 'post_id=' + postId + '&content=' + encodeURIComponent(content)
        });
        const data = await res.json();
        if (data.ok) {
            inp.value = '';
            appendComment(postId, data);
            log('comment added', data.id);
        }
    } catch (e) {
        showToast('Could not post comment', 'error');
    }
}

function appendComment(postId, data) {
    const list = document.getElementById('cmtList-' + postId);
    if (!list) return;

    const div = document.createElement('div');
    div.className = 'comment';
    // Content inserted into DOM (preserves formatting)
    div.innerHTML = `
        <img src="/public/uploads/avatars/${data.avatar}" class="avatar-xs" alt="av" onerror="this.src='/public/uploads/avatars/default.png'">
        <div class="comment-body">
            <div class="comment-user">@${data.username}</div>
            <div class="comment-text">${data.content}</div>
            <div class="comment-ts">${data.created}</div>
        </div>
    `;
    list.appendChild(div);
    div.scrollIntoView({ behavior: 'smooth', block: 'nearest' });

    const cnt = document.getElementById('cmtCount-' + postId);
    if (cnt) cnt.textContent = parseInt(cnt.textContent || 0) + 1;
}

// ── Allow Enter key in comment input ──
document.addEventListener('keydown', e => {
    if (e.key === 'Enter' && e.target.classList.contains('comment-input')) {
        e.preventDefault();
        const postId = e.target.id.replace('cmtInput-', '');
        postComment(postId);
    }
});

// ── Image preview ──
function previewImage(input) {
    const wrap = document.getElementById('imgPreview');
    const img  = document.getElementById('previewImg');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = ev => { img.src = ev.target.result; wrap.style.display = 'block'; };
        reader.readAsDataURL(input.files[0]);
    }
}

function removePreview() {
    document.getElementById('imgPreview').style.display = 'none';
    document.getElementById('previewImg').src = '';
    const fi = document.getElementById('postImage');
    if (fi) fi.value = '';
}

// ── Toasts ──
function setupToasts() {
    if (!document.getElementById('toastWrap')) {
        const w = document.createElement('div');
        w.id = 'toastWrap'; w.className = 'toast-wrap';
        document.body.appendChild(w);
    }
}

function showToast(msg, type = 'success') {
    const wrap = document.getElementById('toastWrap');
    if (!wrap) return;
    const t = document.createElement('div');
    t.className = 'toast ' + type;
    t.textContent = msg;
    wrap.appendChild(t);
    setTimeout(() => { t.style.opacity = '0'; t.style.transition = '0.2s'; setTimeout(() => t.remove(), 200); }, 2800);
}

// ── Profile edit toggle ──
function toggleEdit() {
    const form = document.getElementById('editForm');
    const btn  = document.getElementById('editBtn');
    if (!form) return;
    const show = form.style.display === 'none';
    form.style.display = show ? 'block' : 'none';
    if (btn) btn.textContent = show ? 'Cancel' : 'Edit';
}

// Keyboard: N to focus composer
document.addEventListener('keydown', e => {
    if (e.key === 'n' && !e.target.matches('input,textarea')) {
        const c = document.getElementById('composerInput');
        if (c) { e.preventDefault(); c.focus(); }
    }
});

log('app.js loaded');
