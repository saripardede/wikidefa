<?php

namespace App\Observers;
use Illuminate\Support\Facades\Log;
use App\Models\Tutorial;
use App\Models\Reward;

class TutorialObserver
{
    public function updated(Tutorial $tutorial)
    {
        // Jika status berubah
        if ($tutorial->isDirty('status')) {

            // Jika status berubah menjadi approved, buat atau update reward
            if ($tutorial->status === 'approved') {
                Reward::updateOrCreate(
    ['user_id' => $tutorial->user_id, 'tutorial_id' => $tutorial->id],
    ['judul' => $tutorial->judul, 'deskripsi' => 'Reward dari tutorial', 'poin' => 10]
);

            } else {
                // Jika status berubah menjadi bukan approved, hapus reward-nya
                Reward::where('tutorial_id', $tutorial->id)->delete();

                // ATAU: alternatif, set poin ke 0 jika ingin tetap menyimpan datanya
                // Reward::where('tutorial_id', $tutorial->id)->update(['poin' => 0]);
            }
        }
    }
}
