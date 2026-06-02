<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Models\UserAchievement;
use App\Models\UserO2Stat;
use App\Services\O2Service;
use Illuminate\Support\Facades\Auth;

class AchievementController extends Controller
{
    public function __construct(private O2Service $o2Service) {}

    /**
     * GET /achievement — Halaman progress & daftar badge user
     */
    public function index()
    {
        $user   = Auth::user();
        $stats  = UserO2Stat::firstOrCreate(
            ['user_id' => $user->id],
            ['total_pohon' => 0, 'total_o2_kg_per_bulan' => 0]
        );

        $userAchievements = UserAchievement::where('user_id', $user->id)
            ->with('achievement')
            ->orderBy('diraih_pada', 'desc')
            ->get();

        $allAchievements   = Achievement::orderBy('threshold_o2')->get();
        $unlockedIds       = $userAchievements->pluck('achievement_id')->toArray();
        $badgeBerikutnya   = $this->o2Service->getBadgeBerikutnya($user->id);

        return view('achievement.index', compact(
            'stats',
            'userAchievements',
            'allAchievements',
            'unlockedIds',
            'badgeBerikutnya'
        ));
    }

    /**
     * GET /o2/stats — API endpoint untuk statistik O2 user
     */
    public function stats()
    {
        $user  = Auth::user();
        $stats = UserO2Stat::where('user_id', $user->id)->first();

        return response()->json([
            'total_pohon'           => $stats?->total_pohon ?? 0,
            'total_o2_kg_per_bulan' => $stats?->total_o2_kg_per_bulan ?? 0,
            'last_updated'          => $stats?->last_updated,
        ]);
    }

    /**
     * GET /achievement/progress — API untuk progress badge berikutnya
     */
    public function progress()
    {
        $user            = Auth::user();
        $stats           = UserO2Stat::where('user_id', $user->id)->first();
        $badgeBerikutnya = $this->o2Service->getBadgeBerikutnya($user->id);
        $totalO2         = $stats?->total_o2_kg_per_bulan ?? 0;

        $data = [
            'total_o2'        => $totalO2,
            'badge_berikutnya' => null,
        ];

        if ($badgeBerikutnya) {
            $sisaO2 = max(0, $badgeBerikutnya->threshold_o2 - $totalO2);
            $data['badge_berikutnya'] = [
                'nama'         => $badgeBerikutnya->nama,
                'icon'         => $badgeBerikutnya->badge_icon,
                'threshold_o2' => $badgeBerikutnya->threshold_o2,
                'sisa_o2'      => round($sisaO2, 2),
                'persen'       => $badgeBerikutnya->threshold_o2 > 0
                    ? min(100, round(($totalO2 / $badgeBerikutnya->threshold_o2) * 100))
                    : 100,
            ];
        }

        return response()->json($data);
    }
}
