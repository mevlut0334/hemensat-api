<?php

namespace App\Jobs;

use App\Services\FcmService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendDistrictNotification implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;
    public int $backoff = 10;

    public function __construct(
        public readonly int    $districtId,
        public readonly string $title,
        public readonly string $body,
        public readonly array  $data = [],
    ) {}

    public function handle(FcmService $fcmService): void
    {
        $fcmService->sendToDistrict(
            districtId: $this->districtId,
            title:      $this->title,
            body:       $this->body,
            data:       $this->data,
        );
    }
}
