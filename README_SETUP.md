# VulnSphere — Setup & Run Guide

> Social platform for builders and creators.  
> Built with PHP + SQLite — no external dependencies required.

---

## Requirements

| Requirement | Version |
|-------------|---------|
| PHP         | 7.4+    |
| PHP Extensions | `pdo`, `pdo_sqlite`, `gd` (for avatar generation) |
| SQLite      | Bundled with PHP |

Check your PHP version:
```bash
php --version
```

Check required extensions:
```bash
php -m | grep -E "pdo|sqlite|gd"
```

---

## Quick Start (3 steps)

### Step 1 — Initialize the database

```bash
cd path/to/veb
php setup.php
```

This creates:
- `database/vulnsphere.db` — SQLite database
- `public/uploads/avatars/` — Avatar upload directory
- `public/uploads/posts/` — Post image directory
- Default avatar image

### Step 2 — Start the dev server

```bash
php -S localhost:8080
```

### Step 3 — Open in browser

```
http://localhost:8080
```

---

## Default Accounts

| Username    | Password   | Role  |
|-------------|-----------|-------|
| admin       | admin123   | admin |
| alice_dev   | alice2024  | user  |
| b0bbydrops  | admin      | user  |
| dave_xyz    | letmein    | user  |

---

## Project Structure

```
veb/
├── .env                          # App configuration
├── index.php                     # Main router
├── setup.php                     # Database initializer (run once)
│
├── app/
│   ├── config/
│   │   └── database.php          # PDO SQLite connection
│   ├── controllers/
│   │   ├── AuthController.php
│   │   ├── PostController.php
│   │   ├── CommentController.php
│   │   ├── UserController.php
│   │   └── AdminController.php
│   ├── models/
│   │   ├── User.php
│   │   ├── Post.php
│   │   ├── Comment.php
│   │   └── Session.php
│   └── helpers/
│       └── helpers.php
│
├── views/
│   ├── layout/
│   │   ├── header.php
│   │   └── footer.php
│   ├── auth/
│   │   ├── login.php
│   │   └── register.php
│   ├── feed.php
│   ├── profile.php
│   ├── search.php
│   └── admin/
│       └── panel.php
│
├── public/
│   ├── css/
│   │   ├── main.css
│   │   ├── auth.css
│   │   └── profile.css
│   ├── js/
│   │   ├── app.js
│   │   ├── feed.js
│   │   └── profile.js
│   └── uploads/
│       ├── avatars/
│       └── posts/
│
└── database/
    ├── schema.sql
    ├── seed.sql
    └── vulnsphere.db             # Created by setup.php
```

---

## Routes

| URL | Description |
|-----|-------------|
| `/?page=login` | Login page |
| `/?page=register` | Registration page |
| `/?page=feed` | Main feed (requires login) |
| `/?page=profile&u=alice_dev` | User profile |
| `/?page=search&q=bob` | User search |
| `/?page=admin` | Admin panel (admin role required) |
| `/?page=logout` | Logout |

---

## API Endpoints

| Endpoint | Method | Description |
|----------|--------|-------------|
| `/?page=post-create` | POST | Create a post |
| `/?page=post-delete&id=N` | GET | Delete a post |
| `/?page=post-like` | POST | Like a post |
| `/?page=comment-add` | POST | Add a comment (AJAX) |
| `/?page=edit-profile` | POST | Update profile |
| `/?page=admin-query` | POST | Raw SQL (admin) |

---

## Troubleshooting

**DB not found error:**
```bash
# Make sure setup.php ran successfully
php setup.php
```

**Permission errors on uploads:**
```bash
# Windows: ensure the uploads directory is writable
# Linux/Mac:
chmod -R 755 public/uploads/
```

**Blank page / 500 error:**
```bash
# Enable PHP errors temporarily
# Add to top of index.php:
ini_set('display_errors', 1);
error_reporting(E_ALL);
```

**GD extension missing (avatar generation fails):**
```bash
# Ubuntu/Debian
sudo apt install php-gd

# Windows: uncomment extension=gd in php.ini
```

---

## Resetting the Database

```bash
rm database/vulnsphere.db
php setup.php
```

---

## Notes

- This is a local development server — not suitable for production
- SQLite database file is at `database/vulnsphere.db`
- All uploaded files go to `public/uploads/`
- Session name is `vs_session` (set in `.env`)
