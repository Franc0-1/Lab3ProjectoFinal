<?php
/**
 * Laravel Application Entry Point for Render
 * This file helps Render detect PHP as the runtime
 */

// Redirect to public/index.php for Laravel
if (file_exists(__DIR__ . '/public/index.php')) {
    require_once __DIR__ . '/public/index.php';
} else {
    echo "Laravel Application";
    echo "\nPlease ensure all dependencies are installed with: composer install";
}
