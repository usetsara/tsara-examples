<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Tsara\Client;

header('Content-Type: application/json');

try {
    $input = json_decode(file_get_contents('php://input'), true, 512, JSON_THROW_ON_ERROR);
    $secretKey = getenv('TSARA_SECRET_KEY') ?: '';
    $publicKey = getenv('TSARA_PUBLIC_KEY') ?: '';
    if ($secretKey === '' || $publicKey === '') {
        throw new RuntimeException('Set TSARA_SECRET_KEY and TSARA_PUBLIC_KEY before starting the server.');
    }

    $transactionId = 'example_' . bin2hex(random_bytes(12));
    $tsara = new Client(secretKey: $secretKey);
    $checkout = $tsara->checkout->create($publicKey, [
        'trx_id' => $transactionId,
        'amount' => (float) ($input['amount'] ?? 1000),
        'amount_type' => 'fiat',
        'currency' => 'NGN',
        'email' => $input['email'] ?? 'customer@example.com',
        'name' => $input['name'] ?? 'Example Customer',
        'success_url' => 'http://localhost:8081/?result=success',
        'cancel_url' => 'http://localhost:8081/?result=cancelled',
    ]);

    echo json_encode($checkout, JSON_THROW_ON_ERROR);
} catch (Throwable $error) {
    http_response_code(property_exists($error, 'httpStatus') && $error->httpStatus > 0 ? $error->httpStatus : 500);
    echo json_encode(['success' => false, 'message' => $error->getMessage()], JSON_THROW_ON_ERROR);
}
