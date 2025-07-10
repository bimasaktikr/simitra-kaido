<div
    x-data="{ show: @entangle('showModal') }"
    x-show="show"
    style="display: none;"
    class="flex fixed inset-0 z-50 justify-center items-center bg-black bg-opacity-50 dark:bg-gray-900 dark:bg-opacity-80"
>
    <div class="relative p-6 mx-auto w-full max-w-xl bg-white rounded-lg shadow-lg dark:bg-gray-800 dark:border dark:border-gray-700">
        <button @click="show = false; $wire.set('showModal', false)" class="absolute top-2 right-2 text-2xl text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300">&times;</button>
        <h2 class="mb-4 text-lg font-bold text-gray-900 dark:text-gray-100">Input Nilai 2</h2>
        <form wire:submit.prevent="saveNilai2">
            <div class="grid grid-cols-2 gap-4">
                @for($i = 1; $i <= 10; $i++)
                    <div>
                        <label for="aspek{{ $i }}" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                            Aspek {{ $i }}: {{ $aspekDescriptions[$i] ?? '' }}
                        </label>
                        <input type="number" min="1" max="10" wire:model.defer="aspek{{ $i }}" id="aspek{{ $i }}" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-100" />
                        @error('aspek' . $i)
                            <span class="text-xs text-red-600 dark:text-red-400">{{ $message }}</span>
                        @enderror
                    </div>
                @endfor
            </div>
            {{-- <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Rerata</label>
                <input type="text" readonly value="{{
                    number_format((
                        ($aspek1 ?? 0) + ($aspek2 ?? 0) + ($aspek3 ?? 0) + ($aspek4 ?? 0) + ($aspek5 ?? 0) +
                        ($aspek6 ?? 0) + ($aspek7 ?? 0) + ($aspek8 ?? 0) + ($aspek9 ?? 0) + ($aspek10 ?? 0)
                    ) / 10, 2) }}" class="block mt-1 w-full bg-gray-100 rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-700 dark:text-gray-100" />
            </div> --}}
            <div class="flex items-center mt-10">
                <input type="checkbox" id="is_final" wire:model.defer="is_final" class="text-green-600 rounded border-gray-300 shadow-sm focus:ring focus:ring-green-200 focus:ring-opacity-50 dark:bg-gray-900 dark:border-gray-700" />
                <label for="is_final" class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-200">Final</label>
            </div>
            <div class="flex gap-2 justify-end mt-6">
                <button type="button" @click="show = false; $wire.set('showModal', false)" class="btn btn-secondary dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-200">Batal</button>
                <button type="submit" class="text-white bg-green-600 btn btn-primary hover:bg-green-700 dark:bg-green-700 dark:hover:bg-green-800">Simpan Nilai</button>
            </div>
        </form>
    </div>
</div>
