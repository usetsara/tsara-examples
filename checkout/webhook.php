<?php
// Example Tsara webhook handler.
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
$data = $event['data'] ?? [];

switch ($eventType) {
    case 'payment.success':
        // Example: mark the local order as paid using your own transaction lookup.
        $reference = $data['reference'] ?? null;
        $amount = $data['amount'] ?? null;

        // TODO: persist the successful payment in your database.
        error_log('Tsara payment success for reference: ' . ($reference ?? 'unknown') . ' amount: ' . ($amount ?? 'unknown'));
        break;

    default:
        // Ignore events you do not handle yet, but still acknowledge receipt.
        break;
}

http_response_code(200);
echo 'OK';
