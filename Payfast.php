<?php

require 'vendor/autoload.php';

use Rashed\AllGateway\PayFast;

$payfast = new Payfast(
    '102', //merchant id
    'rashed', //merchant name
    'zWHjBp2AlttNu1sK', //secured key
    'https://ipguat.apps.net.pk/Ecommerce/api/Transaction/GetAccessToken', // token API URL
    'https://ipguat.apps.net.pk/Ecommerce/api/Transaction/PostTransaction', // redirect URL
    "https://webhook.site/2d8d8613-7107-4fa1-8e0b-1aa68a8e19c3", // dynamic success route
    "https://webhook.site/2d8d8613-7107-4fa1-8e0b-1aa68a8e19c3", // dynamic failure route
    "https://webhook.site/2d8d8613-7107-4fa1-8e0b-1aa68a8e19c3" // dynamic checkout route
);
$payment_data = [
    'id' => time(),  // Payment ID (you can dynamically generate or fetch it from DB)
    'payment_amount' => 100.50,  // Payment amount (the total amount for the transaction)
    'attribute_id' => time(),  // Attribute ID (likely a reference to the basket or order)
    'payer_information' => [
        'name' => 'John Doe',  // Payer's name
        'phone' => '1234567890',  // Payer's phone number
        'email' => 'payer@example.com',  // Payer's email address
    ],
    'created_at' => date('Y-m-d H:i:s'),  // Timestamp when the payment data was created (use current time)
];
$params = $payfast->processPayment($payment_data);
$redirectUrl = "https://ipguat.apps.net.pk/Ecommerce/api/Transaction/PostTransaction"; //test redirect url
?>
<html lang="en">
<head>
    <title>
        {{ translate('PayFast Payment') }}
    </title>
</head>
<body>
    <form action="<?=$redirectUrl?>" method="post" id="PayFast_payment_form" name="from1">
        <?php foreach ($params as $a => $b): ?>
            <input type="hidden" name="<?= htmlspecialchars($a) ?>" value="<?= htmlspecialchars($b) ?>">
        <?php endforeach; ?>
    </form>
    <script>
        // Automatically submit the form when the page is loaded
        document.from1.submit();
    </script>
</body>
</html>

