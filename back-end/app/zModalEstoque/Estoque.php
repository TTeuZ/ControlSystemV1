<?php

namespace App\zModalEstoque;
use Illuminate\Database\Eloquent\Model;

class Estoque extends Model
{
    protected $fillable = ['name', 'flag', 'tipe', 'quantidade', 'quantidade_min', 'color', 'user_name_created', 'user_name_updated'];
}
