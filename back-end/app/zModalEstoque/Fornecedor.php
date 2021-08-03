<?php

namespace App\zModalEstoque;
use Illuminate\Database\Eloquent\Model;
use App\zModalEstoque\FornecedorItens;

class Fornecedor extends Model
{
    protected $fillable = ['nome', 'telefone', 'email'];

    public function fornecedorItens() {
        return $this->hasMany(FornecedorItens::class);
    }
}
