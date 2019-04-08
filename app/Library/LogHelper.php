<?php

use App\Models\Log;

function addLog($userid, $action, $notes = '')
{
    Log::create([
        'user_id'   => $userid,
        'action'   => $action,
        'notes'       => $notes
    ]);
}
