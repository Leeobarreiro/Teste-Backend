<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{

protected $fillable = [
    'subtotal', 'frete', 'total', 'status', 'cep', 'endereco', 'cupom_id', 'email'
];


    public function produtos()
    {
        return $this->belongsToMany(Produto::class, 'pedido_produto')
                    ->withPivot('quantidade', 'preco_unitario')
                    ->withTimestamps();
    }
    public function cupom()
{
    return $this->belongsTo(Cupom::class);
}

}
