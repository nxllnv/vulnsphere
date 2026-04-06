/**
 * VulnSphere feed.js
 * Highlight @mentions in post text via innerHTML
 */
document.addEventListener('DOMContentLoaded', () => {
    highlightMentions();
});

function highlightMentions() {
    document.querySelectorAll('.post-text').forEach(el => {
        el.innerHTML = el.innerHTML.replace(
            /@([a-zA-Z0-9_]+)/g,
            '<a href="/index.php?page=profile&u=$1" class="mention">@$1</a>'
        );
    });
}
