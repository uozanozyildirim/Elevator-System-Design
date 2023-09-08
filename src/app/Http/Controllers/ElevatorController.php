<?php


namespace App\Http\Controllers;

use App\Services\ElevatorService;
use Illuminate\Http\Request;

class ElevatorController extends Controller
{
    protected $elevatorService;

    public function __construct(ElevatorService $elevatorService)
    {
        $this->elevatorService = $elevatorService;
    }

    public function callElevator(Request $request)
    {
        $currentFloor = $request->input('current_floor');
        $targetFloor = $request->input('target_floor');

        $this->elevatorService->callElevator($currentFloor, $targetFloor);

        return response()->json(['message' => 'Elevator called']);
    }
}
