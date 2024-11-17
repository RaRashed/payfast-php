<?php

namespace Rashed\AllGateway;

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

    // Constructor accepts dynamic URLs and other parameters
    public function __construct($merchant_id = null, $merchant_name = null, $secured_key = null, $tokenApiUrl = null, $redirectUrl = null, $successUrl = null, $failUrl = null, $checkoutUrl = null)
    {
        // Set default values if not passed
        $this->merchant_id = $merchant_id ?? 'default_merchant_id';
        $this->merchant_name = $merchant_name ?? 'default_merchant_name';
        $this->secured_key = $secured_key ?? 'default_secured_key';
        $this->tokenApiUrl = $tokenApiUrl ?? 'https://ipguat.apps.net.pk/Ecommerce/api/Transaction/GetAccessToken';
        $this->redirectUrl = $redirectUrl ?? 'https://ipguat.apps.net.pk/Ecommerce/api/Transaction/PostTransaction';
        $this->successUrl = $successUrl;
        $this->failUrl = $failUrl;
        $this->checkoutUrl = $checkoutUrl;
    }

    public function processPayment($payment_data)
    {
        // Decode payer information
        $payer = $payment_data['payer_information'];

        // Prepare parameters for token request
        $urlPostParams = sprintf(
            'MERCHANT_ID=%s&SECURED_KEY=%s&TXNAMT=%s&BASKET_ID=%s',
            $this->merchant_id,
            $this->secured_key,
            $payment_data['payment_amount'],
            $payment_data['attribute_id']
        );

        // Make cURL request to get access token
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->tokenApiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $urlPostParams);
        curl_setopt($ch, CURLOPT_USERAGENT, 'CURL/PHP PayFast Example');
        $response = curl_exec($ch);
        curl_close($ch);

        // Decode response and get access token
        $payload = json_decode($response, true);
        $token = isset($payload['ACCESS_TOKEN']) ? $payload['ACCESS_TOKEN'] : '';

        // Prepare parameters for the payment request
        $requestParams = [
            'MERCHANT_ID' => $this->merchant_id,
            'Merchant_Name' => 'Easily',
            'TOKEN' => $token,
            'PROCCODE' => 00,
            'TXNAMT' => $payment_data['payment_amount'],
            'CUSTOMER_MOBILE_NO' => $payer['phone'],
            'CUSTOMER_EMAIL_ADDRESS' => $payer['email'],
            'SIGNATURE' => bin2hex(random_bytes(6)) . '-' . $payment_data['attribute_id'],
            'VERSION' => 'MERCHANT-CART-0.1',
            'TXNDESC' => 'Payfast Payment',
            'SUCCESS_URL' => $this->successUrl, // Use dynamic success URL
            'FAILURE_URL' => $this->failUrl,   // Use dynamic failure URL
            'BASKET_ID' => $payment_data['attribute_id'],
            'ORDER_DATE' => $payment_data['created_at'],
            'CHECKOUT_URL' => $this->checkoutUrl, // Use dynamic checkout URL
        ];
        return $requestParams;
    }
}
