<?php

// app/Services/States/IdleState.php

namespace App\Services\States;

use App\Contracts\ElevatorState;
use App\Models\Elevator;

class IdleState implements ElevatorState
{
    public function move(Elevator $elevator)
    {
        $elevator->direction = 'idle';
        $elevator->save();
    }
}



?>
