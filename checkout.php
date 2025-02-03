<?php
require 'config.php'; // Файл с PayPal API

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $qty = $_POST['qty'];
    $sum = $_POST['sum'];
    $currency = $_POST['currency'];

    $token = bin2hex(random_bytes(16));

    $stmt = $pdo->prepare("INSERT INTO orders (name, email, qty, sum, currency, status, token) VALUES (?, ?, ?, ?, ?, 'pending', ?)");
    $stmt->execute([$name, $email, $qty, $sum, $currency, $token]);

    $order_id = $pdo->lastInsertId();

    // PayPal API
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://api-m.sandbox.paypal.com/v1/oauth2/token");
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Accept: application/json", "Accept-Language: en_US"]);
    curl_setopt($ch, CURLOPT_USERPWD, PAYPAL_CLIENT_ID . ":" . PAYPAL_SECRET);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = json_decode(curl_exec($ch), true);
    $access_token = $response['access_token'];

    $order_data = [
        "intent" => "CAPTURE",
        "purchase_units" => [[
            "amount" => ["currency_code" => $currency, "value" => $sum]
        ]],
        "application_context" => [
            "return_url" => "http://yourdomain.com/success.php?token=$token",
            "cancel_url" => "http://yourdomain.com/cancel.php?token=$token"
        ]
    ];

    curl_setopt($ch, CURLOPT_URL, "https://api-m.sandbox.paypal.com/v2/checkout/orders");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json",
        "Authorization: Bearer $access_token"
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($order_data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $order_response = json_decode(curl_exec($ch), true);
    $order_id_paypal = $order_response['id'];
    $approval_url = $order_response['links'][1]['href'];

    curl_close($ch);

    header("Location: $approval_url");
    exit;
}
?>
