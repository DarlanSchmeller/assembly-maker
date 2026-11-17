<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('workstation.{code}', function ($user, $code) {
    return true; // allow all for prototype
});

Broadcast::channel('production', function ($user) {
    return true;
});
