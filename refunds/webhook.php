<?php
// Example Tsara refund webhook handler.
// Replace the placeholder secret with your real webhook secret.

const TSARA_WEBHOOK_SECRET = 'sk_webhook_xxxxx';

if (strtoupper($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    http_response_code(405);
    echo 'Method Not Allowed';
    exit;
}

$signature = $_SERVER['HTTP_X_TSARA_SIGNATURE'] ?? null;
if (!$signature) {
    http_response_code(400);
    echo 'Missing signature';
    exit;
}

$payload = file_get_contents('php://input');
if ($payload === false || $payload === '') {
    http_response_code(400);
    echo 'Empty payload';
    exit;
}

$expectedSignature = hash_hmac('sha512', $payload, TSARA_WEBHOOK_SECRET);
if (!hash_equals($expectedSignature, $signature)) {
    http_response_code(401);
    echo 'Invalid signature';
    exit;
}

$event = json_decode($payload, true);
if (!is_array($event)) {
    http_response_code(400);
    echo 'Invalid JSON';
    exit;
}

$eventType = $event['event'] ?? null;
$data = is_array($event['data'] ?? null) ? $event['data'] : [];
$transaction = is_array($data['transaction'] ?? null) ? $data['transaction'] : [];

switch ($eventType) {
    case 'refund.pending':
        error_log('Tsara refund pending: ' . ($data['reference'] ?? 'unknown'));
        break;

    case 'refund.processing':
        error_log('Tsara refund processing: ' . ($data['reference'] ?? 'unknown'));
        break;

    case 'refund.successful':
        error_log('Tsara refund successful: ' . ($data['reference'] ?? 'unknown') . ' for transaction ' . ($transaction['reference'] ?? 'unknown'));
        break;

    case 'refund.failed':
        error_log('Tsara refund failed: ' . ($data['reference'] ?? 'unknown'));
        break;

    default:
        break;
}

http_response_code(200);
echo 'OK';
