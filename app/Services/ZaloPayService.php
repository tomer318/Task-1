<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ZaloPayService
{
    public static function createPaymentUrl(Order $order): ?string
    {
        // Cấu hình Sandbox chính thức của ZaloPay
        $appId = 2553;
        $key1 = "PcY4iZIKFCIdgZvA6ueMcMHHUbRLYjPL";
        $endpoint = "https://sb-openapi.zalopay.vn/v2/create";

        $transID = rand(100000, 999999);
        $appTime = round(microtime(true) * 1000);
        $appTransId = date("ymd") . "_" . $transID;

        $items = [];
        foreach ($order->items as $item) {
            $items[] = [
                'itemid' => (string) ($item->product_id ?? $item->id),
                'itemname' => $item->product_name,
                'itemprice' => (int) $item->price,
                'itemquantity' => (int) $item->quantity
            ];
        }

        $embedData = [
            'redirecturl' => route('checkout.zalopay.callback')
        ];

        $orderData = [
            'app_id' => $appId,
            'app_user' => $order->customer_name ?? "User",
            'app_time' => $appTime,
            'amount' => (int) round($order->total_price),
            'app_trans_id' => $appTransId,
            'embed_data' => json_encode($embedData),
            'item' => json_encode($items),
            'description' => "Thanh toan don hang TechZone #" . $order->order_code,
            'bank_code' => "",
            'callback_url' => route('checkout.zalopay.callback')
        ];

        // Chuỗi hash theo tài liệu chuẩn của ZaloPay API v2: app_id|app_trans_id|app_user|amount|app_time|embed_data|item
        $data = $orderData['app_id'] . "|" . 
                $orderData['app_trans_id'] . "|" . 
                $orderData['app_user'] . "|" . 
                $orderData['amount'] . "|" . 
                $orderData['app_time'] . "|" . 
                $orderData['embed_data'] . "|" . 
                $orderData['item'];

        $orderData['mac'] = hash_hmac("sha256", $data, $key1);

        try {
            $response = Http::withoutVerifying()
                ->asForm()
                ->post($endpoint, $orderData);

            $result = $response->json();
            Log::info('ZALOPAY_GATEWAY_RESULT:', $result ?? ['raw' => $response->body()]);

            if (isset($result['return_code']) && $result['return_code'] == 1) {
                // Lưu lại app_trans_id vào order notes hoặc metadata nếu cần đối soát
                return $result['order_url'];
            }

            return null;
        } catch (\Exception $e) {
            Log::error('ZALOPAY_EXCEPTION: ' . $e->getMessage());
            return null;
        }
    }
}