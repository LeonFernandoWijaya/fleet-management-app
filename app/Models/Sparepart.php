<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sparepart extends Model
{
    /** @use HasFactory<\Database\Factories\SparepartFactory> */
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'name',
        'stock',
        'reorder_level',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
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
