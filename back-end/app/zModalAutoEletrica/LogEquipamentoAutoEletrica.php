<?php

namespace App\zModalAutoEletrica;

use Illuminate\Database\Eloquent\Model;
use App\zModalAutoEletrica\AutoEletrica;

class LogEquipamentoAutoEletrica extends Model
{
    protected $fillable = ['nome', 'acao', 'tipo', 'auto_eletrica_id', 'user_name'];
    protected $with = ['auto_eletrica'];

    public function auto_eletrica() {
        return $this->belongsTo(AutoEletrica::class);
    }
}
