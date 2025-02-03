<?php

use function Livewire\Volt\{computed, state, layout, usesPagination, on, mount, updated, title};
use App\Models\CitizenAssociation;
use App\Models\NeighborhoodAssociation;
use Masmerise\Toaster\Toaster;
use App\Models\User;
use App\Models\Province;
use App\Models\Regency;
use App\Models\District;
use App\Models\SubDistrict;
use App\Models\FamilyCard;

layout('layouts.app');
title('Penduduk');
usesPagination();
state(['show' => 5, 'search' => ''])->url();
state(['id','family_card_number','head_of_family','province_id','regency_id','district_id','sub_district_id','citizen_association_id','neighborhood_association_id','address','postal_code']);
state(['cities' => [], 'districts' => [], 'sub_districts' => []]);

mount(function () {
    if(auth()->user()->roles()->get()->first()->name != 'rt'){
        abort(404);
    }

    $this->citizen_association_id = auth()->user()->neighborhoodAssociation->citizen->id;
    $this->neighborhood_association_id = auth()->user()->neighborhoodAssociation->id;
});

$provinces = computed(function () {
    return Province::all();
});

$citizen_associations = computed(function () {
    return CitizenAssociation::all();
});

$neighborhood_associations = computed(function () {
    return NeighborhoodAssociation::all();
});

updated(['province_id' => function () {
    $this->cities = Regency::where('province_id', $this->province_id)->get();
    $this->districts = [];
    $this->sub_districts = [];
    $this->regency_id = null;
    $this->district_id = null;
    $this->sub_district_id = null;
}, 'regency_id' => function () {
    $this->districts = District::where('regency_id', $this->regency_id)->get();
    $this->sub_districts = [];
    $this->district_id = null;
    $this->sub_district_id = null;
}, 'district_id' => function () {
    $this->sub_districts = SubDistrict::where('district_id', $this->district_id)->get();
    $this->sub_district_id = null;
}]);

on(['close-modal-reset' => function ($wireModels) {
    $this->reset('id');
    $this->reset($wireModels);
    $this->resetErrorBag($wireModels);
    $this->cities = [];
    $this->districts = [];
    $this->sub_districts = [];
},'open-modal' => function () {
    $this->citizen_association_id = auth()->user()->neighborhoodAssociation->citizen->id;
    $this->neighborhood_association_id = auth()->user()->neighborhoodAssociation->id;
}]);

$familyCards = computed(function () {
    return FamilyCard::where('neighborhood_association_id', auth()->user()->neighborhoodAssociation->id)
        ->where(function ($query) {
            $query->where('family_card_number', 'like', '%' . $this->search . '%')
                ->orWhere('head_of_family', 'like', '%' . $this->search . '%');
        })->paginate($this->show, pageName: 'family-card-page');
});

$save = function () {
    $this->validate([
        'family_card_number' => ['required', 'string', 'min:16', 'max:16', 'unique:family_cards,family_card_number' . ($this->id ? ',' . $this->id : '')],
        'head_of_family' => ['required', 'string'],
        'province_id' => ['required'],
        'regency_id' => ['required'],
        'district_id' => ['required'],
        'sub_district_id' => ['required'],
        'citizen_association_id' => ['required'],
        'neighborhood_association_id' => ['required'],
        'address' => ['required', 'string'],
        'postal_code' => ['required', 'numeric'],
    ]);
    try {
        FamilyCard::updateOrCreate([
            'id' => $this->id
        ],[
            'family_card_number' => $this->family_card_number,
            'head_of_family' => $this->head_of_family,
            'province_id' => $this->province_id,
            'regency_id' => $this->regency_id,
            'district_id' => $this->district_id,
            'sub_district_id' => $this->sub_district_id,
            'citizen_association_id' => $this->citizen_association_id,
            'neighborhood_association_id' => $this->neighborhood_association_id,
            'address' => $this->address,
            'postal_code' => $this->postal_code,
        ]);
        unset($this->familyCards);
        $this->dispatch('close-modal', id: 'family-card-modal');
        Toaster::success('Data berhasil disimpan!');
    } catch (Exception $e) {
        $this->dispatch('close-modal', id: 'family-card-modal');
        Toaster::error($e->getMessage());
    }
};

