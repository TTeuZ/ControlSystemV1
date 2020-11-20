<?php

namespace App\zModalCentralEquip;

use Illuminate\Database\Eloquent\Model;

class AllEquipamento extends Model
{
    protected $fillable = ['name', 'user_name', 'situacao', 'disponivel'];
}
