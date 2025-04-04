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

    $neighborhoodAssociations = NeighborhoodAssociation::orderBy('position')->get();
    $this->employment = [
        'categories' => $neighborhoodAssociations->pluck('position'),
        'series' => Employment::all()->map(function ($employment) use ($neighborhoodAssociations) {
            return [
                'name' => $employment->name,
                'data' => $neighborhoodAssociations->map(function ($rt) use ($employment) {
                    return FamilyMember::whereHas('family_card', function ($query) use ($rt) {
                        $query->where('neighborhood_association_id', $rt->id);
                    })->where('employment_id', $employment->id)->count();
                })
            ];
        })
    ];

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
            // Deklarasi tema
            const lightTheme = {
                theme: {
                    mode: 'light',
                    palette: 'palette1',
                }
            };

            const darkTheme = {
                theme: {
                    mode: 'dark',
                    palette: 'palette1',
                }
            };

            // Cek status dark mode
            let isDarkMode = localStorage.getItem('color-theme') === 'dark' ||
                (!('color-theme' in localStorage) &&
                    window.matchMedia('(prefers-color-scheme: dark)').matches);

            // Inisialisasi chart
            let emplymentChart, religionChart;

            function initCharts() {
                // Hancurkan chart sebelumnya jika ada
                if (emplymentChart) {
                    emplymentChart.destroy();
                }
                if (religionChart) {
                    religionChart.destroy();
                }

                // Options chart
                const emplymentChartOption = {
                    series: @js($this->employment['series']),
                    chart: {
                        type: 'bar',
                        height: 650,
                        stacked: true,
                    },
                    plotOptions: {
                        bar: {
                            horizontal: true,
                            barHeight: '90%',
                            columnWidth: '50%',
                            borderRadius: 4,
                            dataLabels: {
                                total: {
                                    enabled: true,
                                    offsetX: 0,
                                    style: {
                                        fontSize: '13px',
                                        fontWeight: 900
                                    }
                                },
                                position: 'center',
                                hideOverflowingLabels: false
                            }
                        },
                    },
                    stroke: {
                        width: 1,
                        colors: ['#fff']
                    },
                    title: {
                        text: 'Grafik Jumlah Jiwa Berdasarkan Pekerjaan Setiap RT',
                    },
                    xaxis: {
                        categories: @js($this->employment['categories']),
                        labels: {
                            formatter: function (val) {
                                return val + " Jiwa"
                            }
                        },
                        tickAmount: 8
                    },
                    yaxis: {
                        title: {
                            text: undefined
                        },
                    },
                    tooltip: {
                        y: {
                            formatter: function (val) {
                                return val + " Jiwa"
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

                const religionChartOption = {
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

                // Buat chart baru
                emplymentChart = new ApexCharts(document.querySelector("#emplymentChart"), {
                    ...emplymentChartOption,
                    ...(isDarkMode ? darkTheme : lightTheme)
                });
                emplymentChart.render();

                religionChart = new ApexCharts(document.querySelector("#religionChart"), {
                    ...religionChartOption,
                    ...(isDarkMode ? darkTheme : lightTheme)
                });
                religionChart.render();
            }

            // Fungsi untuk toggle theme
            function handleThemeToggle() {
                isDarkMode = !isDarkMode;
                localStorage.setItem('color-theme', isDarkMode ? 'dark' : 'light');

                // Update chart dengan transition instan
                if (emplymentChart) {
                    emplymentChart.updateOptions(isDarkMode ? darkTheme : lightTheme, false, true);
                }
                if (religionChart) {
                    religionChart.updateOptions(isDarkMode ? darkTheme : lightTheme, false, true);
                }
            }

            // Setup event listener untuk theme toggle
            function setupThemeToggle() {
                const themeToggle = document.getElementById('theme-toggle');
                if (themeToggle) {
                    // Hapus event listener sebelumnya untuk menghindari penumpukan
                    themeToggle.removeEventListener('click', handleThemeToggle);
                    themeToggle.addEventListener('click', handleThemeToggle);
                }
            }

            // Inisialisasi pertama kali
            initCharts();
            setupThemeToggle();

            // Cleanup saat komponen di-unmount (untuk Livewire)
            Livewire.on('component-teardown', () => {
                if (emplymentChart) {
                    emplymentChart.destroy();
                }
                if (religionChart) {
                    religionChart.destroy();
                }
                const themeToggle = document.getElementById('theme-toggle');
                if (themeToggle) {
                    themeToggle.removeEventListener('click', handleThemeToggle);
                }
            });

        }, {once: true});
    </script>
@endpushonce
