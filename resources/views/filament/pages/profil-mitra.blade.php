<x-filament-panels::page>
    <div class="space-y-6">

        {{-- Dropdown Searchable --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Cari Mitra</h2>
            {{ $this->form }}
        </div>

        {{-- Summary Cards --}}
        @if($this->selectedMitra)
            @php
                $summary = $this->getSummary();
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

                {{-- Nama Mitra --}}
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">Nama Mitra</div>
                    <div class="text-xl font-bold text-gray-900 dark:text-white">
                        {{ $this->selectedMitra->name }}
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        ID: {{ $this->selectedMitra->sobat_id }}
                    </div>
                </div>

                {{-- Total Kegiatan --}}
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">Total Kegiatan</div>
                    <div class="text-3xl font-bold text-blue-600 dark:text-blue-400">
                        {{ $summary['total_kegiatan'] ?? 0 }}
                    </div>
                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                        Target: {{ $summary['total_target'] ?? 0 }}
                    </div>
                </div>

                {{-- Total Pendapatan --}}
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">Total Pendapatan</div>
                    <div class="text-xl font-bold text-green-600 dark:text-green-400">
                        @php
                            $pendapatan = $summary['total_pendapatan'] ?? 0;
                            $pendapatanFormatted = 'Rp ' . number_format($pendapatan, 0, ',', '.');
                        @endphp
                        {{ $pendapatanFormatted }}
                    </div>
                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                        Akumulasi dari semua kegiatan
                    </div>
                </div>

                {{-- Rata-rata Rerata Nilai --}}
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">Rata-rata Rerata Nilai</div>
                    <div class="text-3xl font-bold text-purple-600 dark:text-purple-400">
                        @php
                            $rerata = $summary['avg_rerata'] ?? 0;
                            $rerataFormatted = number_format($rerata, 2);
                        @endphp
                        {{ $rerataFormatted }}
                    </div>
                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                        Dari semua penilaian
                    </div>
                </div>

            </div>
        @endif

        {{-- Main Table --}}
        @if($this->selectedMitraId)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                {{ $this->table }}
            </div>
        @else
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-12 text-center">
                <div class="text-gray-400 dark:text-gray-600 mb-4">
                    <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">
                    Pilih Mitra
                </h3>
                <p class="text-gray-500 dark:text-gray-400">
                    Gunakan dropdown di atas untuk memilih mitra yang ingin dilihat profilnya
                </p>
            </div>
        @endif

    </div>
</x-filament-panels::page>
