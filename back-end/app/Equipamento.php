<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use App\Status;

class Equipamento extends Model
{
    protected $fillable = ['name', 'done', 'user_name_created', 'user_name_updated'];

    public function status() {
        return $this->hasMany(Status::class);
    }
}

