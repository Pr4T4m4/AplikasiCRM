<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Tier;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $currentTier = $user->tier;
        $nextTier = null;
        $progressPercentage = 0;
        $pointsToNextLevel = 0;
        $isHighestTier = false; // Tambahkan variabel ini

        if ($currentTier) {
            $allTiers = Tier::orderBy('min_points', 'asc')->get();

            $nextTierIndex = $allTiers->search(function ($tier) use ($currentTier) {
                return $tier->id === $currentTier->id;
            });

            if ($nextTierIndex !== false) {
                // Periksa apakah ada tier setelah tier saat ini
                if (isset($allTiers[$nextTierIndex + 1])) {
                    $nextTier = $allTiers[$nextTierIndex + 1];
                } else {
                    $isHighestTier = true; // User berada di tier tertinggi
                }
            }

            if ($nextTier) {
                $pointsRequiredForNextTier = $nextTier->min_points;
                $userCurrentPoints = $user->current_points;

                $pointsToNextLevel = $pointsRequiredForNextTier - $userCurrentPoints;

                if ($pointsToNextLevel < 0) {
                    $pointsToNextLevel = 0; // Pastikan tidak negatif
                }

                if ($nextTier->min_points > $currentTier->min_points) {
                    $tierRange = $nextTier->min_points - $currentTier->min_points;
                    $pointsIntoCurrentTier = $userCurrentPoints - $currentTier->min_points;
                    $progressPercentage = ($tierRange > 0) ? ($pointsIntoCurrentTier / $tierRange) * 100 : 0;
                } else {
                    $progressPercentage = 100;
                }

                $progressPercentage = max(0, min(100, $progressPercentage));

            } else { // Jika nextTier adalah null, berarti user di tier tertinggi
                $progressPercentage = 100;
                $pointsToNextLevel = 0;
                $isHighestTier = true; // Konfirmasi user di tier tertinggi
            }
        }

        return view('dashboard', compact('user', 'currentTier', 'nextTier', 'progressPercentage', 'pointsToNextLevel', 'isHighestTier'));
    }
}