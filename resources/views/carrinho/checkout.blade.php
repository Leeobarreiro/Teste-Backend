@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">Resumo do Pedido</h2>

    @if(session('carrinho') && count(session('carrinho')) > 0)
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Produto</th>
                    <th>Tamanho</th>
                    <th>Quantidade</th>
                    <th>Preço Unitário</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach(session('carrinho') as $item)
                    @php
                        $subtotalItem = $item['preco_unitario'] * $item['quantidade'];
                    @endphp
                    <tr>
                        <td>{{ $item['nome'] }}</td>
                        <td>{{ $item['variacao'] ?? 'Sem variação' }}</td>
                        <td>{{ $item['quantidade'] }}</td>
                        <td>R$ {{ number_format($item['preco_unitario'], 2, ',', '.') }}</td>
                        <td>R$ {{ number_format($subtotalItem, 2, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-5">
            <h4 class="mb-3">Endereço de Entrega</h4>

            <form action="{{ route('carrinho.concluir') }}" method="POST" class="mt-4">
    @csrf

    <div class="row">
        <div class="col-md-4 mb-3">
            <label for="cep">CEP:</label>
            <input type="text" name="cep" id="cep" class="form-control" required placeholder="Ex: 12345-678">
        </div>

        <div class="col-md-8 mb-3">
            <label for="logradouro">Rua:</label>
            <input type="text" name="logradouro" id="logradouro" class="form-control" required>
        </div>

        <div class="col-md-3 mb-3">
            <label for="numero">Número:</label>
            <input type="text" name="numero" id="numero" class="form-control" required>
        </div>

        <div class="col-md-5 mb-3">
            <label for="complemento">Complemento:</label>
            <input type="text" name="complemento" id="complemento" class="form-control">
        </div>

        <div class="col-md-4 mb-3">
            <label for="bairro">Bairro:</label>
            <input type="text" name="bairro" id="bairro" class="form-control" required>
        </div>

        <div class="col-md-6 mb-3">
            <label for="cidade">Cidade:</label>
            <input type="text" name="cidade" id="cidade" class="form-control" required>
        </div>

        <div class="col-md-2 mb-4">
            <label for="estado">UF:</label>
            <input type="text" name="estado" id="estado" class="form-control" required>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center">
    <div>
        <h5>Frete: R$ {{ number_format($frete, 2, ',', '.') }}</h5>
        <h4>Total: R$ {{ number_format($total, 2, ',', '.') }}</h4>
    </div>
    <button type="submit" class="btn btn-success">Finalizar Pedido</button>
</div>

</form>

        </div>
    @else
        <div class="alert alert-info">
            Seu carrinho está vazio.
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
    document.getElementById('cep').addEventListener('blur', function () {
        let cep = this.value.replace(/\D/g, '');
        if (cep.length === 8) {
            fetch(`https://viacep.com.br/ws/${cep}/json/`)
                .then(response => response.json())
                .then(data => {
                    if (data.erro) {
                        alert('CEP não encontrado.');
                        return;
                    }

                    document.getElementById('logradouro').value = data.logradouro || '';
                    document.getElementById('bairro').value = data.bairro || '';
                    document.getElementById('cidade').value = data.localidade || '';
                    document.getElementById('estado').value = data.uf || '';
                })
                .catch(() => alert('Erro ao buscar endereço.'));
        }
    });
</script>
@endsection
