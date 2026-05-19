<?php

namespace App\Services;

use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use App\Models\UserFcmToken;
use Illuminate\Support\Facades\Log;

class FcmService
{
    public function __construct(private Messaging $messaging) {}

    public function sendToDistrict(int $districtId, string $title, string $body, array $data = []): void
    {
        $tokens = UserFcmToken::whereHas('user', function ($q) use ($districtId) {
            $q->where('district_id', $districtId);
        })->pluck('fcm_token')->toArray();

        Log::info('FCM sendToDistrict', [
            'district_id' => $districtId,
            'token_count' => count($tokens),
        ]);

        if (empty($tokens)) {
            Log::info('FCM: Token bulunamadı, bildirim gönderilmedi.', ['district_id' => $districtId]);
            return;
        }

        $notification = Notification::create($title, $body);

        $message = CloudMessage::new()
            ->withNotification($notification)
            ->withData($data);

        try {
            $this->messaging->sendMulticast($message, $tokens);
            Log::info('FCM: Bildirim gönderildi.', ['district_id' => $districtId, 'token_count' => count($tokens)]);
        } catch (\Exception $e) {
            Log::error('FCM: Bildirim gönderilemedi.', ['error' => $e->getMessage()]);
        }
    }
}
