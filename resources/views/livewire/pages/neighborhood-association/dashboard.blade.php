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
            'data' => $religion->family_members()->whereHas('family_card', function ($query) {
                $query->where('neighborhood_association_id', auth()->user()->neighborhoodAssociation->id);
            })->count(),
        ];
    })->reduce(function ($carry, $item) {
        $carry['label'][] = $item['name'];
        $carry['data'][] = $item['data'];
        return $carry;
    }, ['label' => [], 'data' => []]);

    $neighborhoodAssociationId = auth()->user()->neighborhoodAssociation->id;

    $this->employment = [
        'series' => [
            [
                'data' => Employment::withCount([
                    'family_members' => function($query) use ($neighborhoodAssociationId) {
                        $query->whereHas('family_card', function($q) use ($neighborhoodAssociationId) {
                            $q->where('neighborhood_association_id', $neighborhoodAssociationId);
                        });
                    }
                ])->get()->pluck('family_members_count')->toArray()
            ]
        ],
        'categories' => Employment::all()->pluck('name')->toArray()
    ];


    $this->family_card = FamilyCard::where('neighborhood_association_id', auth()->user()->neighborhoodAssociation->id)->count();
    $this->people = FamilyMember::whereHas('family_card', function ($query){
        $query->where('neighborhood_association_id', auth()->user()->neighborhoodAssociation->id);
    })->count();
});

?>

<div>
    <x-ui.breadcrumbs :crumbs="[
               ['href' => '/', 'text' => 'Dashboard'],
            ]">
        <x-slot name="actions">
            <h1 class="text-xl py-[3.5px] font-medium text-gray-900 dark:text-white">Halo, {{ auth()->user()->name }} - {{ Carbon::parse(date('Y-m-d'))->locale('id')->isoFormat('D MMMM Y') }}</h1>
        </x-slot>
    </x-ui.breadcrumbs>
    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-2 gap-2 mt-2">
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
            const lightTheme = {
                theme: {
                    mode: 'light',
                    palette: 'palette1'
                }
            };

            const darkTheme = {
                theme: {
                    mode: 'dark',
                    palette: 'palette1'
                }
            };

            let isDarkMode = localStorage.getItem('color-theme') === 'dark' ||
                (!('color-theme' in localStorage) &&
                    window.matchMedia('(prefers-color-scheme: dark)').matches);

            var emplymentChartOption = {
                series: @js($this->employment['series'] ?? []),
                chart: {
                    type: 'bar',
                    height: 650,
                    stacked: true,
                    toolbar: { show: false },
                    background: 'transparent',
                },
                plotOptions: {
                    bar: {
                        columnWidth: '45%',
                        distributed: true,
                    }
                },
                stroke: {
                    width: 1,
                    colors: ['#fff']
                },
                title: {
                    text: 'Grafik Jumlah Jiwa Berdasarkan Pekerjaan Setiap ' + @js(auth()->user()->neighborhoodAssociation->position ?? 'RT'),
                    style: {
                        fontSize: '16px',
                        fontWeight: 'bold'
                    }
                },
                xaxis: {
                    categories: @js($this->employment['categories'] ?? []),
                    labels: {
                        formatter: function(val) {
                            return val;
                        }
                    }
                },
                yaxis: {
                    title: { text: undefined },
                    tickAmount: 3,
                    labels: {
                        formatter: function(val) {
                            return Math.round(val);
                        }
                    }
                },
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return val + " Jiwa";
                        }
                    }
                },
                fill: { opacity: 1 },
                legend: {
                    position: 'top',
                    horizontalAlign: 'left',
                    offsetX: 40
                }
            };

            var religionChartOption = {
                series: @js($this->religion['data'] ?? []),
                chart: {
                    width: 340,
                    type: 'pie',
                    background: 'transparent',
                },
                labels: @js($this->religion['label'] ?? []),
                title: {
                    text: 'Total Warga Per Agama',
                    align: 'center',
                    style: {
                        fontSize: '16px',
                        fontWeight: 'bold'
                    }
                },
                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: { width: 200 },
                        legend: { position: 'bottom' }
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
                }
            };

            var emplymentChart = new ApexCharts(
                document.querySelector("#emplymentChart"),
                { ...emplymentChartOption, ...(isDarkMode ? darkTheme : lightTheme) }
            );
            emplymentChart.render();

            var religionChart = new ApexCharts(
                document.querySelector("#religionChart"),
                { ...religionChartOption, ...(isDarkMode ? darkTheme : lightTheme) }
            );
            religionChart.render();

            document.getElementById('theme-toggle')?.addEventListener('click', function() {
                isDarkMode = !isDarkMode;
                emplymentChart.updateOptions(isDarkMode ? darkTheme : lightTheme);
                religionChart.updateOptions(isDarkMode ? darkTheme : lightTheme);
            });

        }, {once: true});
    </script>
@endpushonce
