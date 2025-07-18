@extends('layouts.app')

@section('content')
<div class="container my-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">

            <div class="card shadow rounded-4 border-0">
                <div class="card-body p-4 bg-light">

                    <h4 class="fw-bold text-center mb-4">🧾 Resumo do Pedido</h4>

                    @if(session('carrinho') && count(session('carrinho')) > 0)

                        <div class="table-responsive mb-4">
                            <table class="table table-bordered text-center align-middle">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Produto</th>
                                        <th>Variação</th>
                                        <th>Qtd</th>
                                        <th>Preço Unit.</th>
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
                                            <td>{{ $item['variacao'] ?? '—' }}</td>
                                            <td>{{ $item['quantidade'] }}</td>
                                            <td>R$ {{ number_format($item['preco_unitario'], 2, ',', '.') }}</td>
                                            <td>R$ {{ number_format($subtotalItem, 2, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- CUPOM --}}
                        <div id="bloco-cupom" class="mb-4">
                            @if(session('cupom_aplicado'))
                                <div class="alert alert-success d-flex justify-content-between align-items-center">
                                    <div>
                                        ✅ Cupom <strong>{{ session('cupom_aplicado')->codigo }}</strong> aplicado — Desconto de R$ {{ number_format(session('cupom_aplicado')->desconto, 2, ',', '.') }}
                                    </div>
                                    <form action="{{ route('carrinho.remover-cupom') }}" method="POST" class="ms-3">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Remover</button>
                                    </form>
                                </div>
                            @else
                                <div class="row align-items-end g-2 mb-2" id="form-cupom">
                                    <div class="col-md-6">
                                        <label class="form-label" for="codigo-cupom">Cupom de desconto:</label>
                                        <input type="text" id="codigo-cupom" class="form-control" placeholder="Digite o código">
                                    </div>
                                    <div class="col-md-3">
                                        <button type="button" id="btn-aplicar-cupom" class="btn btn-primary w-100">Aplicar Cupom</button>
                                    </div>
                                </div>
                                <div id="mensagem-cupom"></div>
                            @endif
                        </div>

                        @if(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        {{-- FORMULÁRIO --}}
                        <form action="{{ route('carrinho.concluir') }}" method="POST">
                            @csrf

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label" for="email">E-mail:</label>
                                    <input type="email" name="email" id="email" class="form-control" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label" for="cep">CEP:</label>
                                    <div class="input-group">
                                        <input type="text" name="cep" id="cep" class="form-control" required placeholder="12345-678">
                                        <button type="button" id="btn-buscar-cep" class="btn btn-outline-secondary">Buscar</button>
                                    </div>
                                </div>

                                <div class="col-md-8">
                                    <label class="form-label" for="logradouro">Rua:</label>
                                    <input type="text" name="logradouro" id="logradouro" class="form-control" required>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label" for="numero">Número:</label>
                                    <input type="text" name="numero" id="numero" class="form-control" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label" for="complemento">Complemento:</label>
                                    <input type="text" name="complemento" id="complemento" class="form-control">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label" for="bairro">Bairro:</label>
                                    <input type="text" name="bairro" id="bairro" class="form-control" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label" for="cidade">Cidade:</label>
                                    <input type="text" name="cidade" id="cidade" class="form-control" required>
                                </div>

                                <div class="col-md-2">
                                    <label class="form-label" for="estado">UF:</label>
                                    <input type="text" name="estado" id="estado" class="form-control" required>
                                </div>
                            </div>

                            <hr class="my-4">

                            {{-- TOTAL --}}
                            <div class="d-flex justify-content-between align-items-center" id="resumo-valores">
                                <div>
                                    @php
                                        $cupom = session('cupom_aplicado');
                                        $totalComDesconto = $cupom ? max($total - $cupom->desconto, 0) : $total;
                                    @endphp

                                    @if($cupom)
                                        <h5 id="valor-desconto">Desconto: - R$ {{ number_format($cupom->desconto, 2, ',', '.') }}</h5>
                                    @else
                                        <h5 id="valor-desconto" style="display: none;"></h5>
                                    @endif

                                    <h5>Frete: R$ {{ number_format($frete, 2, ',', '.') }}</h5>
                                    <h4 id="valor-total">Total: R$ {{ number_format($totalComDesconto, 2, ',', '.') }}</h4>
                                </div>

                                <button type="submit" class="btn btn-success btn-lg">Finalizar Pedido</button>
                            </div>
                        </form>

                    @else
                        <div class="alert alert-info text-center">
                            Seu carrinho está vazio. 🛒
                        </div>
                    @endif

                </div>
            </div>

        </div>
    </div>
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
<script>
    document.getElementById('btn-aplicar-cupom').addEventListener('click', function () {
    const codigo = document.getElementById('codigo-cupom').value;

    fetch("{{ route('carrinho.aplicar-cupom-ajax') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({ codigo: codigo })
    })
    .then(response => response.json())
    .then(data => {
        const mensagem = document.getElementById('mensagem-cupom');
        const descontoEl = document.getElementById('valor-desconto');
        const totalEl = document.getElementById('valor-total');

        if (data.success) {
            mensagem.innerHTML = `<div class="alert alert-success">${data.message}</div>`;
            descontoEl.innerHTML = `Desconto: - R$ ${parseFloat(data.desconto).toFixed(2).replace('.', ',')}`;
            descontoEl.style.display = 'block';
            totalEl.innerHTML = `Total: R$ ${parseFloat(data.totalComDesconto).toFixed(2).replace('.', ',')}`;
            
            setTimeout(() => {
                location.reload(); // recarrega só após o sucesso
            }, 800); // tempo suficiente para o usuário ver o feedback visual
        } else {
            mensagem.innerHTML = `<div class="alert alert-danger">${data.message}</div>`;
        }


    })
    .catch(error => {
        document.getElementById('mensagem-cupom').innerHTML =
            `<div class="alert alert-danger">Erro ao aplicar cupom.</div>`;
    });
});
</script>

@endsection
