<?php

/**
 * Uhalisia wa Tanzania Portal - Root Redirector
 *
 * Forwards requests from http://localhost/uhalisia-portal/ to /uhalisia-portal/public/
 */

header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');
header('Location: /uhalisia-portal/public/', true, 302);
exit;
