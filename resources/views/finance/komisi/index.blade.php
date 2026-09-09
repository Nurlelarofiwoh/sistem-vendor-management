@extends('layouts.app')

@section('title', 'Kelola Komisi — Finance')

@section('content')
<div class="py-4">
    <div class="container-fluid">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1 fw-bold">💰 Kelola Komisi Vendor</h1>
                <p class="text-muted mb-0">Tandai pelunasan komisi per-vendor & lihat status reminder email</p>
            </div>
        </div>

        {{-- Alert --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ $errors->first() }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Daftar Event --}}
        <div class="row g-3">
            @forelse($events as $event)
            @php
                $client = $event->client;
                $vendors = $client ? $client->vendors : collect();
                $totalVendor = $vendors->count();
                $lunas = $vendors->where('pivot.status_komisi', 'Lunas')->count();
                $belumLunas = $totalVendor - $lunas;
                $semuaLunas = $totalVendor > 0 && $lunas === $totalVendor;
            @endphp
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <div>
                                <h5 class="mb-0 fw-bold">{{ $event->nama_proyek }}</h5>
                                <small class="text-muted">
                                    {{ $client?->nama_klien }} ·
                                    {{ $client?->tanggal_acara?->format('d M Y') ?? '-' }} ·
                                    {{ $client?->tempat_acara ?? '-' }}
                                </small>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            {{-- Reminder Counter --}}
                            <div class="text-center">
                                <div class="badge bg-secondary rounded-pill px-3">
                                    📧 {{ $event->total_reminder_terkirim }} Reminder
                                </div>
                            </div>
                            {{-- Progress Pelunasan --}}
                            <div class="text-center">
                                @if($semuaLunas)
                                    <span class="badge bg-success rounded-pill px-3">✅ Semua Lunas</span>
                                @else
                                    <span class="badge bg-warning text-dark rounded-pill px-3">
                                        ⚠️ {{ $belumLunas }} Belum Lunas
                                    </span>
                                @endif
                            </div>
                            {{-- Status Proyek --}}
                            <span class="badge rounded-pill
                                {{ $event->status_proyek === 'Transaksi Komplit' ? 'bg-success' : 'bg-info text-white' }}">
                                {{ $event->status_proyek }}
                            </span>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        @if($vendors->isEmpty())
                            <p class="text-muted text-center py-3">Tidak ada vendor terlibat di event ini.</p>
                        @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-3">Vendor</th>
                                        <th>Kategori</th>
                                        <th>Nominal Komisi</th>
                                        <th>Status Komisi</th>
                                        <th>Rating Saat Ini</th>
                                        <th>Reminder Terkirim</th>
                                        <th class="text-end pe-3">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($vendors as $vendor)
                                    <tr>
                                        <td class="ps-3">
                                            <div class="fw-semibold">{{ $vendor->nama_vendor }}</div>
                                            <div class="text-muted small">{{ $vendor->email }}</div>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $vendor->kategori_jasa }}</span>
                                        </td>
                                        <td>
                                            @if($vendor->pivot->jumlah_komisi)
                                                <span class="fw-semibold text-primary">
                                                    Rp {{ number_format($vendor->pivot->jumlah_komisi, 0, ',', '.') }}
                                                </span>
                                            @else
                                                {{-- Form input nominal komisi --}}
                                                <form action="{{ route('finance.komisi.nominal', [$event, $vendor]) }}"
                                                      method="POST" class="d-flex gap-1" style="min-width:200px">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="number" name="jumlah_komisi" class="form-control form-control-sm"
                                                           placeholder="Nominal komisi" min="0" required>
                                                    <button type="submit" class="btn btn-sm btn-outline-primary">Set</button>
                                                </form>
                                            @endif
                                        </td>
                                        <td>
                                            @if($vendor->pivot->status_komisi === 'Lunas')
                                                <span class="badge rounded-pill bg-success">✅ Lunas</span>
                                                <div class="text-muted small mt-1">
                                                    {{ \Carbon\Carbon::parse($vendor->pivot->tanggal_bayar_komisi)->format('d M Y H:i') }}
                                                </div>
                                            @else
                                                <span class="badge rounded-pill bg-danger">❌ Belum Lunas</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="fw-semibold">
                                                @if($vendor->rating > 0)
                                                    ⭐ {{ $vendor->rating }}
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            @php $jumlahReminder = $vendor->pivot->jumlah_reminder_terkirim ?? 0; @endphp
                                            <span class="badge rounded-pill {{ $jumlahReminder >= 3 ? 'bg-danger' : ($jumlahReminder > 0 ? 'bg-warning text-dark' : 'bg-light text-dark') }}">
                                                📧 {{ $jumlahReminder }}x
                                            </span>
                                        </td>
                                        <td class="text-end pe-3">
                                            @if($vendor->pivot->status_komisi !== 'Lunas')
                                                <form action="{{ route('finance.komisi.tandaiLunas', [$event, $vendor]) }}"
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit"
                                                            class="btn btn-sm btn-success"
                                                            onclick="return confirm('Tandai komisi {{ $vendor->nama_vendor }} sebagai LUNAS?')">
                                                        💵 Transaksi Komplit
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-muted small">Sudah selesai</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <p class="text-muted fs-5">Tidak ada event dengan status Finish Event atau Transaksi Komplit.</p>
                    </div>
                </div>
            </div>
            @endforelse
        </div>

    </div>
</div>
@endsection
