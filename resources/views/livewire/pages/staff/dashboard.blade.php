<?php

use function Livewire\Volt\{state, layout, title, mount};
use App\Models\CitizenAssociation;
use App\Models\NeighborhoodAssociation;
use App\Models\FamilyCard;
use App\Models\FamilyMember;
use App\Models\Religion;
use App\Models\Employment;
use Carbon\Carbon;

layout('layouts.app');
title('Dashboard');
state(['citizen_association', 'neighborhood_association','family_card', 'people', 'religion', 'employment']);

mount(function () {
    $religion = Religion::all();
    $this->religion = $religion->map(function ($religion) {
        return [
            'name' => $religion->name,
            'data' => $religion->family_members->count()
        ];
    })->reduce(function ($carry, $item) {
        $carry['label'][] = $item['name'];
        $carry['data'][] = $item['data'];
        return $carry;
    }, ['label' => [], 'data' => []]);

    $employment = Employment::all();
    $this->employment = $employment->map(function ($employment) {
        $neighborhood_association = NeighborhoodAssociation::find($employment->neighborhood_association_id);
        return [
            'name' => $employment->name,
            'data' => $employment->family_members->count()
        ];
    })->reduce(function ($carry, $item) {
        $carry['label'][] = $item['name'];
        $carry['data'][] = $item['data'];
        return $carry;
    }, ['label' => [], 'data' => []]);


    $this->citizen_association = CitizenAssociation::count();
    $this->neighborhood_association = NeighborhoodAssociation::count();
    $this->family_card = FamilyCard::count();
    $this->people = FamilyMember::count();
});

?>

<div>
    <x-ui.breadcrumbs :crumbs="[
                ['href' => route('dashboard'), 'text' => 'Dashboard']
            ]">
        <x-slot name="actions">
            <h1 class="text-xl py-[3.5px] font-medium text-gray-900 dark:text-white">Halo, {{ auth()->user()->name }} - {{ Carbon::parse(date('Y-m-d'))->locale('id')->isoFormat('D MMMM Y') }}</h1>
        </x-slot>
    </x-ui.breadcrumbs>
    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-4 gap-2 mt-2">
        <div class="h-64 col-span-1 p-6 border text-gray-600 dark:text-white border-gray-200 dark:bg-gray-700 dark:border-gray-800 bg-white rounded-lg">
            <span class="items-start font-bold text-xl">Total Rukur Warga</span>
            <h1 class="items-end font-bold text-7xl text-center my-8">{{ $this->citizen_association }}</h1>
            <h1 class="items-end font-bold text-2xl text-center">RW</h1>
        </div>
        <div class="h-64 col-span-1 p-6 border text-gray-600 dark:text-white border-gray-200 dark:bg-gray-700 dark:border-gray-800 bg-white rounded-lg">
            <span class="items-start font-bold text-xl">Total Rukun Tetangga</span>
            <h1 class="items-end font-bold text-7xl text-center my-8">{{ $this->neighborhood_association }}</h1>
            <h1 class="items-end font-bold text-2xl text-center">RT</h1>
        </div>
        <div class="h-64 col-span-1 p-6 border text-gray-600 dark:text-white border-gray-200 dark:bg-gray-700 dark:border-gray-800 bg-white rounded-lg">
            <span class="items-start font-bold text-xl">Total Kepala Keluarga</span>
            <h1 class="items-end font-bold text-7xl text-center my-8">{{ $this->family_card }}</h1>
            <h1 class="items-end font-bold text-2xl text-center">KK</h1>
        </div>
        <div class="h-64 col-span-1 p-6 border text-gray-600 dark:text-white border-gray-200 dark:bg-gray-700 dark:border-gray-800 bg-white rounded-lg">
            <span class="items-start font-bold text-xl">Total Jiwa</span>
            <h1 class="items-end font-bold text-7xl text-center my-8">{{ $this->people }}</h1>
            <h1 class="items-end font-bold text-2xl text-center">Orang</h1>
        </div>
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-4 gap-2 mt-2">
        <div class="col-span-1 xl:col-span-3 lg:col-span-2 p-6 border text-gray-600 dark:text-white border-gray-200 dark:bg-gray-700 dark:border-gray-800 bg-white rounded-lg">
            <div id="emplymentChart"></div>
        </div>
        <div class="col-span-1 p-6 border text-gray-600 dark:text-white border-gray-200 dark:bg-gray-700 dark:border-gray-800 bg-white rounded-lg">
            <div id="religionChart"></div>
        </div>
    </div>
</div>
@pushonce('scripts-bottom')
    <script>
        document.addEventListener('livewire:navigated', () => {
            var emplymentChartOption = {
                series: [{
                    name: 'Marine Sprite',
                    data: [44, 55, 41, 37, 22, 43, 21]
                }, {
                    name: 'Striking Calf',
                    data: [53, 32, 33, 52, 13, 43, 32]
                }, {
                    name: 'Tank Picture',
                    data: [12, 17, 11, 9, 15, 11, 20]
                }, {
                    name: 'Bucket Slope',
                    data: [9, 7, 5, 8, 6, 9, 4]
                }, {
                    name: 'Reborn Kid',
                    data: [25, 12, 19, 32, 25, 24, 10]
                }],
                chart: {
                    type: 'bar',
                    height: 350,
                    stacked: true,
                },
                plotOptions: {
                    bar: {
                        horizontal: true,
                        dataLabels: {
                            total: {
                                enabled: true,
                                offsetX: 0,
                                style: {
                                    fontSize: '13px',
                                    fontWeight: 900
                                }
                            }
                        }
                    },
                },
                stroke: {
                    width: 1,
                    colors: ['#fff']
                },
                title: {
                    text: 'Fiction Books Sales'
                },
                xaxis: {
                    categories: [2008, 2009, 2010, 2011, 2012, 2013, 2014],
                    labels: {
                        formatter: function (val) {
                            return val + "K"
                        }
                    }
                },
                yaxis: {
                    title: {
                        text: undefined
                    },
                },
                tooltip: {
                    y: {
                        formatter: function (val) {
                            return val + "K"
                        }
                    }
                },
                fill: {
                    opacity: 1
                },
                legend: {
                    position: 'top',
                    horizontalAlign: 'left',
                    offsetX: 40
                }
            };

            var emplymentChart = new ApexCharts(document.querySelector("#emplymentChart"), emplymentChartOption);
            emplymentChart.render();

            var religionChartOption = {
                title: {
                    text: 'Total Warga Per Agama',
                    offsetY: 0,
                    style: {
                        fontSize: '16px',
                        fontWeight: 'bold'
                    }
                },
                series: @js($this->religion['data']),
                chart: {
                    width: 340,
                    type: 'pie',
                },
                labels: @js($this->religion['label']),
                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: {
                            width: 200
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }],
                legend: {
                    position: 'top',
                    horizontalAlign: 'left',
                    fontSize: '14px',
                    offsetY: 20,
                    markers: {
                        width: 12,
                        height: 12,
                        radius: 12,
                    },
                    itemMargin: {
                        horizontal: 10,
                        vertical: 5
                    }
                },
            };

            var religionChart = new ApexCharts(document.querySelector("#religionChart"), religionChartOption);
            religionChart.render();

        }, {once: true});
    </script>
@endpushonce
