<?php


namespace App\Contracts;

use App\Models\Elevator;

interface ElevatorState
{
    public function move(Elevator $elevator);
}


?>
