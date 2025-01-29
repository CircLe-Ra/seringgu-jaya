<?php

use App\Models\User;
use Illuminate\Support\Facades\Broadcast;


Broadcast::channel('notification-for-staff', function (User $user) {
    return $user->hasRole('staff');
});
