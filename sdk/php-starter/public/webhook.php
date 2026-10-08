<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Tsara\Webhook;

header('Content-Type: application/json');

try {
    $rawBody = file_get_contents('php://input');
    $signature = $_SERVER['HTTP_X_TSARA_SIGNATURE'] ?? '';
    $secret = getenv('TSARA_WEBHOOK_SECRET') ?: '';
    $event = Webhook::constructEvent($rawBody, $signature, $secret);

    // Persist the event ID before returning 200 and ignore duplicate deliveries.
    error_log('Accepted Tsara event: ' . ($event['event'] ?? $event['type'] ?? 'unknown'));
    echo json_encode(['received' => true], JSON_THROW_ON_ERROR);
} catch (Throwable $error) {
    http_response_code(400);
    echo json_encode(['received' => false, 'message' => $error->getMessage()], JSON_THROW_ON_ERROR);
}
