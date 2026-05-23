# Tsara Payment Integration Examples

This repository provides small, working examples for integrating Tsara checkout and payment APIs.

## Examples

### 1. Checkout Form (Hosted on Your Site)
Use Tsara's inline checkout directly from your site.

Folder:
- `checkout/`

Files:
- `index.html` - example checkout page using `https://checkout.tsara.ng/inline.js`
- `webhook.php` - example webhook handler with signature verification

### 2. Checkout Link (Create via API, Redirect to Tsara)
Create a checkout session with the canonical checkout API and redirect the customer to the hosted checkout URL.

Folder:
- `checkout-link/`

Files:
- `create_link.html` - example `POST /v1/checkout` integration

### 3. Customers
Create, list, and update customers.

Folder:
- `customers/`

Files:
- `create.html` - example `POST /v1/customers` integration
- `list.html` - example `GET /v1/customers` integration
- `update.html` - example `POST /v1/customers/update` integration

### 4. Payment Links
Create, list, update status, and inspect payment-link transactions.

Folder:
- `payment-links/`

Files:
- `create.html` - example `POST /v1/payment-links` integration
- `list.html` - example `GET /v1/payment-links` integration
- `status.html` - example `POST /v1/payment-links/status` integration
- `transactions.html` - example `GET /v1/payment-links/transactions` integration

### 5. Stablecoin Onramp and Offramp
Create canonical OnSwitch-backed stablecoin payment flows.

Folder:
- `stablecoin/`

Files:
- `onramp.html` - example `POST /v1/stablecoin/onramp` integration
- `onramp-status.html` - example `GET /v1/stablecoin/onramp/status?provider_reference=...&refresh=1`
- `offramp.html` - example `POST /v1/stablecoin/offramp` integration
- `offramp-quote.html` - example `POST /v1/stablecoin/offramp/quote` integration
- `offramp-status.html` - example `GET /v1/stablecoin/offramp/status?provider_reference=...&refresh=1`

### 6. Bill Payments
Create direct and crypto-funded bill payment flows.

Folder:
- `bill/`
- `payouts/`

Files:
- `airtime.html` - example `POST /v1/bill/airtime` integration
- `electricity.html` - example `POST /v1/bill/electricity` integration
- `crypto.html` - example `POST /v1/bill/crypto` integration
- `crypto-fetch.html` - example `GET /v1/bill/crypto` integration
- `crypto-reconcile.html` - example `GET /v1/bill/crypto/reconcile` integration
- `crypto-retry.html` - example `POST /v1/bill/crypto/retry` integration

### 7. Payouts
Single and bulk payouts from a business account.

Folder:
- `payouts/`

Files:
- `name-enquiry.html` - example `POST /v1/payouts/name-enquiry` integration
- `single.html` - example `POST /v1/payouts` integration
- `bulk.html` - example `POST /v1/payouts/bulk` integration
- `fetch.html` - example `GET /v1/payouts` and `GET /v1/payouts/bulk` integration

### 8. Navigation
Open `index.html` in the repo root to jump into the examples quickly.

## Current API direction

Canonical routes used in this repo:
- `POST https://api.tsara.ng/v1/checkout`
- `POST https://api.tsara.ng/v1/customers`
- `GET https://api.tsara.ng/v1/customers`
- `POST https://api.tsara.ng/v1/customers/update`
- `POST https://api.tsara.ng/v1/payment-links`
- `GET https://api.tsara.ng/v1/payment-links`
- `POST https://api.tsara.ng/v1/payment-links/status`
- `GET https://api.tsara.ng/v1/payment-links/transactions`
- `POST https://api.tsara.ng/v1/stablecoin/onramp`
- `GET https://api.tsara.ng/v1/stablecoin/onramp/status?provider_reference=...&refresh=1`
- `POST https://api.tsara.ng/v1/stablecoin/offramp`
- `POST https://api.tsara.ng/v1/stablecoin/offramp/quote`
- `GET https://api.tsara.ng/v1/stablecoin/offramp/status?provider_reference=...&refresh=1`
- `POST https://api.tsara.ng/v1/bill/airtime`
- `POST https://api.tsara.ng/v1/bill/electricity`
- `POST https://api.tsara.ng/v1/payouts/name-enquiry`
- `POST https://api.tsara.ng/v1/payouts`
- `GET https://api.tsara.ng/v1/payouts`
- `POST https://api.tsara.ng/v1/payouts/bulk`
- `GET https://api.tsara.ng/v1/payouts/bulk`
- `POST https://api.tsara.ng/v1/bill/crypto`
- `GET https://api.tsara.ng/v1/bill/crypto`
- `GET https://api.tsara.ng/v1/bill/crypto/reconcile`
- `POST https://api.tsara.ng/v1/bill/crypto/retry`

