# Tsara Node.js starter

This starter creates Checkout transactions on a Node.js server, opens hosted Checkout in the browser, verifies status server-side, and verifies signed webhooks.

## Run it

```bash
npm install
```

Set the variables from `.env.example` in your shell, then run:

```bash
npm start
```

Open `http://localhost:8080`.

Configure your Tsara test webhook URL as `https://your-public-test-url.example/webhook`. A tunnel can expose the local server while testing. The sample logs accepted events; replace that section with durable, idempotent event storage before fulfillment.

The browser callback is followed by a server-side status request. Your production application should fulfill an order only after a signed webhook or server status confirms success.