$edit = function ($id) {
    $familyCard = FamilyCard::find($id);
    $this->id = $familyCard->id;
    $this->family_card_number = $familyCard->family_card_number;
    $this->head_of_family = $familyCard->head_of_family;
    $this->province_id = $familyCard->province_id;
    $this->province_id ? $this->cities = Regency::where('province_id', $this->province_id)->get() : $this->cities = [];
    $this->regency_id = $familyCard->regency_id;
    $this->regency_id ? $this->districts = District::where('regency_id', $this->regency_id)->get() : $this->districts = [];
    $this->district_id = $familyCard->district_id;
    $this->district_id ? $this->sub_districts = SubDistrict::where('district_id', $this->district_id)->get() : $this->sub_districts = [];
    $this->sub_district_id = $familyCard->sub_district_id;
    $this->citizen_association_id = $familyCard->citizen_association_id;
    $this->citizen_association_id ? $this->neighborhood_associations = NeighborhoodAssociation::where('citizen_association_id', $this->citizen_association_id)->get() : $this->neighborhood_associations = [];
    $this->neighborhood_association_id = $familyCard->neighborhood_association_id;
    $this->address = $familyCard->address;
    $this->postal_code = $familyCard->postal_code;

    $this->dispatch('open-modal', id: 'family-card-modal');
};

$destroy = function ($id) {
    try {
        $familyCard = FamilyCard::find($id);
        $familyCard->delete();
        unset($this->familyCards);
        Toaster::success('Data berhasil dihapus!');
    } catch (Exception $e) {
        Toaster::error($e->getMessage());
    }
}

?>

