<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Acara;
use Carbon\Carbon;

class UpdateStatusAcara extends Command
{
    protected $signature = 'acara:update-status';
    protected $description = 'Update status acara berdasarkan waktu acara';

    public function handle()
    {
        $now = Carbon::now();

        try {
            // Status acara
            $statusBerlangsung = Acara::STATUS_BERLANGSUNG;
            $statusSelesai = Acara::STATUS_SELESAI;

            // Update acara yang sedang berlangsung
            $updatedBerlangsung = Acara::whereDate('tanggal_acara', '<=', $now->format('Y-m-d'))
                ->whereTime('waktu_mulai', '<=', $now->format('H:i:s'))
                ->whereTime('waktu_selesai', '>', $now->format('H:i:s'))
                ->where('status_acara', '!=', $statusBerlangsung)
                ->update(['status_acara' => $statusBerlangsung]);

            // Update acara yang sudah selesai
            $updatedSelesai = Acara::whereDate('tanggal_acara', '<', $now->format('Y-m-d'))
                ->orWhere(function ($query) use ($now) {
                    $query->whereDate('tanggal_acara', '=', $now->format('Y-m-d'))
                          ->whereTime('waktu_selesai', '<', $now->format('H:i:s'));
                })
                ->where('status_acara', '!=', $statusSelesai)
                ->update(['status_acara' => $statusSelesai]);

            $this->info("Status acara berhasil diperbarui. 
                Acara berlangsung: $updatedBerlangsung, 
                Acara selesai: $updatedSelesai");
        } catch (\Exception $e) {
            $this->error("Terjadi kesalahan saat memperbarui status acara: " . $e->getMessage());
        }
    }
}
    