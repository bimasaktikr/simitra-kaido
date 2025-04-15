<x-filament::page>
    {{-- Survey Detail Section --}}
    <x-filament::section>
        <x-filament::grid
            default="1"
            md="2"
            lg="2"
            class="gap-6"
        >
            <x-filament::card>
                <div class="space-y-1">
                    <h2 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                        {{ $record->name }}
                    </h2>
                    <p class="text-sm text-gray-500">Code: <span class="font-medium text-gray-700">{{ $record->code }}</span></p>
                    <p class="text-sm text-gray-500">Team: <span class="font-medium">{{ $record->team->name }}</span></p>
                    <p class="text-sm text-gray-500">Payment Type: <span class="font-medium">{{ ucfirst($record->payment->payment_type) }}</span></p>
                    <p class="text-sm text-gray-500">Status:
                        <span class="inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-xs font-semibold
                            {{ match($record->status) {
                                'done' => 'bg-green-100 text-green-700',
                                'in progress' => 'bg-yellow-100 text-yellow-800',
                                'not started' => 'bg-gray-100 text-gray-600',
                                default => 'bg-gray-100 text-gray-600'
                            } }}">
                            {{ ucfirst($record->status) }}
                        </span>
                    </p>
                    <p class="text-sm text-gray-500">Date:
                        <span class="font-medium">
                            {{ $record->start_date->format('d M Y') }} - {{ $record->end_date->format('d M Y') }}
                        </span>
                    </p>
                    <p class="text-sm text-gray-500">Rate:
                        <span class="font-medium text-blue-700">
                            Rp{{ number_format($record->rate) }}
                        </span>
                    </p>
                    <p class="text-sm text-gray-500">Penilaian:
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold
                            {{ $record->is_scored ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $record->is_scored ? 'Sudah' : 'Belum' }}
                        </span>
                    </p>
                </div>
            </x-filament::card>
        </x-filament::grid>
    </x-filament::section>

    {{-- Mitra Table Section --}}
    <x-filament::section
        heading="Assigned Mitras"
        description="List of mitras assigned to this survey along with their target, payment rate, and scores."
    >
        {{ $this->table }}
    </x-filament::section>
</x-filament::page>
