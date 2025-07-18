<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedido;

class PedidoWebhookController extends Controller
{
    public function atualizarStatus(Request $request)
    {
        $request->validate([
            'pedido_id' => 'required|exists:pedidos,id',
            'status' => 'required|string|in:pendente,processando,concluido,cancelado'
        ]);

        $pedido = Pedido::find($request->pedido_id);
        $pedido->status = $request->status;
        $pedido->save();

        return response()->json([
            'message' => 'Status do pedido atualizado com sucesso.',
            'pedido_id' => $pedido->id,
            'novo_status' => $pedido->status,
        ]);
    }
}
