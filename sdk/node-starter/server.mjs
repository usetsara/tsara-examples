import { createServer } from "node:http";
import { readFile } from "node:fs/promises";
import { randomUUID } from "node:crypto";
import { Tsara, constructWebhookEvent } from "@tsara/node";

const port = Number(process.env.PORT || 8080);
const secretKey = process.env.TSARA_SECRET_KEY;
const publicKey = process.env.TSARA_PUBLIC_KEY;
const webhookSecret = process.env.TSARA_WEBHOOK_SECRET;

if (!secretKey || !publicKey) {
  throw new Error("Set TSARA_SECRET_KEY and TSARA_PUBLIC_KEY before starting the server.");
}

const tsara = new Tsara({ secretKey });
const page = await readFile(new URL("./public/index.html", import.meta.url));

async function rawBody(request) {
  const chunks = [];
  for await (const chunk of request) chunks.push(chunk);
  return Buffer.concat(chunks);
}

function json(response, status, payload) {
  response.writeHead(status, { "Content-Type": "application/json" });
  response.end(JSON.stringify(payload));
}

createServer(async (request, response) => {
  const url = new URL(request.url, `http://${request.headers.host}`);

  try {
    if (request.method === "GET" && url.pathname === "/") {
      response.writeHead(200, { "Content-Type": "text/html; charset=utf-8" });
      response.end(page);
      return;
    }

    if (request.method === "GET" && url.pathname === "/config") {
      json(response, 200, { publicKey });
      return;
    }

    if (request.method === "POST" && url.pathname === "/checkout") {
      const input = JSON.parse((await rawBody(request)).toString("utf8") || "{}");
      const transactionId = `example_${randomUUID()}`;
      const checkout = await tsara.checkout.create(publicKey, {
        trx_id: transactionId,
        amount: Number(input.amount || 1000),
        amount_type: "fiat",
        currency: "NGN",
        email: input.email || "customer@example.com",
        name: input.name || "Example Customer",
        success_url: `http://localhost:${port}/?result=success`,
        cancel_url: `http://localhost:${port}/?result=cancelled`,
      });
      json(response, 200, checkout);
      return;
    }

    if (request.method === "GET" && url.pathname === "/status") {
      const transactionId = url.searchParams.get("trx_id");
      if (!transactionId) return json(response, 400, { message: "trx_id is required" });
      json(response, 200, await tsara.checkout.status(transactionId));
      return;
    }

    if (request.method === "POST" && url.pathname === "/webhook") {
      if (!webhookSecret) return json(response, 503, { message: "Webhook secret is not configured" });
      const body = await rawBody(request);
      const signature = request.headers["x_tsara_signature"] || request.headers["x-tsara-signature"] || "";
      const event = constructWebhookEvent(body, String(signature), webhookSecret);

      // Persist the event ID before returning 200 and ignore duplicate deliveries.
      console.log("Accepted Tsara event", event.event || event.type || "unknown");
      json(response, 200, { received: true });
      return;
    }

    json(response, 404, { message: "Not found" });
  } catch (error) {
    console.error(error);
    json(response, error.status || 500, {
      message: error.message || "Unexpected error",
      requestId: error.requestId || null,
    });
  }
}).listen(port, () => {
  console.log(`Tsara Node starter: http://localhost:${port}`);
});
