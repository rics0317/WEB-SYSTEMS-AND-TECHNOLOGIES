<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{
    private $merchantId = 'bitscalp.online';
    private $merchantKey = '04b5ccb52452097e1afe89e0e0f4ab5b';
    private $paymentUrl = 'https://CTpay.la2568.site/api/transfer';

    // Show the payment form (optional, if needed)
    public function showPaymentPage()
    {
        return view('payment'); // Create a payment view in resources/views
    }

    // Process payment
    public function processPayment(Request $request)
    {
        $orderId = 'ORDER' . time();
        $amount = $request->input('amount', '120.00'); // Example: PHP 120.00
        $callbackUrl = route('payment.callback');
        $returnUrl = route('payment.success');

        // Build data array
        $data = [
            'merchant' => $this->merchantId,
            'payment_type' => '1', // 1 = Scan code (GCash QR)
            'amount' => $amount,
            'order_id' => $orderId,
            'bank_code' => 'gcash',
            'callback_url' => $callbackUrl,
            'return_url' => $returnUrl,
        ];

        // Generate signature
        $data['sign'] = $this->generateSignature($data);

        // Send payment request
        $response = Http::asForm()->post($this->paymentUrl, $data);

        if ($response->successful()) {
            $responseData = $response->json();
            if ($responseData['status'] == 1) {
                return redirect($responseData['redirect_url']); // Redirect to GCash payment page
            }
        }

        return back()->with('error', 'Failed to initiate payment.');
    }

    // Handle payment callback
    public function handleCallback(Request $request)
    {
        $data = $request->all();

        // Validate signature
        $computedSign = $this->generateSignature($data);
        if ($data['sign'] !== $computedSign) {
            return response('Invalid signature', 400);
        }

        // Process payment status
        if ($data['status'] == 5) { // Status 5 = Success
            // Update order status in your database
            // Example: Order::where('order_id', $data['order_id'])->update(['status' => 'Paid']);
        }

        return response('SUCCESS', 200); // Required response for callback
    }

    // Redirect after successful payment
    public function paymentSuccess()
    {
        return view('success'); // Create a success view in resources/views
    }

    // Generate MD5 Signature
    private function generateSignature(array $data)
    {
        ksort($data); // Sort data by key in ASCII order
        $queryString = http_build_query($data) . "&key={$this->merchantKey}";
        return md5($queryString);
    }
}
