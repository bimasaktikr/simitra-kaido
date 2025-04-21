<x-filament-panels::page>
    {{-- <livewire:list-top-mitra> --}}
    {{-- <livewire:table-modal> --}}
    <x-filament::page>
        <h1>Top Mitra</h1>
        <ul>
            @foreach($topMitra as $mitra)
                <li>{{ $mitra->mitra_name }} - Rating: {{ $mitra->avg_rating }}</li>
            @endforeach
        </ul>
    </x-filament::page>
</x-filament-panels::page>
