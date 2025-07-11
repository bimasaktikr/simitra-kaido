@php
    use App\Models\MitraTeladan;
@endphp

<x-filament::page>
    <form wire:submit.prevent="submit">
        <div class="flex gap-4 mb-6">
            {{ $this->form }}
            <button type="submit" class="filament-button">showReport</button>
        </div>
    </form>

    {{-- Report Table Placeholder --}}
    <div>
        @foreach ($reportData as $group)
            <h2 class="mt-8 mb-2 text-lg font-bold">{{ $group['mitraName'] }} - {{ $group['teamName'] }}</h2>
            <div class="overflow-x-auto">
                <table class="mb-6 min-w-full border border-gray-300">
                    <thead>
                        <tr>
                            <th class="px-2 py-1 border">employeeName</th>
                            <th class="px-2 py-1 border">aspek1</th>
                            <th class="px-2 py-1 border">aspek2</th>
                            <th class="px-2 py-1 border">aspek3</th>
                            <th class="px-2 py-1 border">aspek4</th>
                            <th class="px-2 py-1 border">aspek5</th>
                            <th class="px-2 py-1 border">aspek6</th>
                            <th class="px-2 py-1 border">aspek7</th>
                            <th class="px-2 py-1 border">aspek8</th>
                            <th class="px-2 py-1 border">aspek9</th>
                            <th class="px-2 py-1 border">aspek10</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($group['nilai2List'] as $row)
                            <tr>
                                <td class="px-2 py-1 border">{{ $row['employeeName'] }}</td>
                                <td class="px-2 py-1 border">{{ $row['aspek1'] }}</td>
                                <td class="px-2 py-1 border">{{ $row['aspek2'] }}</td>
                                <td class="px-2 py-1 border">{{ $row['aspek3'] }}</td>
                                <td class="px-2 py-1 border">{{ $row['aspek4'] }}</td>
                                <td class="px-2 py-1 border">{{ $row['aspek5'] }}</td>
                                <td class="px-2 py-1 border">{{ $row['aspek6'] }}</td>
                                <td class="px-2 py-1 border">{{ $row['aspek7'] }}</td>
                                <td class="px-2 py-1 border">{{ $row['aspek8'] }}</td>
                                <td class="px-2 py-1 border">{{ $row['aspek9'] }}</td>
                                <td class="px-2 py-1 border">{{ $row['aspek10'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-2 py-1 text-center border" colspan="11">noData</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @endforeach
    </div>
</x-filament::page>
