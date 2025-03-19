<?php
// only a post with tsara signature header gets our attention
if ((strtoupper($_SERVER['REQUEST_METHOD']) != 'POST') || !array_key_exists('HTTP_X_TSARA_SIGNATURE', $_SERVER)) {
    http_response_code(400);
    exit();
}

// Retrieve the request's body
$input = @file_get_contents("php://input");
define('TSARA_SECRET_KEY', 'secret_key');

// validate event do all at once to avoid timing attack
if ($_SERVER['HTTP_X_TSARA_SIGNATURE'] !== hash_hmac('sha512', $input, TSARA_SECRET_KEY)) {
    http_response_code(401);
    exit();
}



// parse event (which is json string) as object
// Do something - that will not take long - with $event
$event = json_decode($input);

if ($event->event == 'payment.success') {
    $data = $event->data;
    // Take Action
}
exit();
