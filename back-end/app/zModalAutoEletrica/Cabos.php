<?php

namespace App\zModalAutoEletrica;

use Illuminate\Database\Eloquent\Model;

class Cabos extends Model
{
    protected $fillable = ['nome', 'tipo', 'situacao', 'auto_eletrica_id', 'user_name_created', 'user_name_updated'];
    protected $with = ['auto_eletrica'];

    public function auto_eletrica() {
        return $this->belongsTo(AutoEletrica::class);
    }
}
