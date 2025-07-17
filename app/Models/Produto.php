<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    protected $fillable = ['nome', 'preco'];

    public function estoques()
    {
        return $this->hasMany(Estoque::class);
    }

    public function pedidos()
    {
        return $this->belongsToMany(Pedido::class, 'pedido_produto')
                    ->withPivot('quantidade', 'preco_unitario')
                    ->withTimestamps();
    }
}
