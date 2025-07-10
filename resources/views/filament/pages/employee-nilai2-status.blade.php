<x-filament-panels::page>
    {{-- Filter Form and Action Section --}}
    <div class="mb-6">
        {{ $this->form }}
        <div class="mt-6">
            {{ $this->filterAction }}
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-700 dark:text-gray-200 border border-gray-200 dark:border-gray-700 rounded-lg">
            <thead class="bg-gray-100 dark:bg-gray-800">
                <tr>
                    <th class="px-4 py-2">Employee</th>
                    @foreach($teams as $team)
                        <th class="px-4 py-2">{{ $team->name }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($statusTable as $row)
                    <tr class="border-t border-gray-200 dark:border-gray-700">
                        <td class="px-4 py-2 font-semibold">{{ $row['employee']->user->name ?? '-' }}</td>
                        @foreach($teams as $team)
                            <td class="px-4 py-2 text-center">
                                @if($row['statuses'][$team->id] === 'Sudah')
                                    <span class="inline-block px-2 py-1 text-xs font-bold text-green-700 bg-green-100 rounded dark:bg-green-900 dark:text-green-200">Sudah</span>
                                @elseif($row['statuses'][$team->id] === 'Belum')
                                    <span class="inline-block px-2 py-1 text-xs font-bold text-red-700 bg-red-100 rounded dark:bg-red-900 dark:text-red-200">Belum</span>
                                @else
                                    <span class="inline-block px-2 py-1 text-xs font-bold text-gray-500 bg-gray-100 rounded dark:bg-gray-800 dark:text-gray-400">-</span>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-filament-panels::page>
