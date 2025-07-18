<?php

namespace App\Http\Controllers;

use App\Models\Cupom;
use Illuminate\Http\Request;

class CupomController extends Controller
{
    public function index()
    {
        $cupons = Cupom::latest()->paginate(10);
        return view('cupons.index', compact('cupons'));
    }

    public function create()
    {
        return view('cupons.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'codigo' => 'required|unique:cupons,codigo',
            'desconto' => 'required|numeric|min:0',
            'valor_minimo' => 'nullable|numeric|min:0',
            'validade' => 'required|date|after_or_equal:today',
        ]);

        Cupom::create($request->all());

        return redirect()->route('cupons.index')->with('success', 'Cupom criado com sucesso!');
    }

    public function edit(Cupom $cupon)
    {
    return view('cupons.edit', compact('cupon'));
    }


    public function update(Request $request, Cupom $cupon)
    {
        $request->validate([
            'codigo' => 'required|unique:cupons,codigo,' . $cupon->id,
            'desconto' => 'required|numeric|min:0',
            'valor_minimo' => 'nullable|numeric|min:0',
            'validade' => 'required|date|after_or_equal:today',
        ]);

        $cupon->update($request->all());

        return redirect()->route('cupons.index')->with('success', 'Cupom atualizado com sucesso!');
    }

    public function destroy(Cupom $cupon)
    {
        $cupon->delete();
        return redirect()->route('cupons.index')->with('success', 'Cupom removido.');
    }
}
