<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserO2Stat;
use App\Models\Achievement;

class AchievementController extends Controller
{
    public function progress()
    {
        $user = Auth::user();
        $stat = $user->o2Stat;
        $currentO2 = $stat ? $stat->total_o2_kg_per_bulan : 0.0;

        $nextBadge = Achievement::where('threshold_o2', '>', $currentO2)
            ->orderBy('threshold_o2', 'asc')
            ->first();

        return response()->json([
            'current_o2' => $currentO2,
            'next_badge' => $nextBadge ? [
                'nama_gelar' => $nextBadge->nama_gelar,
                'threshold_o2' => $nextBadge->threshold_o2,
                'remaining' => $nextBadge->threshold_o2 - $currentO2
            ] : null,
            'achieved' => $user->achievements()->with('achievement')->get()
        ]);
    }

    public function stats()
    {
        $user = Auth::user();
        $stat = $user->o2Stat;

        if (!$stat) {
            return response()->json([
                'total_pohon' => 0.0,
                'total_o2_kg_per_bulan' => 0.0,
            ]);
        }

        return response()->json([
            'total_pohon' => $stat->total_pohon,
            'total_o2_kg_per_bulan' => $stat->total_o2_kg_per_bulan,
        ]);
    }
}
