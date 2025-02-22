<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    /** @use HasFactory<\Database\Factories\VehicleFactory> */
    use HasFactory;
    protected $fillable = [
        'vehicle_type_id',
        'vehicle_status_id',
        'plate_number',
        'brand',
        'model',
        'capacity_ton',
        'reservice_level',
    ];

    public function vehicleType()
    {
        return $this->belongsTo(VehicleType::class);
    }

    public function vehicleStatus()
    {
        return $this->belongsTo(VehicleStatus::class);
    }

    public function vehicleDocuments()
    {
        return $this->hasMany(VehicleDocument::class);
    }

    public function vehicleReports()
    {
        return $this->hasMany(VehicleReport::class);
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
