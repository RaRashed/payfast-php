<?php

namespace RaRashed\AllGateway;

class Payfast
{
    protected $merchant_id;
    protected $secured_key;
    protected $tokenApiUrl;
    protected $redirectUrl;
    protected $successUrl;
    protected $failUrl;
    protected $checkoutUrl;
    protected $merchant_name;

    public function __construct(
        $merchant_id = null,
        $merchant_name = null,
        $secured_key = null,
        $tokenApiUrl = null,
        $redirectUrl = null,
        $successUrl = null,
        $failUrl = null,
        $checkoutUrl = null
    ) {
        $this->merchant_id = $merchant_id ?? 'default_merchant_id';
        $this->merchant_name = $merchant_name ?? 'default_merchant_name';
        $this->secured_key = $secured_key ?? 'default_secured_key';
        $this->tokenApiUrl = $tokenApiUrl ?? 'https://ipguat.apps.net.pk/Ecommerce/api/Transaction/GetAccessToken';
        $this->redirectUrl = $redirectUrl ?? 'https://ipguat.apps.net.pk/Ecommerce/api/Transaction/PostTransaction';
        $this->successUrl = $successUrl;
        $this->failUrl = $failUrl;
        $this->checkoutUrl = $checkoutUrl;
    }

    public function processPayment(array $payment_data): array
    {
        // Validate required fields
        if (!isset($payment_data['payer_information'], $payment_data['payment_amount'], $payment_data['attribute_id'], $payment_data['created_at'])) {
            throw new \InvalidArgumentException('Missing required payment data.');
        }

        $payer = $payment_data['payer_information'];

        $urlPostParams = sprintf(
            'MERCHANT_ID=%s&SECURED_KEY=%s&TXNAMT=%s&BASKET_ID=%s',
            $this->merchant_id,
            $this->secured_key,
            $payment_data['payment_amount'],
            $payment_data['attribute_id']
        );

        // Make cURL request
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->tokenApiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $urlPostParams);
        curl_setopt($ch, CURLOPT_USERAGENT, 'CURL/PHP PayFast Example');
        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            throw new \Exception('cURL Error: ' . curl_error($ch));
        }

        curl_close($ch);

        $payload = json_decode($response, true);
        $token = $payload['ACCESS_TOKEN'] ?? null;

        if (empty($token)) {
            throw new \Exception('Failed to fetch access token from the API.');
        }

        // Prepare request parameters
        return [
            'MERCHANT_ID' => $this->merchant_id,
            'Merchant_Name' => $this->merchant_name,
            'TOKEN' => $token,
            'PROCCODE' => 00,
            'TXNAMT' => $payment_data['payment_amount'],
            'CUSTOMER_MOBILE_NO' => $payer['phone'],
            'CUSTOMER_EMAIL_ADDRESS' => $payer['email'],
            'SIGNATURE' => bin2hex(random_bytes(6)) . '-' . $payment_data['attribute_id'],
            'VERSION' => 'MERCHANT-CART-0.1',
            'TXNDESC' => 'Payfast Payment',
            'SUCCESS_URL' => $this->successUrl,
            'FAILURE_URL' => $this->failUrl,
            'BASKET_ID' => $payment_data['attribute_id'],
            'ORDER_DATE' => $payment_data['created_at'],
            'CHECKOUT_URL' => $this->checkoutUrl,
        ];
    }
}
