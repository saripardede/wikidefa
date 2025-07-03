<?php

namespace App\Services;

use App\Models\Tutorial;

class TutorialApprovalService
{
    public function handle(Tutorial $tutorial, string $status, ?int $points = null): void
    {
        $tutorial->status = $status;

        if ($status === 'disetujui' && $points !== null) {
            $tutorial->points = $points;
            $tutorial->approved_at = now();

            // Tambahan jika kamu punya sistem reward atau notifikasi:
            // $tutorial->user->notify(new TutorialApprovedNotification($tutorial));
            // RewardService::give($tutorial->user, $points);
        }

        $tutorial->save();
    }
}
