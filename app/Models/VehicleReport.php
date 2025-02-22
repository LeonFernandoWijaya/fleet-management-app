<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleReport extends Model
{
    /** @use HasFactory<\Database\Factories\VehicleReportFactory> */
    use HasFactory;

    protected $fillable = [
        'vehicle_id',
        'description',
        'user_id',
    ];

    public static function createRecord($data)
    {
        return self::create($data);
    }

    public static function updateRecord($id, $data)
    {
        return self::where('id', $id)->update($data);
    }
}
