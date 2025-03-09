@props(['thead', 'action' => true, 'theadCol' => null])
@php
    $thead = \Str::of($thead)->explode(',');
@endphp

<div class="relative overflow-x-auto border dark:border-gray-700 sm:rounded-lg scrollbar-thumb-gray-700 scrollbar-track-white scrollbar-thin dark:scrollbar-track-gray-800 dark:scrollbar-thumb-gray-500 print:dark:border-gray-200">
    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 ">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
        <tr class="text-neutral-500 dark:text-neutral-50 print:dark:text-black">
            @foreach ($thead as $key => $th)
                @php
                    $mergeCell = Str::of($th)->explode(':');
                    $count = $mergeCell->count();
                @endphp
                <th scope="col" class="px-6 py-3 " colspan="{{ $count ?? '' }}">
                    {{ $th }}
                </th>
            @endforeach
            @if ($action ?? false)
                <th scope="col" class="px-6 py-3">Aksi</th>
            @endif
        </tr>
        </thead>
        <tbody>
            {{ $slot }}
        </tbody>
    </table>
</div>
