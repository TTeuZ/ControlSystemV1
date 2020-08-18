<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Equipamento;

class Status extends Model
{
    protected $fillable = ['info', 'flag', 'equipamento_id', 'user_name_created', 'user_name_updated'];
    protected $with = ['equipamento'];

    public function equipamento() {
        return $this->belongsTo(Equipamento::class);
    }
}