Do not use old create-link shapes such as:
- `GET /v1/checkout/create`

## Curl snippets

### Create customer
```sh
curl -X POST https://api.tsara.ng/v1/customers \
  -H "Authorization: Bearer pk_live_xxxxx" \
  -H "Content-Type: application/json" \
  -d '{
    "email": "user@example.com",
    "name": "John Doe",
    "type": "individual"
  }'
```

### List customers
```sh
curl "https://api.tsara.ng/v1/customers?search=john&type=individual&page=1&limit=10" \
  -H "Authorization: Bearer pk_live_xxxxx"
```

### Update customer
```sh
curl -X POST https://api.tsara.ng/v1/customers/update \
  -H "Authorization: Bearer pk_live_xxxxx" \
  -H "Content-Type: application/json" \
  -d '{
    "customer_id": "id_1234567890",
    "email": "user@example.com",
    "name": "John Doe Updated",
    "type": "individual"
  }'
```

### Create payment link
```sh
curl -X POST https://api.tsara.ng/v1/payment-links \
  -H "Authorization: Bearer pk_live_xxxxx" \
  -H "Content-Type: application/json" \
  -d '{
    "currency": "NGN",
    "title": "January dues",
    "description": "Membership dues",
    "amount": 5000,
    "slug": "jan-dues",
    "type": "fixed",
    "quantity_limit": 1,
    "fields": ["First Name", "Last Name", "Email", "Phone"]
  }'
```

### List payment links
```sh
curl "https://api.tsara.ng/v1/payment-links?status=active&page=1&limit=10" \
  -H "Authorization: Bearer pk_live_xxxxx"
```

### Update payment-link status
```sh
curl -X POST https://api.tsara.ng/v1/payment-links/status \
  -H "Authorization: Bearer pk_live_xxxxx" \
  -H "Content-Type: application/json" \
  -d '{
    "uid": "plink_123",
    "status": "active"
  }'
```

### List payment-link transactions
```sh
curl "https://api.tsara.ng/v1/payment-links/transactions?uid=plink_1234567890&status=success&page=1&limit=10" \
  -H "Authorization: Bearer pk_live_xxxxx"
```

### Checkout link
```sh
curl -X POST https://api.tsara.ng/v1/checkout \
  -H "Authorization: Bearer pk_live_xxxxx" \
  -H "Content-Type: application/json" \
  -d '{
    "trx_id": "ORDER-1234567890",
    "email": "customer@example.com",
    "name": "Test Client",
    "phone": "08012345678",
    "amount": 1000,
    "amount_type": "fiat",
    "currency": "NGN",
    "redirect_url": "https://yourwebsite.com/checkout/return"
  }'
```

### Stablecoin onramp
```sh
curl -X POST https://api.tsara.ng/v1/stablecoin/onramp \
  -H "Authorization: Bearer pk_live_xxxxx" \
  -H "Content-Type: application/json" \
  -d '{
    "reference": "onramp_001",
    "fiat_currency": "NGN",
    "amount": 2000,
    "asset": "USDC",
    "chain": "SOLANA"
  }'
```

### Stablecoin offramp
```sh
curl -X POST https://api.tsara.ng/v1/stablecoin/offramp \
  -H "Authorization: Bearer pk_live_xxxxx" \
  -H "Content-Type: application/json" \
  -d '{
    "reference": "offramp_001",
    "fiat_currency": "NGN",
    "amount": 2000,
    "asset": "USDC",
    "chain": "SOLANA",
    "bank_code": "090286",
    "account_number": "0110000000"
  }'
```

### Stablecoin offramp quote
```sh
curl -X POST https://api.tsara.ng/v1/stablecoin/offramp/quote \
  -H "Authorization: Bearer pk_live_xxxxx" \
  -H "Content-Type: application/json" \
  -d '{
    "reference": "offramp-quote-001",
    "fiat_currency": "NGN",
    "amount": 2000,
    "asset": "USDC",
    "chain": "SOLANA",
    "bank_code": "090286",
    "account_number": "0110000000"
  }'
```

### Airtime bill
```sh
curl -X POST https://api.tsara.ng/v1/bill/airtime \
  -H "Authorization: Bearer pk_live_xxxxx" \
  -H "Content-Type: application/json" \
  -d '{
    "provider": "MTN",
    "phone_number": "08012345678",
    "amount": 1000,
    "idempotency_key": "bill-airtime-order-001"
  }'
```

