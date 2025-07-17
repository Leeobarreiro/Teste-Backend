<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produto;
use App\Models\Estoque;
use App\Models\Pedido;

class CarrinhoController extends Controller
{
    public function index()
    {
        $carrinho = session()->get('carrinho', []);
        $subtotal = 0;

        foreach ($carrinho as $item) {
            $subtotal += $item['preco_unitario'] * $item['quantidade'];
        }

        $frete = $this->calcularFrete($subtotal);
        $total = $subtotal + $frete;

        return view('carrinho.index', compact('carrinho', 'subtotal', 'frete', 'total'));
    }

    private function calcularFrete($subtotal)
    {
        if ($subtotal >= 52 && $subtotal <= 166.59) {
            return 15;
        } elseif ($subtotal > 200) {
            return 0;
        } else {
            return 20;
        }
    }

    public function adicionar(Request $request)
    {
        $request->validate([
            'produto_id' => 'required|exists:produtos,id',
            'variacao_id' => 'required|exists:estoques,id',
            'quantidade' => 'required|integer|min:1',
        ]);

        $produto = Produto::findOrFail($request->produto_id);
        $estoque = Estoque::findOrFail($request->variacao_id);

        if ($request->quantidade > $estoque->quantidade) {
            return back()->with('error', 'Quantidade solicitada maior que o estoque disponível.');
        }

        $carrinho = session()->get('carrinho', []);
        $key = $produto->id . '-' . $estoque->id;

        if (isset($carrinho[$key])) {
            $carrinho[$key]['quantidade'] += $request->quantidade;
        } else {
            $carrinho[$key] = [
                'produto_id' => $produto->id,
                'nome' => $produto->nome,
                'variacao' => $estoque->variacao,
                'quantidade' => $request->quantidade,
                'preco_unitario' => $produto->preco,
                'estoque_id' => $estoque->id,
            ];
        }

        session()->put('carrinho', $carrinho);

        return redirect()->route('produtos.index')->with('success', 'Produto adicionado ao carrinho!');
    }

    public function remover(Request $request)
    {
    $key = $request->input('key');
    $carrinho = session()->get('carrinho', []);

    if (isset($carrinho[$key])) {
        unset($carrinho[$key]);
        session()->put('carrinho', $carrinho);
        return back()->with('success', 'Item removido do carrinho.');
    }

    return back()->with('error', 'Item não encontrado no carrinho.');
    }

    public function finalizar()
    {
        $carrinho = session()->get('carrinho', []);

        if (empty($carrinho)) {
            return redirect()->route('carrinho.index')->with('error', 'Carrinho vazio.');
        }

        return view('carrinho.finalizar');
    }

    public function checkout()
    {
        $carrinho = session()->get('carrinho', []);
        if (empty($carrinho)) {
            return redirect()->route('produtos.index')->with('error', 'Carrinho vazio.');
        }

        $subtotal = 0;
        foreach ($carrinho as $item) {
            $subtotal += $item['preco_unitario'] * $item['quantidade'];
        }

        $frete = $this->calcularFrete($subtotal);
        $total = $subtotal + $frete;

        return view('carrinho.checkout', compact('carrinho', 'subtotal', 'frete', 'total'));
    }

    public function concluirPedido(Request $request)
    {
        $request->validate([
            'cep' => 'required|string|max:9',
            'logradouro' => 'required|string|max:100',
            'numero' => 'required|string|max:10',
            'complemento' => 'nullable|string|max:100',
            'bairro' => 'required|string|max:100',
            'cidade' => 'required|string|max:100',
            'estado' => 'required|string|max:2',
        ]);

        $carrinho = session()->get('carrinho', []);

        if (empty($carrinho)) {
            return redirect()->route('produtos.index')->with('error', 'Carrinho vazio.');
        }

        $subtotal = 0;
        foreach ($carrinho as $item) {
            $subtotal += $item['preco_unitario'] * $item['quantidade'];
        }

        $frete = $this->calcularFrete($subtotal);
        $total = $subtotal + $frete;

        $enderecoCompleto = "{$request->logradouro}, {$request->numero} {$request->complemento}, {$request->bairro}, {$request->cidade} - {$request->estado}";

        $pedido = Pedido::create([
            'subtotal' => $subtotal,
            'frete' => $frete,
            'total' => $total,
            'status' => 'pendente',
            'cep' => $request->cep,
            'endereco' => $enderecoCompleto,
        ]);

        foreach ($carrinho as $item) {
            $pedido->produtos()->attach($item['produto_id'], [
                'quantidade' => $item['quantidade'],
                'preco_unitario' => $item['preco_unitario'],
            ]);

            $estoque = Estoque::find($item['estoque_id']);
            if ($estoque) {
                $estoque->quantidade -= $item['quantidade'];
                $estoque->save();
            }
        }

        session()->forget('carrinho');

        return redirect()->route('produtos.index')->with('success', 'Pedido finalizado com sucesso!');
    }
}
