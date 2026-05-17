<?php

namespace App\Services;

use App\Models\Avaliacao;
use App\Models\UserScore;

class ReputationService
{
    public function recalculate(int $userId): void
    {
        $stats = (new Avaliacao())->calculateAverage($userId);
        $average = round((float) $stats['average_rating'], 2);
        $total = (int) $stats['total_reviews'];
        $points = (int) round($average * 20 + $total * 2);
        $level = $this->levelFor($points);

        (new UserScore())->upsert($userId, $average, $total, $points, $level);
    }

    private function levelFor(int $points): string
    {
        return match (true) {
            $points >= 250 => 'gold',
            $points >= 120 => 'silver',
            $points >= 50 => 'bronze',
            default => 'beginner',
        };
    }
}
