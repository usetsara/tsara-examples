<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Tsara\Client;

header('Content-Type: application/json');

try {
    $transactionId = trim((string) ($_GET['trx_id'] ?? ''));
    if ($transactionId === '') {
        http_response_code(400);
        throw new InvalidArgumentException('trx_id is required');
    }

    $tsara = new Client(secretKey: getenv('TSARA_SECRET_KEY') ?: '');
    echo json_encode($tsara->checkout->status($transactionId), JSON_THROW_ON_ERROR);
} catch (Throwable $error) {
    $status = property_exists($error, 'httpStatus') && $error->httpStatus > 0
        ? $error->httpStatus
        : (http_response_code() === 400 ? 400 : 500);
    http_response_code($status);
    echo json_encode(['success' => false, 'message' => $error->getMessage()], JSON_THROW_ON_ERROR);
}
