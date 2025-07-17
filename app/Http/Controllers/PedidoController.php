<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedido;

class PedidoController extends Controller
{
    public function webhook(Request $request)
    {
        $request->validate([
            'pedido_id' => 'required|exists:pedidos,id',
            'status' => 'required|string',
        ]);

        $pedido = Pedido::findOrFail($request->pedido_id);

        if (strtolower($request->status) === 'cancelado') {
            $pedido->delete();
            return response()->json(['message' => 'Pedido cancelado e removido com sucesso.'], 200);
        }

        $pedido->status = $request->status;
        $pedido->save();

        return response()->json(['message' => 'Status do pedido atualizado.'], 200);
    }
}
