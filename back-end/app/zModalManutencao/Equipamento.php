<?php

namespace App\zModalManutencao;
use Illuminate\Database\Eloquent\Model;
use App\zModalManutencao\Status;

class Equipamento extends Model
{
    protected $fillable = ['name', 'done', 'time', 'user_name_created', 'user_name_updated'];

    public function status() {
        return $this->hasMany(Status::class);
    }
}

