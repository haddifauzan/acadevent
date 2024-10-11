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

        // Update acara yang sedang berlangsung
        Acara::where('tanggal_acara', '<=', $now->format('Y-m-d'))
            ->where('waktu_mulai', '<=', $now->format('H:i:s'))
            ->where('waktu_selesai', '>', $now->format('H:i:s'))
            ->where('status_acara', '!=', 'berlangsung')
            ->update(['status_acara' => 'berlangsung']);

        // Update acara yang sudah selesai
        Acara::where('tanggal_acara', '<', $now->format('Y-m-d'))
            ->orWhere(function ($query) use ($now) {
                $query->where('tanggal_acara', '=', $now->format('Y-m-d'))
                      ->where('waktu_selesai', '<', $now->format('H:i:s'));
            })
            ->where('status_acara', '!=', 'selesai')
            ->update(['status_acara' => 'selesai']);
        
        $this->info('Status acara berhasil diperbarui');
    }
}


