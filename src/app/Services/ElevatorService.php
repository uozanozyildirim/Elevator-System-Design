<?php

namespace App\Services;

use App\Models\Elevator;
use App\Models\ElevatorReservation;
use App\Contracts\Observable;
use App\Contracts\Observer;
use Illuminate\Support\Collection;
use App\Services\States\MovingUpState;
use App\Services\States\MovingDownState;
use App\Services\States\IdleState;
use Illuminate\Support\Facades\Log;

class ElevatorService implements Observable
{
    private Collection $observers;

    protected $selectedElevator = null;


    public function __construct()
    {
        $this->observers = collect();
        $elevators = Elevator::all();
        foreach ($elevators as $elevator) {
            $this->attach($elevator);
        }
    }

    public function attach(Observer $observer)
    {
        $this->observers->push($observer);
    }

    public function detach(Observer $observer)
    {
        $this->observers = $this->observers->filter(fn($o) => $o !== $observer);
    }

    public function notify()
    {
        Log::info('Notify called');
        foreach ($this->observers as $observer) {
            $observer->updateState($this);
        }
    }

    public function callElevator(int $currentFloor, int $targetFloor , ?int $priority = 0): string
    {
        $elevators = Elevator::all();

        // Check if any elevators are available
        if ($elevators->isEmpty()) {
            return 'No elevators are available.';
        }


        // Step 1: Look for idle elevators
        $idleElevators = $elevators->filter(fn($elevator) => $elevator->direction === 'idle');
        if (!$idleElevators->isEmpty()) {
            $this->selectedElevator = $idleElevators->sortBy(fn($elevator) => abs($elevator->current_floor - $currentFloor))->first();
        } else {
            // Step 2: Look for moving elevators heading towards the current floor
            $suitableElevators = $elevators->filter(function ($elevator) use ($currentFloor) {
                return ($elevator->direction === 'up' && $elevator->current_floor <= $currentFloor) ||
                    ($elevator->direction === 'down' && $elevator->current_floor >= $currentFloor);
            });

            if (!$suitableElevators->isEmpty()) {
                $this->selectedElevator = $suitableElevators->sortBy(fn($elevator) => abs($elevator->current_floor - $currentFloor))->first();
            } else {
                // Step 3: Fallback to the first available elevator
                $this->selectedElevator = $elevators->first();
            }
        }

        // Check if a suitable elevator was found
        if ($this->selectedElevator === null) {
            return 'No suitable elevator found.';
        }

        // Decide which state the elevator should be in
        if ($targetFloor > $currentFloor) {
            $this->selectedElevator->setState(new MovingUpState());
        } elseif ($targetFloor < $currentFloor) {
            $this->selectedElevator->setState(new MovingDownState());
        } else {
            $this->selectedElevator->setState(new IdleState());
        }

        $this->selectedElevator->move();

        // Create a new reservation
        $reservation = new ElevatorReservation([
            'elevator_id' => $this->selectedElevator->id,
            'current_floor' => $currentFloor,
            'target_floor' => $targetFloor,
            'status' => 'waiting',
        ]);
        $reservation->save();

        // Notify all observers about the change
        $this->notify();

        return $this->selectedElevator->name;
    }

    public function getCurrentFloor()
    {
        if (!$this->selectedElevator) {
            Log::error('Selected elevator is null');
            return null;
        }

        return $this->selectedElevator->current_floor;
    }

    public function getSelectedElevator()
    {
        return $this->selectedElevator;
    }


}
?>
