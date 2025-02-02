<?php

use App\Models\User;
use Illuminate\Support\Facades\Broadcast;


Broadcast::channel('notification-for-staff', function (User $user) {
    return $user->hasRole('staff');
});
Broadcast::channel('notification-for-neighborhood-and-citizen.{userId}', function (User $user, int $userId) {
    return $user->id === $userId;
});
