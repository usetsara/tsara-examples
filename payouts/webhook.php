<?php
// Example Tsara payout webhook handler.
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
$item = is_array($data['item'] ?? null) ? $data['item'] : null;

switch ($eventType) {
    case 'payout.item.processing':
        error_log('Tsara payout item processing: ' . ($item['reference'] ?? 'unknown'));
        break;

    case 'payout.item.successful':
        error_log('Tsara payout item successful: ' . ($item['reference'] ?? 'unknown'));
        break;

    case 'payout.item.failed':
        error_log('Tsara payout item failed: ' . ($item['reference'] ?? 'unknown') . ' error: ' . ($item['last_error'] ?? 'unknown'));
        break;

    case 'payout.batch.completed':
        error_log('Tsara payout batch completed: ' . ($data['reference'] ?? 'unknown') . ' status: ' . ($data['status'] ?? 'unknown'));
        break;

    default:
        break;
}

http_response_code(200);
echo 'OK';
