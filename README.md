# All Gateway Package

The `PayFast` package allows easy integration with payment gateway in one unified API. It is simple to install, configure, and use.

---

## Installation

You can install the package via Composer:

```bash
composer require rarashed/payfast-sdk
```

#Code example

```bash
use RaRashed\PayFastSdk\PayFast;

$payfast = new PayFast(
    'merchant_id', // Your Merchant ID
    'merchant_name', // Your Merchant Name
    'secured_key', // Your Secured Key
    'token_api_url', // Your Token API URL
    'redirect_url', // Your Redirect URL
    'success_url', // Your Success URL
    'fail_url', // Your Failure URL
    'checkout_url' // Your Checkout URL
);

$payment_data = [
    'payment_amount' => 100.50,
    'payer_information' => [
        'name' => 'John Doe',
        'phone' => '1234567890',
        'email' => 'payer@example.com'
    ],
    'created_at' => now(),
];

$response = $payfast->processPayment($payment_data);
```

#Pay HTML Form Code

```bash
$redirectUrl = "https://ipguat.apps.net.pk/Ecommerce/api/Transaction/PostTransaction"; // Test redirect URL
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PayFast Payment</title>
</head>
<body>
    <form action="<?= htmlspecialchars($redirectUrl) ?>" method="post" id="payfast-payment-form">
        <?php foreach ($response as $key => $value): ?>
            <input type="hidden" name="<?= htmlspecialchars($key) ?>" value="<?= htmlspecialchars($value) ?>">
        <?php endforeach; ?>
    </form>
    <script>
        // Automatically submit the form when the page is loaded
        document.getElementById('payfast-payment-form').submit();
    </script>
</body>
</html>
```

#Token and Redirect URL will be country wise. Test Token URL

```bash
https://ipguat.apps.net.pk/Ecommerce/api/Transaction/GetAccessToken
```

##Test Redirect URL

```bash
https://ipguat.apps.net.pk/Ecommerce/api/Transaction/PostTransaction

```
