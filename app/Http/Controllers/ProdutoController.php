<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use App\Models\Estoque;
use Illuminate\Http\Request;

class ProdutoController extends Controller
{
    public function index()
    {
        $produtos = Produto::with('estoques')->get();
        return view('produtos.index', compact('produtos'));
    }

    public function create()
    {
        return view('produtos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'preco' => 'required|numeric',
            'variacao.*' => 'nullable|string|max:255',
            'quantidade.*' => 'nullable|integer|min:0',
        ]);

        $produto = Produto::create([
            'nome' => $request->nome,
            'preco' => $request->preco,
        ]);

        foreach ($request->variacao as $i => $variacao) {
            if ($variacao !== null) {
                Estoque::create([
                    'produto_id' => $produto->id,
                    'variacao' => $variacao,
                    'quantidade' => $request->quantidade[$i] ?? 0,
                ]);
            }
        }

        return redirect()->route('produtos.index');
    }

    public function edit($id)
    {
        $produto = Produto::with('estoques')->findOrFail($id);
        return view('produtos.edit', compact('produto'));
    }

    public function update(Request $request, $id)
    {
        $produto = Produto::findOrFail($id);
        $produto->update($request->only(['nome', 'preco']));

        // Atualizar estoques
        foreach ($request->estoque_id as $i => $estoqueId) {
            $estoque = Estoque::find($estoqueId);
            if ($estoque) {
            $estoque->update([
                    'variacao' => $request->variacao[$i],
                    'quantidade' => $request->quantidade[$i],
            ]);
        }
    }
    
        return redirect()->route('produtos.index')->with('success', 'Produto atualizado com sucesso!');
    }
}