<div>
    <x-ui.breadcrumbs :crumbs="[
            [
                'href' => route('dashboard'),
                'text' => 'Dashboard'
            ],
            [
                'text' => 'Warga'
            ],[
                'text' => 'Kartu Keluarga',
                'href' => route('master-data.citizen-association')
            ]
        ]">
        <x-slot name="actions">
            <x-ui.input-icon id="search" wire:model.live="search" placeholder="Cari..." size="small">
                <x-slot name="icon">
                    <svg class="text-gray-500 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" width="24"
                         height="24" viewBox="0 0 24 24">
                        <g fill="none">
                            <path fill="currentColor" fill-opacity="0.25" fill-rule="evenodd"
                                  d="M12 19a7 7 0 1 0 0-14a7 7 0 0 0 0 14M10.087 7.38A5 5 0 0 1 12 7a.5.5 0 0 0 0-1a6 6 0 0 0-6 6a.5.5 0 0 0 1 0a5 5 0 0 1 3.087-4.62"
                                  clip-rule="evenodd"/>
                            <path stroke="currentColor" stroke-linecap="round" d="M20.5 20.5L17 17"/>
                            <circle cx="11" cy="11" r="8.5" stroke="currentColor"/>
                        </g>
                    </svg>
                </x-slot>
            </x-ui.input-icon>
        </x-slot>
    </x-ui.breadcrumbs>
    <x-ui.modal id="family-card-modal" size="lg">
        <x-slot name="header">
            <h5 class="text-xl font-medium text-gray-900 dark:text-white">Kartu Keluarga (KK)</h5>
        </x-slot>
        <x-slot name="content">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mx-auto">
                <x-ui.input type="text" label="Nomor Kartu Keluarga" wire:model="family_card_number" id="family_card_number"/>
                <x-ui.input type="text" label="Kepala Keluarga" wire:model="head_of_family" id="head_of_family"/>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-2 mb-2">
                <x-ui.input-select label="Provinsi" id="province_id" name="province_id"
                                     server :data="$this->provinces" required
                                     wire:model.live="province_id"/>
                <x-ui.input-select :selected="$this->regency_id" label="Kota" id="regency_id" name="regency_id" server
                                     required wire:model.live="regency_id"
                                     :data="$this->cities"/>
                <x-ui.input-select :selected="$this->district_id" label="Kecamatan" id="district_id" name="district_id" required
                                     wire:model.live="district_id" server
                                     :data="$this->districts"/>
                <x-ui.input-select :selected="$this->sub_district_id" label="Kelurahan" id="sub_district_id" name="sub_district_id"
                                     required wire:model.live="sub_district_id"
                                     server :data="$this->sub_districts"/>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-2 mb-2">
                <x-ui.input-select :selected="$this->citizen_association_id" label="RW" id="citizen_association_id" name="citizen_association_id"
                                   required wire:model="citizen_association_id"
                                   server :data="$this->citizen_associations" display_name="position" :disabled="true" />
                <x-ui.input-select :selected="$this->neighborhood_association_id" label="RT" id="neighborhood_association_id" name="neighborhood_association_id"
                                   required wire:model="neighborhood_association_id"
                                   server :data="$this->neighborhood_associations" display_name="position" :disabled="true" />
                <x-ui.input type="text" label="Alamat" wire:model="address" id="address"/>
                <x-ui.input type="number" label="Kode Pos" wire:model="postal_code" id="postal_code"/>
            </div>

        </x-slot>
        <x-slot name="footer">
            <x-ui.button size="sm" reset color="light" class="mr-2" wire:click="$dispatch('close-modal', { id: 'family-card-modal' })">
                Batal
            </x-ui.button>
            <x-ui.button size="sm" title="Simpan" submit color="blue" wire:loading.attr="disabled" wire:loading.class="cursor-not-allowed" wire:target="save" wire:click="save" />
        </x-slot>
    </x-ui.modal>

    <div class="grid-cols-1 lg:grid-cols-3 grid gap-2 ">
        <div class="col-span-3 ">
            <x-ui.card class="mt-2 w-full ">
                <x-slot name="header">
                    <div>
                        <h5 class="text-xl font-medium text-gray-900 dark:text-white">Kartu Keluarga (KK)</h5>
                        <p class="text-sm text-gray-600 dark:text-gray-300">Daftar kartu keluarga yang terdaftar di {{ Auth::user()->neighborhoodAssociation->position ?? '' }}</p>
                    </div>
                </x-slot>
                <x-slot name="sideHeader">
                    <div class="flex items-center gap-2">
                        <x-ui.input-select id="show" name="show" wire:model.live="show" size="xs" class="w-full">
                            <option value="">Semua</option>
                            <option value="5">5</option>
                            <option value="10">10</option>
                            <option value="20">20</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </x-ui.input-select>
                        <x-ui.button wire:click="$dispatch('open-modal', { id :'family-card-modal'})" size="xs" color="blue">
                            <span class="iconify duo-icons--add-circle w-4 h-4 me-1"></span>
                            Tambah
                        </x-ui.button>
                    </div>
                </x-slot>
                <x-ui.table thead="#, Nomor KK, Kepala Keluarga, Alamat, RT/RW, Desa/Kelurahan, Kecamatan, Kabupaten/Kota, Provinsi, Kode Pos" :action="true">
                    @if($this->familyCards->count() > 0)
                        @foreach($this->familyCards as $key => $familyCard)
                            <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                                <td class="px-6 py-4">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $familyCard->family_card_number }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $familyCard->head_of_family }}
                                </td>
                                <td class="px-6 py-4 text-nowrap">
                                    {{ $familyCard->address }}
                                </td>
                                <td class="px-6 py-4 text-nowrap">
                                    {{ $familyCard->citizen->position }}/{{ $familyCard->neighborhood->position }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $familyCard->sub_district->name }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $familyCard->district->name }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $familyCard->regency->name }}
                                </td>
                                <td class="px-6 py-4 text-nowrap">
                                    {{ $familyCard->province->name }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $familyCard->postal_code }}
                                </td>
                                <td class="px-6 py-4 ">
                                    <x-ui.button wire:navigate class="w-24 mb-1" tag="link" size="xs" color="yellow" href="{{ route('neighborhood-association.inhabitant-detail', ['id' => $familyCard->id]) }}">
                                        <span class="iconify carbon--user w-3 h-3 me-1"></span>
                                        Anggota
                                    </x-ui.button>
                                    <x-ui.button class="w-24 mb-1" size="xs" color="blue" wire:click="edit({{ $familyCard->id }})" wire:loading.attr="disabled" wire:loading.class="cursor-not-allowed" wire:target="edit({{ $familyCard->id }})">
                                        <span class="iconify carbon--edit w-3 h-3 me-1"></span>
                                        Ubah
                                    </x-ui.button>
                                    <x-ui.button class="w-24 mb-1" size="xs" color="red" wire:click="destroy({{ $familyCard->id }})"
                                                 wire:confirm="Anda yakin ingin menghapus data ini?">
                                        <span class="iconify carbon--delete w-3 h-3 me-1"></span>
                                        Hapus
                                    </x-ui.button>
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

                {{ $this->familyCards->links('livewire.pagination') }}
            </x-ui.card>
        </div>
    </div>
</div>
