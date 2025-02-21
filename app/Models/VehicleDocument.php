<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleDocument extends Model
{
    /** @use HasFactory<\Database\Factories\VehicleDocumentFactory> */
    use HasFactory;

    protected $fillable = [
        'vehicle_id',
        'name',
        'expiry_date',
        'path',
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
