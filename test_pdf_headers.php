<?php
// Quick test script to check PDF response headers
$ch = curl_init();

// Step 1: GET login page, grab CSRF token & cookies
curl_setopt_array($ch, [
    CURLOPT_URL => 'http://localhost/uhalisia-portal/public/admin/login',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_COOKIEJAR => __DIR__ . '/cookie_test.txt',
    CURLOPT_COOKIEFILE => __DIR__ . '/cookie_test.txt',
]);
$html = curl_exec($ch);
preg_match('/_token.*?value="([^"]+)"/', $html, $m);
$token = $m[1] ?? '';
echo "CSRF: $token\n";

// Step 2: POST login
curl_setopt_array($ch, [
    CURLOPT_URL => 'http://localhost/uhalisia-portal/public/admin/login',
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => http_build_query([
        '_token' => $token,
        'email' => 'admin@uwt.co.tz',
        'password' => 'password123',
    ]),
    CURLOPT_FOLLOWLOCATION => true,
]);
curl_exec($ch);
echo 'Login HTTP status: ' . curl_getinfo($ch, CURLINFO_HTTP_CODE) . "\n";

// Step 3: GET the PDF with download=1, inspect headers
curl_setopt_array($ch, [
    CURLOPT_URL => 'http://localhost/uhalisia-portal/public/admin/bookings/1/quote-pdf?download=1',
    CURLOPT_POST => false,
    CURLOPT_HEADER => true,
    CURLOPT_NOBODY => false,
    CURLOPT_RETURNTRANSFER => true,
]);
$response = curl_exec($ch);
$headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
$headers = substr($response, 0, $headerSize);
$body = substr($response, $headerSize);

echo "\n--- RESPONSE HEADERS ---\n";
echo $headers;
echo "\nBody size: " . strlen($body) . " bytes\n";

// Check if body starts with %PDF
if (substr($body, 0, 4) === '%PDF') {
    echo "Body IS a valid PDF file\n";
} else {
    echo "Body is NOT a PDF. First 200 chars:\n";
    echo substr($body, 0, 200) . "\n";
}

curl_close($ch);
@unlink(__DIR__ . '/cookie_test.txt');
echo "\nDone.\n";
