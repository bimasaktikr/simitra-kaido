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
                                        wire:click="confirmMitra({{ $item['mitra_id'] }})"
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
        

            <div 
                x-data="modalHandler('confirm-mitra-modal')" 
                x-init="init()" 
                x-on:open-modal.window="checkAndOpen($event)" 
                x-on:close-modal.window="checkAndClose($event)" 
                x-show="isOpen" 
                x-trap.noscroll="isOpen" 
                class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
                x-transition
                style="display: none;"
            >
                <div 
                    class="w-full max-w-lg p-6 bg-white rounded-lg shadow-lg dark:bg-gray-800" 
                    @click.outside="close()"
                    x-transition
                >
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-semibold">Konfirmasi Mitra Teladan</h2>
                        <button @click="close()" class="text-gray-500 hover:text-gray-700">
                            &times;
                        </button>
                    </div>

                    <div class="text-sm text-gray-700 dark:text-gray-200">
                        <p>Apakah kamu yakin ingin memilih <strong x-text="namaMitra"></strong> sebagai Mitra Teladan?</p>
                        <ul class="mt-2">
                            <li>Rerata Nilai 1: <span x-text="nilai"></span></li>
                            <li>Jumlah Survey: <span x-text="jumlah"></span></li>
                            <li>Team ID: <span x-text="teamId"></span></li>
                        </ul>
                    </div>

                    <div class="flex justify-end mt-6 space-x-2">
                        <button @click="close()" class="px-4 py-2 text-sm bg-gray-300 rounded hover:bg-gray-400">
                            Batal
                        </button>
                        <button @click="accept()" class="px-4 py-2 text-sm text-white bg-blue-600 rounded hover:bg-blue-700">
                            Terima
                        </button>
                    </div>
                </div>
            </div>



    </x-filament::section>

    <div>
        <p>Mitra: {{ $confirmingMitra['mitra_name'] ?? 'None' }}</p>
        <div>
            <p class="text-sm text-gray-500">Modal Open: {{ $isConfirmModalOpen ? 'YES' : 'NO' }}</p>
        </div>
    </div>
    <script>
        function modalHandler(modalId) {
            return {
                isOpen: false,
                namaMitra: '-',
                nilai: '-',
                jumlah: '-',
                teamId: '-',
        
                init() {
                    // Opsional: auto-init logika tambahan
                },
        
                open(detail = {}) {
                    this.namaMitra = detail.nama ?? '-'
                    this.nilai = detail.nilai ?? '-'
                    this.jumlah = detail.jumlah ?? '-'
                    this.teamId = detail.team_id ?? '-'
                    this.isOpen = true
                },
        
                close() {
                    this.isOpen = false
                },
        
                accept() {
                    // Kirim Livewire event atau apapun logic yang kamu butuh
                    window.livewire.emit('acceptMitraTeladan') 
                    this.close()
                },
        
                checkAndOpen(e) {
                    if (e.detail.id === modalId) {
                        this.open(e.detail)
                    }
                },
        
                checkAndClose(e) {
                    if (e.detail.id === modalId) {
                        this.close()
                    }
                }
            }
        }
        </script>
</x-filament::page>