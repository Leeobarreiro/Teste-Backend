@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2>Editar Produto</h2>

    <form action="{{ route('produtos.update', $produto->id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Nome --}}
        <div class="mb-3">
            <label for="nome" class="form-label">Nome do Produto</label>
            <input type="text" class="form-control" name="nome" value="{{ $produto->nome }}" required>
        </div>

        {{-- Preço --}}
        <div class="mb-3">
            <label for="preco" class="form-label">Preço (R$)</label>
            <input type="number" step="0.01" class="form-control" name="preco" value="{{ $produto->preco }}" required>
        </div>

        <hr>

        <h5>Tamanhos e Estoque</h5>
        <div id="variacoes-container">
            @foreach($produto->estoques as $i => $estoque)
                <div class="row mb-3 variacao-row">
                    <input type="hidden" name="estoque_id[]" value="{{ $estoque->id }}">
                    <div class="col-md-6">
                        <input type="text" name="variacao[]" class="form-control" value="{{ $estoque->variacao }}" placeholder="Ex: Tamanho M" required>
                    </div>
                    <div class="col-md-4">
                        <input type="number" name="quantidade[]" class="form-control" value="{{ $estoque->quantidade }}" placeholder="Quantidade" required>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-danger remove-variacao w-100">Remover</button>
                    </div>
                </div>
            @endforeach
        </div>

        <button type="button" class="btn btn-secondary mb-3" id="add-variacao">+ Adicionar Tamanho</button>

        <div class="d-grid">
            <button type="submit" class="btn btn-primary">Salvar Alterações</button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    document.getElementById('add-variacao').addEventListener('click', function () {
        const container = document.getElementById('variacoes-container');
        const row = document.createElement('div');
        row.className = 'row mb-3 variacao-row';
        row.innerHTML = `
            <input type="hidden" name="estoque_id[]" value="0">
            <div class="col-md-6">
                <input type="text" name="variacao[]" class="form-control" placeholder="Ex: Tamanho XG" required>
            </div>
            <div class="col-md-4">
                <input type="number" name="quantidade[]" class="form-control" placeholder="Quantidade" required>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger remove-variacao w-100">Remover</button>
            </div>
        `;
        container.appendChild(row);
    });

    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-variacao')) {
            e.target.closest('.variacao-row').remove();
        }
    });
</script>
@endsection
