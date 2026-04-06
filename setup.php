<?php
// VulnSphere - Database initializer (Linux compatible)
// Run: php setup.php

echo "=== VulnSphere Setup ===\n\n";

$rootPath = __DIR__;
$dbDir    = $rootPath . '/database';
$dbPath   = $dbDir . '/vulnsphere.db';

// Create upload directories
$dirs = [
    $rootPath . '/public/uploads',
    $rootPath . '/public/uploads/avatars',
    $rootPath . '/public/uploads/posts',
];

foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
        echo "[+] Created: $dir\n";
    }
    // Linux: ensure writable by web server
    chmod($dir, 0755);
}

// Generate default avatar PNG
$defaultAvatar = $rootPath . '/public/uploads/avatars/default.png';
if (!file_exists($defaultAvatar) && extension_loaded('gd')) {
    $img = imagecreatetruecolor(100, 100);
    $bg  = imagecolorallocate($img, 20, 20, 20);
    $fg  = imagecolorallocate($img, 100, 100, 100);
    imagefill($img, 0, 0, $bg);
    imagefilledellipse($img, 50, 36, 40, 40, $fg);
    imagefilledrectangle($img, 18, 60, 82, 100, $fg);
    imagepng($img, $defaultAvatar);
    imagedestroy($img);
    echo "[+] Default avatar created\n";
} elseif (!file_exists($defaultAvatar)) {
    // Fallback: 1x1 transparent PNG if GD not available
    $png = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==');
    file_put_contents($defaultAvatar, $png);
    echo "[+] Default avatar (placeholder) created\n";
}

// Init SQLite DB
echo "\n[*] Initializing database: $dbPath\n";

try {
    $pdo = new PDO('sqlite:' . $dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec('PRAGMA journal_mode = WAL;');

    $schema = file_get_contents($dbDir . '/schema.sql');
    $pdo->exec($schema);
    echo "[+] Schema applied\n";

    $seed = file_get_contents($dbDir . '/seed.sql');
    $pdo->exec($seed);
    echo "[+] Seed data inserted\n";

    // Make DB writable by web server on Linux
    chmod($dbPath, 0664);

    $users = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
    $posts = $pdo->query("SELECT COUNT(*) FROM posts")->fetchColumn();

    echo "\n[+] Users: $users\n";
    echo "[+] Posts: $posts\n";

} catch (PDOException $e) {
    echo "[!] Error: " . $e->getMessage() . "\n";
    exit(1);
}

echo "\n=== Setup Complete ===\n";
echo "\nAccounts:\n";
echo "  admin      / admin123\n";
echo "  alice_dev  / alice2024\n";
echo "  b0bbydrops / admin\n";
echo "  dave_xyz   / letmein\n";
echo "\nStart server:\n";
echo "  php -S localhost:8080\n\n";
