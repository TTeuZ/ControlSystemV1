<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use App\Status;

class Equipamento extends Model
{
    protected $fillable = ['name', 'done'];

    public function status() {
        return $this->hasMany(Status::class);
    }
}

