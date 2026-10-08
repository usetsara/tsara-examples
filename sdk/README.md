# Official Tsara SDK examples

Use the official SDK that matches your runtime:

- PHP: `composer require tsara/tsara-php`
- Node.js: `npm install @tsara/node`
- Browser Checkout: `npm install @tsara/checkout-js@0.1.2`

## Runnable starters

- [`node-starter/`](node-starter/) - Node.js server, Checkout JS browser flow, status verification, and signed webhook endpoint
- [`php-starter/`](php-starter/) - PHP server, Checkout JS browser flow, status verification, and signed webhook endpoint

Both starters use test credentials from environment variables. They create the Checkout transaction on the server and expose only the public key to the browser.

## Node.js Checkout creation

```ts
import { Tsara } from "@tsara/node";

const tsara = new Tsara({ secretKey: process.env.TSARA_SECRET_KEY! });
const checkout = await tsara.checkout.create(process.env.TSARA_PUBLIC_KEY!, {
  trx_id: "order_001",
  amount: 1000,
  email: "customer@example.com",
  name: "Example Customer",
  success_url: "https://merchant.example/payments/success",
  cancel_url: "https://merchant.example/payments/cancel",
});
```

## PHP Checkout creation

```php
$tsara = new Tsara\Client(secretKey: getenv('TSARA_SECRET_KEY'));
$checkout = $tsara->checkout->create(getenv('TSARA_PUBLIC_KEY'), [
    'trx_id' => 'order_001',
    'amount' => 1000,
    'email' => 'customer@example.com',
    'name' => 'Example Customer',
]);
```

Use only public keys in browser code. Verify payment server-side or through a signed webhook before delivering value.

## Browser Checkout with npm

```js
import { open } from "@tsara/checkout-js";

open({
  publicKey: "pk_test_xxxxx",
  transactionId: "order_001",
  onSuccess: result => console.log("Payment completed", result),
  onCancel: context => console.log("Checkout closed", context),
  onError: error => console.error(error.message),
});
```

## Browser Checkout with a script tag

Use a pinned SDK version so upgrades are deliberate:

```html
<script src="https://unpkg.com/@tsara/checkout-js@0.1.2/dist/index.global.js"></script>
<script>
  TsaraCheckout.open({
    publicKey: "pk_test_xxxxx",
    transactionId: "order_001"
  });
</script>
```
