<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Equipamento;

class Status extends Model
{
    protected $fillable = ['info', 'flag', 'equipamento_id'];
    protected $with = ['equipamento'];

    public function equipamento() {
        return $this->belongsTo(Equipamento::class);
    }
}


