<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Services\ElevatorService;

class CallElevatorCommand extends Command
{
    protected $signature = 'elevator:call {--current_floor= : The current floor} {--target_floor= : The target floor}';
    protected $description = 'Call an elevator to the specified floor';

    protected $elevatorService;

    public function __construct(ElevatorService $elevatorService)
    {
        parent::__construct();
        $this->elevatorService = $elevatorService;
    }

    public function handle()
    {
        $currentFloor = $this->option('current_floor');
        $targetFloor = $this->option('target_floor');

        $selectedElevator = $this->elevatorService->callElevator($currentFloor, $targetFloor);

        $this->info("Elevator {$selectedElevator} has been dispatched from floor {$currentFloor} to {$targetFloor}.");
    }
}
