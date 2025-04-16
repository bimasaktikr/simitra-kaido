<x-filament::page>
    {{ $this->form }}

    <x-filament::section
        heading="Top Mitra Performance"
        description="Quarterly ranking based on survey ratings and participation"
        class="bg-white shadow-sm rounded-xl dark:bg-gray-800"
    >
        <div
            x-data="{
                isConfirmModalOpen: false,
                confirmingMitra: null,
                openModal(mitra) {
                    this.confirmingMitra = mitra;
                    this.isConfirmModalOpen = true;
                },
                closeModal() {
                    this.isConfirmModalOpen = false;
                    this.confirmingMitra = null;
                },
                acceptMitra() {
                    alert('Accepted ' + this.confirmingMitra.mitra_name);
                    this.closeModal();
                }
            }"
        >
            <div class="overflow-hidden border border-gray-200 rounded-lg dark:border-gray-700">
                <table class="w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700/20">
                        <tr>
                            <th>Nama</th>
                            <th>Sobat ID</th>
                            <th>Team</th>
                            <th>Rating</th>
                            <th>Surveys</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($this->tableData as $item)
                            <tr>
                                <td>{{ $item['mitra_name'] }}</td>
                                <td>{{ $item['sobat_id'] }}</td>
                                <td>Team {{ $item['team_id'] }}</td>
                                <td>{{ number_format((float)$item['avg_rating'], 2) }} ★</td>
                                <td>{{ $item['surveys_count'] }}</td>
                                <td>
                                    <x-filament::button
                                        @click="openModal({{ json_encode($item) }})"
                                        color="primary"
                                    >
                                        Accept
                                    </x-filament::button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <template x-if="isConfirmModalOpen">
                <x-filament::modal
                    id="confirm-mitra-modal"
                    width="2xl"
                    slide-over
                    :heading="'Konfirmasi Mitra Teladan'"
                    x-on:close="closeModal()"
                >
                    <div class="space-y-4">
                        <p>Apakah kamu yakin ingin memilih <strong x-text="confirmingMitra?.mitra_name"></strong> sebagai Mitra Teladan?</p>
                        <ul class="text-sm">
                            <li>Rerata Nilai 1: <span x-text="confirmingMitra?.avg_rating ?? '-'"></span></li>
                            <li>Jumlah Survey: <span x-text="confirmingMitra?.surveys_count ?? '-'"></span></li>
                            <li>Team ID: <span x-text="confirmingMitra?.team_id ?? '-'"></span></li>
                        </ul>
                    </div>
                    <div class="flex justify-end mt-6 space-x-2">
                        <x-filament::button color="secondary" @click="closeModal()">Batal</x-filament::button>
                        <x-filament::button color="primary" @click="acceptMitra()">Terima</x-filament::button>
                    </div>
                </x-filament::modal>
            </template>
        </div>
    </x-filament::section>
</x-filament::page>