<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    /** @use HasFactory<\Database\Factories\TripFactory> */
    use HasFactory;

    protected $fillable = [
        'vehicle_id',
        'user_id',
        'trip_status_id',
        'departure_time',
        'arrival_time',
        'actual_departure_time',
        'actual_arrival_time',
        'departure_location',
        'arrival_location',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function tripStatus()
    {
        return $this->belongsTo(TripStatus::class);
    }

    public static function createRecord($data)
    {
        return self::create($data);
    }

    public static function updateRecord($id, $data)
    {
        return self::where('id', $id)->update($data);
    }
}
