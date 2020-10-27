<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Equipamento;
use App\StatusEnum;

class Status extends Model
{
    protected $fillable = ['info', 'flag', 'equipamento_id', 'status_enum_id' ,'user_name_created', 'user_name_updated'];
    protected $with = ['equipamento', 'statusEnum'];

    public function equipamento() {
        return $this->belongsTo(Equipamento::class);
    }
    public function statusEnum() {
        return $this->belongsTo(StatusEnum::class);
    }
}