### Electricity bill
```sh
curl -X POST https://api.tsara.ng/v1/bill/electricity \
  -H "Authorization: Bearer pk_live_xxxxx" \
  -H "Content-Type: application/json" \
  -d '{
    "provider": "IKEDC",
    "number": "1234567890",
    "vend_type": "PREPAID",
    "amount": 2000,
    "idempotency_key": "bill-electricity-order-001"
  }'
```

### Payout name enquiry
```sh
curl -X POST https://api.tsara.ng/v1/payouts/name-enquiry \
  -H "Authorization: Bearer pk_live_xxxxx" \
  -H "Content-Type: application/json" \
  -d '{
    "bank_code": "090286",
    "account_number": "0110000000"
  }'
```

### Single payout
```sh
curl -X POST https://api.tsara.ng/v1/payouts \
  -H "Authorization: Bearer pk_live_xxxxx" \
  -H "Content-Type: application/json" \
  -d '{
    "reference": "payout_001",
    "source_account_number": "0111632855",
    "narration": "Vendor payout",
    "idempotency_key": "payout-idem-001",
    "amount": 5000,
    "beneficiary": {
      "bank_code": "090286",
      "account_number": "0110000000"
    }
  }'
```

### Bulk payout
```sh
curl -X POST https://api.tsara.ng/v1/payouts/bulk \
  -H "Authorization: Bearer pk_live_xxxxx" \
  -H "Content-Type: application/json" \
  -d '{
    "reference": "bulk_payout_001",
    "source_account_number": "0111632855",
    "narration": "Weekly settlements",
    "idempotency_key": "bulk-idem-001",
    "items": [
      {
        "reference": "item_001",
        "bank_code": "090286",
        "account_number": "0110000000",
        "amount": 5000
      },
      {
        "reference": "item_002",
        "bank_code": "100004",
        "account_number": "8093930950",
        "amount": 7000
      }
    ]
  }'
```

### Fetch payout
```sh
curl "https://api.tsara.ng/v1/payouts?reference=payout_001" \
  -H "Authorization: Bearer pk_live_xxxxx"
```

### Crypto-funded bill
```sh
curl -X POST https://api.tsara.ng/v1/bill/crypto \
  -H "Authorization: Bearer pk_live_xxxxx" \
  -H "Content-Type: application/json" \
  -d '{
    "service": "AIRTIME",
    "provider": "MTN",
    "amount": 2000,
    "phone_number": "08012345678",
    "asset": "solana:usdc",
    "idempotency_key": "bill-airtime-001"
  }'
```

### Fetch crypto-funded bill
```sh
curl "https://api.tsara.ng/v1/bill/crypto?trx_id=ts-1234567890" \
  -H "Authorization: Bearer pk_live_xxxxx"
```

### Reconcile crypto-funded bill
```sh
curl "https://api.tsara.ng/v1/bill/crypto/reconcile?provider_reference=provider-guid-here" \
  -H "Authorization: Bearer pk_live_xxxxx"
```

### Retry crypto-funded bill
```sh
curl -X POST https://api.tsara.ng/v1/bill/crypto/retry \
  -H "Authorization: Bearer pk_live_xxxxx" \
  -H "Content-Type: application/json" \
  -d '{
    "trx_id": "ts-1234567890"
  }'
```

## Getting Started

1. Clone the repository:
   ```sh
   git clone https://github.com/usetsara/payment-examples.git
   cd payment-examples
   ```

2. Replace placeholder values such as:
   - `pk_live_xxxxx`
   - redirect URLs
   - `sk_webhook_xxxxx`
   - provider references

3. Host the example you want to test:
   - `index.html`
   - `checkout/`
   - `checkout-link/`
   - `customers/`
   - `payment-links/`
   - `stablecoin/`
   - `bill/`
- `payouts/`

4. Implement your actual order update logic inside `checkout/webhook.php`.

## Notes

- The browser examples already dump the full JSON response in a `<pre>` block for quick debugging.
- Stablecoin status refresh examples use `provider_reference` plus `refresh=1`.
- Bill crypto examples use fetch, reconcile, and retry as separate support operations.
- Payout examples are asynchronous by design; create returns a local pending state, not final settlement.
- The create examples call out which identifiers to save for the follow-up status/support pages.
- Replace the placeholder public key before testing any route.

## Webhook notes

- Tsara sends the raw request body and `X-Tsara-Signature` header.
- Verify the signature before you trust the payload.
- Return `200 OK` quickly after processing the event.
- Use your own transaction reference lookup before marking an order as paid.

## License

MIT License
