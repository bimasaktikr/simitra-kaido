<div>
    <h1> This is LiveWire Component</h1>
    {{ $this -> form }}
    <section class="p-4 m-8 bg-white shadow rounded-xl dark:bg-gray-900">
        <h2 class="mb-4 text-xl font-semibold text-gray-800 dark:text-gray-100">Daftar Mitra Teladan</h2>
    <div class="mt-4 overflow-hidden border border-gray-200 rounded-lg shadow-lg dark:border-gray-700">
        <table class="w-full text-sm text-left divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-100 dark:bg-gray-800">
                <tr>
                    <th class="p-4 font-semibold text-gray-600 dark:text-gray-200">Nama</th>
                    <th class="p-4 font-semibold text-gray-600 dark:text-gray-200">Sobat ID</th>
                    <th class="p-4 font-semibold text-gray-600 dark:text-gray-200">Team</th>
                    <th class="p-4 font-semibold text-gray-600 dark:text-gray-200">Rating</th>
                    <th class="p-4 font-semibold text-gray-600 dark:text-gray-200">Surveys</th>
                    <th class="p-4 font-semibold text-gray-600 dark:text-gray-200">Status</th>
                    <th class="p-4 font-semibold text-gray-600 dark:text-gray-200">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-900">
                @foreach ($this->mergedData as $item)
                    <tr class="transition-all duration-300 ease-in-out">
                        <td class="p-4 border-t border-b border-gray-200 dark:border-gray-700">{{ $item['mitra_name'] }}</td>
                        <td class="p-4 border-t border-b border-gray-200 dark:border-gray-700">{{ $item['sobat_id'] }}</td>
                        <td class="p-4 border-t border-b border-gray-200 dark:border-gray-700">Team {{ $item['team_id'] }}</td>
                        <td class="p-4 border-t border-b border-gray-200 dark:border-gray-700">{{ number_format((float)$item['avg_rating'], 2) }} ★</td>
                        <td class="p-4 border-t border-b border-gray-200 dark:border-gray-700">{{ $item['surveys_count'] }}</td>
                        <td class="p-4 border-t border-b border-gray-200 dark:border-gray-700">
                            @if ($item['status'] === 'final')
                                <span class="font-semibold text-green-500 dark:text-green-400">Final</span>
                            @else
                                <span class="text-yellow-500 dark:text-yellow-400">Belum Final</span>
                            @endif
                        </td>
                        <td class="p-4 text-center border-t border-b border-gray-200 dark:border-gray-700">
                            @if ($item['status'] !== 'final')

                                <button
                                    wire:click='openModal(@json($item))'
                                    class="px-4 py-2 text-sm font-medium text-white transition duration-150 rounded-md shadow bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
                                >
                                    Finalisasi
                                </button>

                            @else
                                <span class="text-green-500 dark:text-green-400">✔</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>
<div x-data="{ open: @entangle('isOpen') }" x-show="open" x-cloak>
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-500 bg-opacity-75">
        <div class="p-6 bg-white rounded-lg shadow-lg w-96">
            <div class="space-y-4">
                <div class="text-center">
                    <h3 class="text-lg font-semibold">Finalisasi Mitra Teladan</h3>
                    <p class="text-sm text-gray-700">
                        Kamu akan memfinalisasi mitra berikut sebagai Mitra Teladan:
                    </p>
                </div>
                @if ($confirmingMitra)
                    <div class="space-y-2 text-sm text-gray-700">
                        <p><strong>Nama:</strong> {{ $confirmingMitra->mitra_name }}</p>
                        <p><strong>Sobat ID:</strong> {{ $confirmingMitra->sobat_id }}</p>
                        <p><strong>Rating:</strong> {{ number_format($confirmingMitra->avg_rating, 2) }} ★</p>
                        <p><strong>Survey:</strong> {{ $confirmingMitra->surveys_count }}</p>
                    </div>
                @endif
                <div class="flex justify-end space-x-2">
                    <button wire:click="closeModal" class="px-4 py-2 text-white bg-gray-300 rounded">
                        Batal
                    </button>
                    <button wire:click="acceptMitraTeladan" class="px-4 py-2 text-white bg-green-500 rounded">
                        Ya, Finalisasi
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
