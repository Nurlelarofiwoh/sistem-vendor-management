<?php

use Illuminate\Support\Facades\Schedule;

// =========================================================================
// JADWAL HARIAN — Sistem Informasi Strategic Partnership
// =========================================================================

// Hitung penalti rating vendor berdasarkan keterlambatan komisi (07:00)
Schedule::command('vms:hitung-penalti')->dailyAt('07:00');

// Kirim reminder email tagihan komisi ke vendor belum lunas (08:00)
// Sistem otomatis mengirim H+1, H+7, dan H+30 sejak status 'Finish Event'
Schedule::command('vms:kirim-reminder-komisi')->dailyAt('08:00');

// Cek keterlambatan komisi vendor dan update skor finansial otomatis
Schedule::command('vendor:check-finance')->dailyAt('06:00');
