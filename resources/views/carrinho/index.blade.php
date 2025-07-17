@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">🛍️ Seu Carrinho</h2>

    {{-- Mensagens de feedback --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            ✅ {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            ❌ {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(empty($carrinho))
        <div class="alert alert-info text-center p-4 rounded shadow-sm">
            🧺 Seu carrinho está vazio. <a href="{{ route('produtos.index') }}" class="btn btn-outline-primary ms-2">Ver produtos</a>
        </div>
    @else
        <div class="row">
            @foreach($carrinho as $key => $item)
                @php
                    $subtotalItem = $item['preco_unitario'] * $item['quantidade'];
                @endphp
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card shadow-sm h-100 border-primary">
                        <div class="card-body">
                            <h5 class="card-title">{{ $item['nome'] }}</h5>
                            <h6 class="card-subtitle mb-2 text-muted">{{ $item['variacao'] ?? 'Sem variação' }}</h6>

                            <p class="mb-1">🧾 <strong>Quantidade:</strong> {{ $item['quantidade'] }}</p>
                            <p class="mb-1">💰 <strong>Preço:</strong> R$ {{ number_format($item['preco_unitario'], 2, ',', '.') }}</p>
                            <p class="mb-3">🧮 <strong>Subtotal:</strong> R$ {{ number_format($subtotalItem, 2, ',', '.') }}</p>

                            <form action="{{ route('carrinho.remover') }}" method="POST">
                                @csrf
                                <input type="hidden" name="key" value="{{ $key }}">
                                <button type="submit" class="btn btn-outline-danger w-100">
                                    ❌ Remover do carrinho
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="card mt-4 shadow-sm">
            <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-center">
                <div class="mb-3 mb-md-0">
                    <p class="mb-1"><strong>Subtotal:</strong> R$ {{ number_format($subtotal, 2, ',', '.') }}</p>
                    <p class="mb-1"><strong>Frete:</strong> R$ {{ number_format($frete, 2, ',', '.') }}</p>
                    <h5><strong>Total:</strong> R$ {{ number_format($total, 2, ',', '.') }}</h5>
                </div>
                <a href="{{ route('carrinho.checkout') }}" class="btn btn-lg btn-success">
                    ✅ Finalizar Compra
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
