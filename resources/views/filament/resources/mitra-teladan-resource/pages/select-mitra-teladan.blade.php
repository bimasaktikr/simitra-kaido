<x-filament::page>
    {{ $this->form }}


    {{-- <x-filament::section heading="Top Mitra per Team">
        <x-filament::grid :columns="5">
            @foreach($this->topMitras as $mitra)
                <x-filament::card>
                    <div class="text-lg font-bold text-primary">{{ $mitra['team_name'] }}</div>
                    <div class="text-sm">{{ $mitra['mitra_name'] }}</div>
                    <div class="text-gray-500">Rerata: {{ number_format($mitra['average_rerata'], 2) }}</div>
                    <div class="text-gray-500">Survei: {{ $mitra['distinct_survey_count'] }}</div>
                </x-filament::card>
            @endforeach
        </x-filament::grid>
    </x-filament::section> --}}


    <x-filament::section
        heading="Top Mitra Performance"
        description="Quarterly ranking based on survey ratings and participation"
        class="bg-white shadow-sm rounded-xl dark:bg-gray-800"
    >
        <div class="overflow-hidden border border-gray-200 rounded-lg dark:border-gray-700">
            <table class="w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700/20">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-400">
                            Nama
                        </th>
                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-400">
                            Sobat ID
                        </th>
                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-400">
                            Team
                        </th>
                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-400">
                            Rating
                        </th>
                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-400">
                            Surveys
                        </th>
                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-400">
                            Status
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                    @foreach ($this->tableData as $item)
                        <tr class="transition-colors">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $item['mitra_name'] }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap dark:text-gray-400">
                                {{ $item['sobat_id'] }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap dark:text-gray-400">
                                Team {{ $item['team_id'] }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap dark:text-gray-400">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-200">
                                    {{ number_format((float)$item['avg_rating'], 2) }} ★
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap dark:text-gray-400">
                                {{ $item['surveys_count'] }}
                            </td>
                            <td class="px-6 py-4 text-sm whitespace-nowrap">
                                <button
                                    wire:click="acceptMitra('{{ $item['mitra_id'] }}', {{ $item['team_id'] }})"
                                    class="px-3 py-1 text-xs font-medium text-white bg-blue-500 rounded hover:bg-blue-600"
                                >
                                    Accept
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if (count($this->tableData) === 0)
            <div class="p-4 text-center text-gray-500 dark:text-gray-400">
                No data available for the selected quarter.
            </div>
        @endif


    </x-filament::section>

    <livewire:select-mitra-teladan />

</x-filament::page>
