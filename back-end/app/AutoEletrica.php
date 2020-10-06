<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AutoEletrica extends Model
{
    protected $fillable = ['nome', 'endereco', 'user_name_created'];

    public function cabos() {
        return $this->hasMany(Cabos::class);
    }
    public function equipamentosAutoEletrica() {
        return $this->hasMany(EquipamentoAutoEletrica::class);
    }
    public function Responsavel() {
        return $this->hasOne(Responsavel::class);
    }
}
