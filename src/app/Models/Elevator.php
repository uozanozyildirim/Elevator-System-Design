<?php

namespace App\Models;

use App\Contracts\ElevatorState;
use App\Contracts\Observer;
use App\Contracts\Observable;
use App\Services\States\IdleState;
use App\Services\States\MovingUpState;
use App\Services\States\MovingDownState;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Log;

class Elevator extends Model implements Observer
{

    protected $guarded = [];

    protected $fillable = ['current_floor', 'direction', 'target_floor'];


    use HasFactory;
    private ElevatorState $state;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setState(new IdleState());
    }

    public function setState(ElevatorState $state)
    {
        $this->state = $state;
    }

    public function move()
    {
        $this->state->move($this);
    }

    public function updateState(Observable $observable)
    {
        Log::info('UpdateState called');
        $selectedElevator = $observable->getSelectedElevator();
        Log::info('Selected Elevator:', ['id' => $selectedElevator->id ?? 'none']);

        if ($selectedElevator && $this->id === $selectedElevator->id) {
            $newFloor = $observable->getCurrentFloor();
            Log::info('New floor:', ['floor' => $newFloor ?? 'none']);
            $this->current_floor = $newFloor;
            $this->save();
        }
    }
}
