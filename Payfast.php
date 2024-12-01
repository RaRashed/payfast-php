<?php

require 'vendor/autoload.php';

use RaRashed\PayFastSdk\PayFast;

// Initialize the Payfast class with dynamic parameters
$payfast = new PayFast(
    '102', // Merchant ID
    'Rashed', // Merchant Name
    'zWHjBp2AlttNu1sK', // Secured Key
    'https://ipguat.apps.net.pk/Ecommerce/api/Transaction/GetAccessToken', // Token API URL
    'https://ipguat.apps.net.pk/Ecommerce/api/Transaction/PostTransaction', // Redirect URL
    "https://webhook.site/2d8d8613-7107-4fa1-8e0b-1aa68a8e19c3", // Success URL
    "https://webhook.site/2d8d8613-7107-4fa1-8e0b-1aa68a8e19c3", // Failure URL
    "https://webhook.site/2d8d8613-7107-4fa1-8e0b-1aa68a8e19c3"  // Checkout URL
);

// Prepare payment data dynamically
$paymentData = [
    'id' => time(), // Payment ID (unique identifier for the transaction)
    'payment_amount' => 100.50, // Payment amount
    'attribute_id' => time(), // Attribute ID (order or basket reference)
    'payer_information' => [
        'name' => 'John Doe', // Payer's name
        'phone' => '1234567890', // Payer's phone number
        'email' => 'payer@example.com', // Payer's email address
    ],
    'created_at' => date('Y-m-d H:i:s'), // Payment creation timestamp
];

// Process payment and get the parameters
$params = $payfast->processPayment($paymentData);

// Define the redirect URL
$redirectUrl = "https://ipguat.apps.net.pk/Ecommerce/api/Transaction/PostTransaction"; // Test redirect URL

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PayFast Payment</title>
</head>

<body>
    <form action="<?= htmlspecialchars($redirectUrl) ?>" method="post" id="payfast-payment-form">
        <?php foreach ($params as $key => $value): ?>
            <input type="hidden" name="<?= htmlspecialchars($key) ?>" value="<?= htmlspecialchars($value) ?>">
        <?php endforeach; ?>
    </form>
    <script>
        // Automatically submit the form when the page is loaded
        document.getElementById('payfast-payment-form').submit();
    </script>
</body>

</html>