# Tsara Payment Integration Examples

This repository provides examples for integrating Tsara's payment system into your application. These examples showcase how to create and handle payments using Tsara's APIs.

## Examples

### 1. Checkout Form (Hosted on Your Site)
This example demonstrates how to embed Tsara's checkout form directly into your website.

📂 `checkout/`
- `index.html` - Example checkout page with an embedded payment form.
- `webhook.php` - Webhook handler to process payment notifications.

### 2. Checkout Link (Redirect to Tsara)
This example demonstrates how to generate a Tsara-hosted checkout link using the API and redirect users to complete payments.

(Example files for this method will be added soon.)

## Getting Started

1. Clone the repository:
   ```sh
   git clone https://github.com/usetsara/payment-examples.git
   cd payment-examples
   ```

2. Host the `checkout/` folder on your server to test the embedded checkout form.

3. Implement the webhook handler (`checkout/webhook.php`) to receive payment updates.

## Contributing

Feel free to submit issues or pull requests to improve the examples.

## License

MIT License
