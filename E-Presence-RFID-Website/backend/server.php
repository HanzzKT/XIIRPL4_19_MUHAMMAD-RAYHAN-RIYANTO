<?php

/**
 * Laravel - A PHP Framework For Web Artisans
 *
 * This file is used to emulate Apache's "mod_rewrite" functionality from the
 * built-in PHP web server.
 */

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

if ($uri !== '/' && file_exists(__DIR__.'/../frontend/public'.$uri)) {
    return false;
}

require_once dirname(__DIR__, 1) . '/frontend/public/index.php';