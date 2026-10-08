<?php

declare(strict_types=1);

$publicKey = getenv('TSARA_PUBLIC_KEY') ?: '';
if ($publicKey === '') {
    http_response_code(500);
    exit('Set TSARA_PUBLIC_KEY before starting the server.');
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Tsara PHP Checkout starter</title>
</head>
<body>
  <main>
    <h1>Tsara Checkout</h1>
    <form id="checkout-form">
      <label>Name <input name="name" value="Example Customer" required></label>
      <label>Email <input name="email" type="email" value="customer@example.com" required></label>
      <label>Amount (NGN) <input name="amount" type="number" min="100" value="1000" required></label>
      <button type="submit">Pay with Tsara</button>
    </form>
    <pre id="result" aria-live="polite"></pre>
  </main>

  <script src="https://unpkg.com/@tsara/checkout-js@0.1.2/dist/index.global.js"></script>
  <script>
    const publicKey = <?= json_encode($publicKey, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
    const form = document.querySelector("#checkout-form");
    const result = document.querySelector("#result");

    form.addEventListener("submit", async event => {
      event.preventDefault();
      result.textContent = "Creating checkout...";
      const payload = Object.fromEntries(new FormData(form));
      const checkout = await fetch("/create-checkout.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(payload),
      }).then(response => response.json());

      if (!checkout.success) {
        result.textContent = checkout.message || "Unable to create checkout";
        return;
      }

      const transactionId = checkout.data?.id || checkout.trx_id;
      if (!transactionId) {
        result.textContent = "Checkout was created without a transaction ID.";
        return;
      }
      TsaraCheckout.open({
        publicKey,
        transactionId,
        onSuccess: async callback => {
          result.textContent = "Verifying payment on the server...";
          const status = await fetch(`/status.php?trx_id=${encodeURIComponent(transactionId)}`).then(response => response.json());
          result.textContent = JSON.stringify({ callback, status }, null, 2);
        },
        onCancel: context => { result.textContent = JSON.stringify(context, null, 2); },
        onError: error => { result.textContent = error.message; },
      });
    });
  </script>
</body>
</html>
