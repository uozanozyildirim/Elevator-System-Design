<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ElevatorReservation extends Model
{
    use HasFactory;

    protected $fillable = ['elevator_id', 'current_floor', 'target_floor', 'status'];

    public function elevator()
    {
        return $this->belongsTo(Elevator::class);
    }
}
