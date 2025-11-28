<div class="overflow-x-auto">
    @php
        // Get recommendations from the field state
        $recommendations = $getState() ?? [];
    @endphp

    @if(empty($recommendations))
        <div class="text-center py-8 text-gray-500">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p class="mt-2">No recommendations loaded yet</p>
            <p class="text-sm">Select a Master Survey to see ML recommendations</p>
        </div>
    @else
        <div class="rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
            <table class="w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                        <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap">
                            Rank
                        </th>
                        <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap">
                            ML Score
                        </th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Nama Mitra
                        </th>
                        <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap">
                            Rating Mitra
                        </th>
                        <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap">
                            Avg Rating Survey
                        </th>
                        <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap">
                            Jumlah Survey
                        </th>
                        <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap">
                            Survey Type
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($recommendations as $index => $rec)
                        <tr>
                            <td class="px-4 py-3 whitespace-nowrap text-center">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full 
                                    {{ $index < 3 ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">
                                    {{ $index + 1 }}
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-center">
                                <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                    {{ number_format(($rec['final_rank_score'] ?? 0) * 100, 1) }}%
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2 dark:bg-gray-700 mt-1">
                                    <div class="h-2 rounded-full transition-all duration-300
                                        {{ ($rec['final_rank_score'] ?? 0) >= 0.8 ? 'bg-green-600' : (($rec['final_rank_score'] ?? 0) >= 0.6 ? 'bg-yellow-500' : 'bg-blue-500') }}" 
                                        style="width: {{ ($rec['final_rank_score'] ?? 0) * 100 }}%">
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $rec['mitra_name'] ?? 'N/A' }}</div>
                                <div class="text-xs text-gray-500">ID: {{ $rec['mitra_id'] }}</div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ ($rec['optimized_score'] ?? 0) >= 0.9 ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : (($rec['optimized_score'] ?? 0) >= 0.7 ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300') }}">
                                    {{ number_format(($rec['optimized_score'] ?? 0) * 100, 1) }}%
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-center text-sm text-gray-900 dark:text-gray-100">
                                {{ number_format($rec['survey_score'] ?? 0, 2) }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">
                                    {{ $rec['jumlah_survey'] ?? 0 }}
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                    {{ $rec['survey_type'] ?? 'N/A' }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
