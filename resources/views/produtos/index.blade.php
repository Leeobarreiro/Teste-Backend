@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">Produtos Cadastrados</h2>

    {{-- Mensagens de sucesso e erro --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row">
        {{-- Lista de produtos --}}
        <div class="col-md-8">
            @if($produtos->isEmpty())
                <div class="alert alert-info">Nenhum produto cadastrado ainda.</div>
            @else
                <table class="table table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>Nome</th>
                            <th>Preço</th>
                            <th>Tamanhos e Estoque</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($produtos as $produto)
                        <tr>
                            <td>{{ $produto->nome }}</td>
                            <td>R$ {{ number_format($produto->preco, 2, ',', '.') }}</td>
                            <td>
                                <ul class="mb-0 list-unstyled">
                                    @foreach($produto->estoques as $estoque)
                                        <li class="mb-2 border-bottom pb-2">
                                            <div><strong>{{ $estoque->variacao ?? 'Sem variação' }}</strong>: {{ $estoque->quantidade }} unidades</div>

                                            {{-- Formulário de adicionar ao carrinho --}}
                                            <form action="{{ route('carrinho.adicionar') }}" method="POST" class="row g-2 mt-1">
                                                @csrf
                                                <input type="hidden" name="produto_id" value="{{ $produto->id }}">
                                                <input type="hidden" name="variacao_id" value="{{ $estoque->id }}">

                                                <div class="col-6">
                                                    <input type="number" name="quantidade" min="1" max="{{ $estoque->quantidade }}"
                                                        value="1" class="form-control form-control-sm" required>
                                                </div>

                                                <div class="col-6">
                                                    <button type="submit" class="btn btn-success btn-sm w-100">Adicionar ao carrinho</button>
                                                </div>
                                            </form>
                                        </li>
                                    @endforeach
                                </ul>
                            </td>
                            <td class="text-center align-middle">
                                <a href="{{ route('produtos.edit', $produto->id) }}" class="btn btn-sm btn-primary">Editar</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        {{-- Carrinho resumido --}}
        <div class="col-md-4">
            <div class="card bg-light">
                <div class="card-header bg-dark text-white">
                    🛒 Itens no Carrinho
                </div>
                <div class="card-body">
                    @php
                        $carrinho = session('carrinho', []);
                        $subtotal = 0;
                    @endphp

                    @if(count($carrinho) > 0)
                        <ul class="list-group mb-3">
                            @foreach($carrinho as $item)
                                @php
                                    $itemTotal = $item['quantidade'] * $item['preco_unitario'];
                                    $subtotal += $itemTotal;
                                @endphp
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $item['nome'] }}</strong><br>
                                        <small>{{ $item['variacao'] }} • {{ $item['quantidade'] }}x</small>
                                    </div>

                                    <div class="text-end">
                                        <span class="d-block mb-1">R$ {{ number_format($itemTotal, 2, ',', '.') }}</span>

                                        {{-- Ícone de remover --}}
                                        <form action="{{ route('carrinho.remover') }}" method="POST" style="display: inline;">
                                            @csrf
                                            <input type="hidden" name="key" value="{{ $item['produto_id'] . '-' . $item['estoque_id'] }}">
                                            <button type="submit" class="btn btn-sm btn-link text-danger p-0" title="Remover do carrinho">
                                                ❌
                                            </button>
                                        </form>
                                    </div>
                                </li>
                            @endforeach
                        </ul>

                        <p><strong>Subtotal:</strong> R$ {{ number_format($subtotal, 2, ',', '.') }}</p>

                        <a href="{{ route('carrinho.checkout') }}" class="btn btn-primary w-100">
                            Finalizar Compra
                        </a>
                    @else
                        <p class="text-muted">Seu carrinho está vazio.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
