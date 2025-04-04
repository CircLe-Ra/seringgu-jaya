<?php

use function Livewire\Volt\{computed, state, layout, usesPagination, on, mount, updated, title};
use App\Models\Letter;
use Illuminate\Support\Facades\Validator;
use Masmerise\Toaster\Toaster;

layout('layouts.app');
title('Laporan Pelayanan');
usesPagination();
state(['show' => 5, 'status' => '', 'start_date' => '', 'end_date' => ''])->url();


$reports = computed(function () {
    return Letter::where('status','!=','draft')
        ->where('status', 'like', '%' . $this->status . '%')
        ->paginate($this->show, pageName: 'report-page');
});

$filter = function (){
    $validator = Validator::make([
        'start_date' => $this->start_date,
        'end_date' => $this->end_date
    ], [
        'start_date' => 'date',
        'end_date' => 'date|after_or_equal:start_date',
    ]);

    if($validator->fails()){
        foreach ($validator->errors()->all() as $error){
            Toaster::error($error);
        }
        return;
    }

    $start_date = $this->start_date . ' 00:00:00';
    $end_date = $this->end_date . ' 23:59:59';

    $this->reports = Letter::where('status','!=','draft')
        ->where('status', 'like', '%' . $this->status . '%')
        ->whereBetween('created_at', [$start_date, $end_date])
        ->paginate($this->show, pageName: 'report-page');
}

?>

<div>
    <x-ui.breadcrumbs :crumbs="[
            ['href' => '/', 'text' => 'Dashboard'],
            [
                'text' => 'Laporan'
            ],[
                'text' => 'Pelayanan'
            ]
        ]">
        <x-slot name="actions">
            <x-ui.input-select id="show" name="show" wire:model.live="show" size="xs" class="w-full my-[2px]">
                <option value="">Semua</option>
                <option value="5">5</option>
                <option value="10">10</option>
                <option value="20">20</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </x-ui.input-select>
        </x-slot>
    </x-ui.breadcrumbs>
    <div class="grid-cols-1 lg:grid-cols-3 grid gap-2 ">
        <div class="col-span-3 ">
            <x-ui.card class="mt-2 w-full " id="print-area">
                <x-slot name="header">
                    <div>
                        <h5 class="text-xl font-medium text-gray-900 dark:text-white">Laporan Pelayanan</h5>
                        <p class="text-sm text-gray-600 dark:text-gray-300">Lihat atau cetak laporan pelayanan</p>
                    </div>
                </x-slot>
                <x-slot name="sideHeader">
                    <div class="flex items-center gap-2 print:hidden">
                        <x-ui.input-select id="status" name="status" wire:model.live="status" size="xs" class="w-full p-2">
                            <option value="">Semua Status</option>
                            <option value="apply">Diajukan</option>
                            <option value="process">Diproses</option>
                            <option value="reply">Selesai</option>
                        </x-ui.input-select>
                        <x-ui.input type="date" size="small" wire:model="start_date" id="start_date" />
                        <span class="text-gray-600 dark:text-gray-300 text-xs">s/d</span>
                        <x-ui.input type="date" size="small" wire:model="end_date" id="end_date" />
                        <x-ui.button color="blue" size="sm" wire:click="filter">
                            Tampilkan
                            <svg class="w-4 h-4 rotate-45 ms-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 7v10m0 0H7m10 0L7 7"/></svg>
                        </x-ui.button>
                        <x-ui.button color="blue" size="sm" wire:click="dispatch('print')">
                            Cetak
                            <svg class="w-4 h-4 -rotate-90 ms-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 7v10m0 0H7m10 0L7 7"/></svg>
                        </x-ui.button>
                    </div>
                </x-slot>
                <x-ui.table thead="#, Nomor Surat, Pemohon, RT/RW, Pengajuan, Keterangan, Status, Tanggal Dibuat" :action="false">
                    @if($this->reports->count() > 0)
                        @foreach($this->reports as $key => $report)
                            <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                                <td class="px-6 py-4">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $report->letter_number ?? '' }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $report->family_member->name ?? '' }}
                                </td>
                                <td class="px-6 py-4 text-nowrap">
                                    {{ $report->neighborhood->position ?? '' }}/{{ $report->neighborhood->citizen->position ?? '' }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $report->letter_type->name ?? '' }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $report->description ?? '' }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $report->status == 'reply' ? 'Selesai' : ($report->status == 'apply' ? 'Diajukan' : 'Diproses') ?? '' }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ \Carbon\Carbon::parse($report->created_at)->locale('id')->isoFormat('D MMMM Y') ?? '' }}
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                            <td class="px-6 py-4 text-center" colspan="11">
                                Tidak ada data
                            </td>
                        </tr>
                    @endif
                </x-ui.table>

                {{ $this->reports->links('livewire.pagination') }}
            </x-ui.card>
        </div>
    </div>
    @pushonce('scripts-bottom')
        <script>
            document.addEventListener('livewire:navigated', () => {
                Livewire.on('print', () => {
                    let printContents = document.getElementById('print-area').innerHTML;
                    document.body.innerHTML = printContents;
                    window.print();
                    window.location.reload();
                });
            }, { once: true });
        </script>
    @endpushonce
</div>
