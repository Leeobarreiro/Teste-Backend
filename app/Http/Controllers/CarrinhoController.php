<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produto;
use App\Models\Estoque;
use App\Models\Pedido;
use App\Models\Cupom;
use Illuminate\Support\Facades\Session;
use App\Mail\PedidoFinalizadoMail;
use Illuminate\Support\Facades\Mail;



class CarrinhoController extends Controller
{
   public function index()
{
    $carrinho = session()->get('carrinho', []);
    $subtotal = 0;

    foreach ($carrinho as $item) {
        $subtotal += $item['preco_unitario'] * $item['quantidade'];
    }

    // Aplicar desconto do cupom, se existir
    $cupom = session('cupom_aplicado');
    $subtotalComDesconto = $this->calcularTotalComCupom($subtotal);

    $frete = $this->calcularFrete($subtotalComDesconto);
    $total = $subtotalComDesconto + $frete;

    return view('carrinho.index', compact('carrinho', 'subtotal', 'subtotalComDesconto', 'frete', 'total', 'cupom'));
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
        'email' => 'required|email',

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

    // ✅ Verifica se existe um cupom na sessão
    $cupom = session('cupom_aplicado');
    $desconto = $cupom ? $cupom->desconto : 0;

    // ✅ Calcula o total com desconto (nunca abaixo de zero)
    $total = max($subtotal - $desconto, 0) + $frete;

    $enderecoCompleto = "{$request->logradouro}, {$request->numero} {$request->complemento}, {$request->bairro}, {$request->cidade} - {$request->estado}";

    $pedido = Pedido::create([
        'subtotal' => $subtotal,
        'frete' => $frete,
        'total' => $total,
        'status' => 'pendente',
        'cep' => $request->cep,
        'endereco' => $enderecoCompleto,
        'cupom_id' => $cupom?->id,
        'endereco' => $enderecoCompleto,
        'email' => $request->email,

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

    // Envia e-mail de confirmação
$pedido->load('produtos');

Mail::to($pedido->email)->send(new PedidoFinalizadoMail($pedido));

    // ✅ Limpa sessão do carrinho e cupom
    session()->forget('carrinho');
    session()->forget('cupom_aplicado');

    return redirect()->route('produtos.index')->with('success', 'Pedido finalizado com sucesso!');
}


    private function calcularSubtotal()
{
    $carrinho = session('carrinho', []);
    $subtotal = 0;

    foreach ($carrinho as $item) {
        $subtotal += $item['preco_unitario'] * $item['quantidade'];
    }

    return $subtotal;
}


    
    public function aplicarCupom(Request $request)
    {
    $request->validate([
        'codigo' => 'required'
    ]);

    $cupon = Cupom::where('codigo', $request->codigo)
        ->whereDate('validade', '>=', now())
        ->first();

    if (!$cupon) {
        return redirect()->back()->with('error', 'Cupom inválido ou expirado.');
    }

    // Verifica valor mínimo do carrinho
    $subtotal = $this->calcularSubtotal(); 
    if ($subtotal < $cupon->valor_minimo) {
        return redirect()->back()->with('error', 'Este cupom requer compras acima de R$ ' . number_format($cupon->valor_minimo, 2, ',', '.'));
    }

    Session::put('cupom_aplicado', $cupon);

    return redirect()->back()->with('success', 'Cupom aplicado com sucesso!');
    }
    
    private function calcularTotalComCupom($subtotal)
    {
    $cupom = session('cupom_aplicado');

    if ($cupom && isset($cupom->desconto)) {
        return max($subtotal - $cupom->desconto, 0); // Nunca negativo
    }

    return $subtotal;
}

public function aplicarCupomAjax(Request $request)
{
    $codigo = $request->input('codigo');
    $cupom = Cupom::where('codigo', $codigo)->first();

    if (!$cupom) {
        return response()->json(['success' => false, 'message' => 'Cupom inválido.']);
    }

    if ($cupom->validade && now()->toDateString() > $cupom->validade->toDateString()) {
        return response()->json(['success' => false, 'message' => 'Cupom expirado.']);
    }

    $subtotal = $this->calcularSubtotal(); // método que soma o carrinho
    if ($cupom->valor_minimo && $subtotal < $cupom->valor_minimo) {
        return response()->json(['success' => false, 'message' => 'Valor mínimo não atingido para usar este cupom.']);
    }

    session(['cupom_aplicado' => $cupom]);

    $desconto = $cupom->desconto;
    $frete = $this->calcularFrete($subtotal);
    $totalComDesconto = max(($subtotal + $frete) - $desconto, 0);

    return response()->json([
        'success' => true,
        'message' => 'Cupom aplicado com sucesso!',
        'desconto' => number_format($desconto, 2, ',', '.'),
        'totalComDesconto' => number_format($totalComDesconto, 2, ',', '.')
    ]);
}

public function removerCupom()
{
    session()->forget('cupom_aplicado');
    return redirect()->back()->with('success', 'Cupom removido com sucesso!');
}


}
