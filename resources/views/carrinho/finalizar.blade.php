@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2>Finalizar Pedido</h2>

    <form action="{{ route('carrinho.concluir') }}" method="POST">
        @csrf

        {{-- CEP --}}
        <div class="mb-3">
            <label for="cep" class="form-label">CEP</label>
            <input type="text" name="cep" id="cep" class="form-control" required maxlength="9">
        </div>

        {{-- Endereço preenchido automaticamente --}}
        <div class="mb-3">
            <label for="endereco" class="form-label">Endereço</label>
            <input type="text" name="endereco" id="endereco" class="form-control" readonly>
        </div>

        {{-- Botão de Finalizar --}}
        <div class="d-grid">
            <button type="submit" class="btn btn-primary">Confirmar Pedido</button>
        </div>
    </form>
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
                        alert('CEP não encontrado');
                        return;
                    }
                    const endereco = `${data.logradouro}, ${data.bairro}, ${data.localidade} - ${data.uf}`;
                    document.getElementById('endereco').value = endereco;
                })
                .catch(() => alert('Erro ao buscar endereço'));
        }
    });
</script>
@endsection
