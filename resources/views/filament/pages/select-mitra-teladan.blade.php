<x-filament-panels::page>
    <div class="flex justify-end mb-4">
        <a
            href="{{ route('export.nilai2.report', ['year' => $selectedYear, 'quarter' => $selectedQuarter]) }}"
            class="px-4 py-2 text-white rounded filament-button bg-primary-600"
            target="_blank"
        >export</a>
    </div>
    {{-- <pre class="p-2 mb-2 text-xs text-gray-400 bg-gray-100 rounded dark:bg-gray-800">$groupedByTeam: @json($groupedByTeam)</pre> --}}
    {{-- Debug output for troubleshooting --}}
    {{-- <pre class="p-2 mb-4 text-xs text-gray-400 bg-gray-100 rounded dark:bg-gray-800">$acceptedMitraTeladan: @json($acceptedMitraTeladan)</pre> --}}
    {{-- Top Mitra per Team Horizontal Stack --}}

    {{-- Filter Form and Table Section --}}
    <div class="mb-6">
        {{ $this->form }}
        <div class="mt-6">
            {{ $this->filterAction }}
        </div>
    </div>

    {{-- Top Mitra Teladan per Team (from mitra_teladans table) --}}
    <div class="mb-6">
        <div class="flex overflow-x-auto flex-row flex-nowrap gap-4 pb-2">
            @foreach($acceptedMitraTeladan as $index => $item)
                <div class="flex-shrink-0 p-4 w-72 bg-white rounded-lg shadow dark:bg-gray-800">

                    <div class="text-lg font-bold text-primary">{{ $item['team_name'] }}</div>
                    <div class="text-sm">Mitra Teladan: <span class="font-semibold">{{ $item['mitra_name'] }}</span></div>
                    <div class="text-gray-500">Rerata Rating: <span class="font-semibold">{{ is_numeric($item['avg_rating']) ? number_format($item['avg_rating'], 2) : '-' }}</span></div>
                    <div class="text-gray-500">Jumlah Survey: <span class="font-semibold">{{ $item['surveys_count'] }}</span></div>
                    <div class="text-gray-500">Rerata Nilai 2: <span class="font-semibold">{{ is_numeric($item['nilai2_average']) ? number_format($item['nilai2_average'], 2) : '-' }}</span></div>
                    <div class="text-gray-500">Peringkat Saat ini: <span class="font-semibold">{{ $item['ranking'] ?? '-' }}</span></div>
                    @if($item['mitra_name'] !== 'Belum ada Mitra Teladan' && !empty($item['mitra_id']))
                        <button
                            x-data
                            @click="$wire.dispatch('openNilai2Modal', [{{ $item['mitra_teladan_id'] }}])"
                            class="inline-flex items-center px-4 py-2 mt-3 text-sm font-semibold text-black dark:text-white bg-blue-600 rounded-lg shadow transition-all duration-150 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-400 focus:ring-offset-2 dark:bg-orange-500 dark:hover:bg-orange-600 dark:focus:ring-orange-400 dark:text-orange-100 {{ $item['user_has_nilai2'] ? 'opacity-50 cursor-not-allowed' : '' }}"
                            {{ $item['user_has_nilai2'] ? 'disabled' : '' }}
                        >
                            <svg class="mr-2 w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19.5 3 21l1.5-4L16.5 3.5z"/></svg>
                            Nilai
                        </button>
                        @if(in_array(auth()->user()->getRoleNames()->first(), ['super_admin', 'ketua_sdm']))
                            @if($item['is_finalized'])
                                <button
                                    disabled
                                    class="inline-flex items-center px-4 py-2 mt-3 text-sm font-semibold text-black bg-green-500 rounded-lg shadow opacity-70 cursor-not-allowed dark:text-white"
                                >Sudah Final</button>
                            @elseif($item['is_finalizable'])
                                <button
                                    wire:click="openFinalisasiModal({{ $item['mitra_teladan_id'] }})"
                                    class="inline-flex items-center px-4 py-2 mt-3 text-sm font-semibold text-black bg-blue-600 rounded-lg shadow transition-all duration-150 dark:text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2"
                                >Finalisasi</button>
                            @endif
                        @endif
                    @endif
                    {{-- <pre>
mitra_id: {{ $item['mitra_id'] ?? 'null' }}
mitra_teladan_id: {{ $item['mitra_teladan_id'] ?? 'null' }}
user_has_nilai2: {{ $item['user_has_nilai2'] ? 'true' : 'false' }}
                    </pre> --}}
                </div>
            @endforeach
        </div>
    </div>

    {{-- Only one table for top mitra per team --}}
    <div class="p-4 bg-gray-100 rounded-lg shadow-md dark:bg-gray-800 dark:border-gray-700">
        <div class="mb-4">
            <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                Mitra Teladan Tahun {{ $selectedYear }} Triwulan {{ $selectedQuarter }}
            </h2>
            <p class="text-sm text-gray-600 dark:text-gray-400">Status: </p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-200">
                    <tr>
                        <th class="px-6 py-3">ID Sobat</th>
                        <th class="px-6 py-3">Nama</th>
                        <th class="px-6 py-3">Rating</th>
                        <th class="px-6 py-3">Banyak Survey</th>
                        <th class="px-6 py-3">Team</th>
                        <th class="px-6 py-3">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($groupedByTeam as $mitra)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <td class="px-6 py-4">{{ $mitra['mitra_id'] }}</td>
                            <td class="px-6 py-4">{{ $mitra['mitra_name'] }}</td>
                            <td class="px-6 py-4">{{ number_format((float)($mitra['avg_rating'] ?? 0), 2) }}</td>
                            <td class="px-6 py-4">{{ $mitra['surveys_count'] }}</td>
                            <td class="px-6 py-4">{{ $mitra['team_name'] ?? $mitra['team_id'] }}</td>
                            <td class="px-6 py-4">
                                @if(in_array($mitra['team_id'], $canAcceptTeamIds))
                                    @if(isset($acceptedMitraIdByTeam[$mitra['team_id']]) && $acceptedMitraIdByTeam[$mitra['team_id']] == $mitra['mitra_id'])
                                        <button class="px-4 py-2 text-white bg-gray-400 rounded" disabled>Accepted</button>
                                    @elseif(empty($acceptedMitraIdByTeam[$mitra['team_id']]))
                                        <button
                                            class="px-4 py-2 text-white bg-blue-600 rounded hover:bg-blue-700"
                                            wire:click="acceptMitra('{{ $mitra['mitra_id'] }}')"
                                        >Accept</button>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-4 text-center text-gray-500">No data found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <livewire:penilaian2-input />

    @if($showFinalisasiModal)
        <div
            class="flex fixed inset-0 z-50 justify-center items-center"
            style="background: rgba(16, 23, 42, 0.5);"
            x-data
            x-cloak
        >
            <div
                class="relative mx-auto w-full max-w-lg bg-white rounded-xl ring-1 shadow-xl transition-all dark:bg-gray-900 ring-black/10 dark:ring-white/10"
                style="box-shadow: 0 10px 15px -3px rgba(16,23,42,0.1), 0 4px 6px -4px rgba(16,23,42,0.1);"
            >
                <button
                    wire:click="$set('showFinalisasiModal', false)"
                    class="absolute top-4 right-4 text-xl font-bold text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 focus:outline-none"
                    aria-label="Tutup"
                    type="button"
                >&times;</button>
                <div class="px-6 pt-6 pb-2">
                    <h2 class="mb-2 text-lg font-bold text-gray-900 dark:text-white">Konfirmasi Finalisasi</h2>
                    <p class="mb-4 text-gray-700 dark:text-gray-300">
                        Apakah Anda yakin ingin melakukan finalisasi untuk Mitra Teladan ini? Tindakan ini tidak dapat dibatalkan.
                    </p>
                </div>
                <div class="flex gap-2 justify-end px-6 pb-6">
                    <button
                        wire:click="$set('showFinalisasiModal', false)"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg transition filament-button filament-modal-close-button dark:bg-gray-800 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-700"
                    >Batal</button>
                    <button
                        wire:click="finalizeAction"
                        class="px-4 py-2 text-sm font-medium text-white rounded-lg transition filament-button bg-primary-600 dark:bg-primary-500 hover:bg-primary-700 dark:hover:bg-primary-600"
                    >Finalisasi</button>
                </div>
            </div>
        </div>
    @endif

</x-filament-panels::page>
