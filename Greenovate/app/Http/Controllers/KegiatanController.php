<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KegiatanController extends Controller
{
    public function index(Request $request)
    {
        // Extract filters
        $lokasi = $request->input('lokasi');
        $tanggal = $request->input('tanggal');
        $status = $request->input('status');
        $page = $request->input('page', 1);

        // Define a unique cache key based on query parameters (GN-26 Perform & Cache)
        $cacheKey = "kegiatan_list_lokasi_{$lokasi}_tanggal_{$tanggal}_status_{$status}_page_{$page}";

        // Cache the query results for 15 minutes (900 seconds)
        // Adjust the TTL based on application needs
        $kegiatans = \Illuminate\Support\Facades\Cache::remember($cacheKey, 900, function () use ($lokasi, $tanggal, $status) {
            $query = \App\Models\Kegiatan::query();

            // PBI-52 & PBI-54: Filter logic matching indexes
            if (!empty($lokasi)) {
                $query->where('lokasi', 'ilike', '%' . $lokasi . '%');
            }

            if (!empty($tanggal)) {
                $query->whereDate('tanggal', '=', $tanggal);
            }

            if (!empty($status)) {
                $query->where('status', '=', $status);
            }

            // Order by most recent event date or closest event date
            $query->orderBy('tanggal', 'asc');

            // Pagination (M)
            return $query->paginate(10);
        });

        return view('kegiatan.index', compact('kegiatans'));
    }
