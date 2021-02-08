<?php

namespace App\zModalEstoque;

use Illuminate\Database\Eloquent\Model;
use App\zModalEstoque\Fornecedor;

class FornecedorItens extends Model
{
    protected $fillable = ['nome', 'valor', 'data', 'observacao', 'quantidade', 'fornecedor_id'];

    public function fornecedor() {
        return $this->belongsTo(Fornecedor::class);
    }
}
