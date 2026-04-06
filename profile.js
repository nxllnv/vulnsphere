/**
 * VulnSphere profile.js
 * Avatar preview on file select
 */
document.addEventListener('DOMContentLoaded', () => {
    const avatarInput = document.getElementById('avatarInput');
    const profileAv   = document.getElementById('profileAvatar');

    if (avatarInput && profileAv) {
        avatarInput.addEventListener('change', e => {
            const file = e.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = ev => { profileAv.src = ev.target.result; };
            reader.readAsDataURL(file);
        });
    }
});
