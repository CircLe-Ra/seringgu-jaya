<?php

use function Livewire\Volt\{computed, state, layout, usesPagination, on, mount, updated, title, usesFileUploads};
use App\Models\CitizenAssociation;
use App\Models\NeighborhoodAssociation;
use Masmerise\Toaster\Toaster;
use App\Models\User;
use App\Models\FamilyCard;
use App\Models\FamilyMember;
use App\Models\Religion;
use App\Models\Education;
use App\Models\Employment;
use App\Models\BloodGroup;

layout('layouts.app');
title('Informasi Keluarga');
usesPagination();
usesFileUploads();
state(['id','family_card_number','head_of_family','province','regency','district','sub_district','citizen_association','neighborhood_association','address','postal_code']);
state(['resident_identification_number', 'name', 'gender', 'birth_place', 'birth_date', 'religion_id', 'education_id', 'employment_id', 'blood_group_id','user_id','email','password','password_confirmation']);
state(['position'=> 'familiar']);
state(['letter_type_id','letter_file', 'family_card_file', 'resident_identification_card_file', 'family_member_id']);

mount(function () {
    if(auth()->user()->roles()->get()->first()->name != 'warga'){
        abort(404);
    }

    $family_card = FamilyCard::find(auth()->user()->family_member->family_card_id);
    $this->family_card_number = $family_card->family_card_number;
    $this->head_of_family = $family_card->head_of_family;
    $this->province = $family_card->province->name;
    $this->regency = $family_card->regency->name;
    $this->district = $family_card->district->name;
    $this->sub_district = $family_card->sub_district->name;
    $this->citizen_association = $family_card->citizen->position;
    $this->neighborhood_association = $family_card->neighborhood->position;
    $this->address = $family_card->address;
    $this->postal_code = $family_card->postal_code;
});


$familyMembers = computed(function () {
    return FamilyMember::where('family_card_id', auth()->user()->family_member->family_card_id)->orderBy('position', 'desc')->get();
});

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
                'text' => 'Kartu Keluarga'
            ]
        ]">
    </x-ui.breadcrumbs>
    <div class="grid-cols-1 lg:grid-cols-3 grid gap-2 ">
        <div class="col-span-3 ">
            <x-ui.card class="mt-2 w-full ">
                <x-slot name="header">
                    <div>
                        <h5 class="text-xl font-medium text-gray-900 dark:text-white">Informasi Kartu Keluarga</h5>
                        <p class="text-sm text-gray-600 dark:text-gray-300">Daftar anggota keluarga {{ $this->head_of_family }}</p>
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
                    </div>
                </x-slot>

                <div class="border dark:border-gray-600 rounded-lg p-3 border-gray-200 mx-auto">
                    <h3 class="text-center font-bold text-lg dark:text-blue-100 text-gray-900">No. {{ $this->family_card_number }}</h3>
                </div>
                <div class="border dark:border-gray-600 rounded-lg p-3 border-gray-200 mx-auto">
                    <table class="w-full dark:text-gray-100 text-gray-900" >
                        <tr>
                            <td class="font-bold">Nama Kepala Keluarga</td>
                            <td>: {{ $this->head_of_family }}</td>
                            <td class="font-bold">Desa/Kelurahan</td>
                            <td>: {{ $this->sub_district }}</td>
                        </tr>
                        <tr>
                            <td class="font-bold">Alamat</td>
                            <td>: {{ $this->address }}</td>
                            <td class="font-bold">Kecamatan</td>
                            <td>: {{ $this->district }}</td>
                        </tr>
                        <tr>
                            <td class="font-bold">RT/RW</td>
                            <td>: {{ $this->citizen_association }}/{{ $this->neighborhood_association }}</td>
                            <td class="font-bold">Kabupaten/Kota</td>
                            <td>: {{ $this->regency }}</td>
                        </tr>
                        <tr>
                            <td class="font-bold">Kode Pos</td>
                            <td>: {{ $this->postal_code }}</td>
                            <td class="font-bold">Provinsi</td>
                            <td>: {{ $this->province }}</td>
                        </tr>
                    </table>
                </div>
                <x-ui.table thead="Status, Nama, Nomor Induk Kependudukan, Jenis Kelamin, Tempat Lahir, Tanggal Lahir, Agama, Pendidikan, Jenis Pekerjaan, Golongan Darah, Akun" :action="false">

                    @if($this->familyMembers->count() > 0)
                        @foreach($this->familyMembers as $key => $familyMember)
                            <tr class=" {{ $familyMember->id == auth()->user()->family_member->id ? 'odd:bg-gray-400 odd:dark:bg-gray-400 even:bg-gray-400 even:dark:bg-gray-400 dark:text-gray-900 text-gray-50' : 'odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700' }}">
                                <td class="px-6 py-4 text-nowrap">
                                    {{ $familyMember->position == 'patriarch' ? 'Kepala Keluarga' : 'Anggota Keluarga' }}
                                </td>
                                <td class="px-6 py-4 text-nowrap">
                                    {{ $familyMember->name }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $familyMember->resident_identification_number }}
                                </td>
                                <td class="px-6 py-4 text-nowrap">
                                    {{ $familyMember->gender == 'M' ? 'Laki-Laki' : 'Perempuan' }}
                                </td>
                                <td class="px-6 py-4 ">
                                    {{ $familyMember->birth_place }}
                                </td>
                                <td class="px-6 py-4 text-nowrap">
                                    {{ $familyMember->birth_date }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $familyMember->religion->name }}
                                </td>
                                <td class="px-6 py-4 text-nowrap">
                                    {{ $familyMember->education->name }}
                                </td>
                                <td class="px-6 py-4 text-nowrap">
                                    {{ $familyMember->employment->name }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $familyMember->blood_group->name }}
                                </td>
                                <td class="px-6 py-4 text-nowrap">
                                    {{ $familyMember->user ? 'Aktif' : 'Tidak Aktif' }}
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
            </x-ui.card>
        </div>
    </div>
</div>
