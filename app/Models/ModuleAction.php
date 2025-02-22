<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModuleAction extends Model
{
    /** @use HasFactory<\Database\Factories\ModuleActionFactory> */
    use HasFactory;

    public function module()
    {
        return $this->belongsTo(Module::class)->select('id', 'name');
    }

    public function action()
    {
        return $this->belongsTo(Action::class)->select('id', 'name');
    }
}
