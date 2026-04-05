# vulnsphere
Intentionally vulnerable PHP social media app for educational pentesting purposes

⚠️ Educational purposes only — intentionally vulnerable application.

VulnSphere is a vulnerable social media platform built for cybersecurity training and penetration testing practice.
OWASP Top 10 vulnerabilities are embedded naturally inside the application.

Stack

PHP • SQLite • Vanilla JS • Manual MVC

Setup
git clone https://github.com/nxllnv/vulnsphere.git
cd vulnsphere
cp .env.example .env
php setup.php
php -S localhost:8080

Requirement: PHP 7.4+ (pdo_sqlite enabled)

Included Vulnerabilities

SQLi • XSS • IDOR • Weak Auth • CSRF • Insecure Upload • Info Disclosure

Disclaimer

Use only in lab environments.
Do not deploy publicly.

MIT License
