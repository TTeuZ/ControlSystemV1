<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Responsavel extends Model
{
    protected $fillable = ['nome', 'telefone', 'auto_eletrica_id'];
    protected $with = ['auto_eletrica'];

    public function auto_eletrica() {
        return $this->belongsTo(AutoEletrica::class);
    }
}
