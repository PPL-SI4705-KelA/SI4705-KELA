<?php

namespace App\Policies;

use App\Models\Kegiatan;
use App\Models\User;

/**
 * Policy untuk model Kegiatan.
 * Semua aksi CRUD hanya boleh dilakukan oleh Admin.
 */
class KegiatanPolicy
{
    /**
     * Lihat daftar kegiatan — semua user login boleh.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Lihat detail — semua user login boleh.
     */
    public function view(User $user, Kegiatan $kegiatan): bool
    {
        return true;
    }

    /**
     * Buat kegiatan baru — hanya admin.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Update kegiatan — hanya admin.
     */
    public function update(User $user, Kegiatan $kegiatan): bool
    {
        return $user->isAdmin();
    }

    /**
     * Hapus kegiatan — hanya admin, dan hanya jika belum ada pendaftar.
     * Jika sudah ada pendaftar → policy menolak → set status 'nonaktif' saja.
     */
    public function delete(User $user, Kegiatan $kegiatan): bool
    {
        if (! $user->isAdmin()) {
            return false;
        }

        // Blok hapus jika sudah ada pendaftar
        return ! $kegiatan->hasPendaftar();
    }

    /**
     * Restore soft-deleted kegiatan — hanya admin.
     */
    public function restore(User $user, Kegiatan $kegiatan): bool
    {
        return $user->isAdmin();
    }

    /**
     * Hard delete — hanya admin, hanya jika belum ada pendaftar.
     */
    public function forceDelete(User $user, Kegiatan $kegiatan): bool
    {
        return $user->isAdmin() && ! $kegiatan->hasPendaftar();
    }
}
