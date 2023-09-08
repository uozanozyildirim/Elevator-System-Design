<?php

namespace App\Contracts;

interface Observer
{
    public function updateState(Observable $observable);
}


?>
