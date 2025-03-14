<?php

use function Livewire\Volt\{state, layout, title, mount};
use App\Models\CitizenAssociation;
use App\Models\NeighborhoodAssociation;

layout('layouts.app');
title('Dashboard');
state(['citizen_association', 'neighborhood_association']);

mount(function () {
    $this->citizen_association = CitizenAssociation::count();
    $this->neighborhood_association = NeighborhoodAssociation::count();
});

?>

<div>
    //
</div>
