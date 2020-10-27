<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use App\Status;

class StatusEnum extends Model
{
    protected $fillable = ['title'];

    public function status() {
        return $this->hasMany(Status::class);
    }
}
