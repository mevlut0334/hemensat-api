<?php

namespace App\Services;

use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use App\Models\UserFcmToken;

class FcmService
{
    public function __construct(private Messaging $messaging) {}

    public function sendToDistrict(int $districtId, string $title, string $body, array $data = []): void
    {
        $tokens = UserFcmToken::whereHas('user', function ($q) use ($districtId) {
            $q->where('district_id', $districtId);
        })->pluck('fcm_token')->toArray();

        if (empty($tokens)) {
            return;
        }

        $notification = Notification::create($title, $body);

        $message = CloudMessage::new()
            ->withNotification($notification)
            ->withData($data);

        $this->messaging->sendMulticast($message, $tokens);
    }
}
