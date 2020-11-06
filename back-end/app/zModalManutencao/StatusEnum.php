<?php

namespace App\zModalManutencao;
use Illuminate\Database\Eloquent\Model;
use App\zModalManutencao\Status;

class StatusEnum extends Model
{
    protected $fillable = ['title'];

    public function status() {
        return $this->hasMany(Status::class);
    }
}
