<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Mini ERP - Início</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #0f2027, #203a43, #2c5364);
            color: #fff;
            font-family: 'Segoe UI', sans-serif;
        }
        .hero {
            text-align: center;
            padding: 100px 20px;
        }
        .hero h1 {
            font-size: 3rem;
            font-weight: bold;
        }
        .btn-group-home a {
            min-width: 160px;
            margin: 10px;
        }
        .card-link:hover {
            transform: scale(1.05);
            transition: all 0.3s ease-in-out;
        }
    </style>
</head>
<body>
    <div class="hero">
        <h1>💳 Mini ERP </h1>
        <p class="lead mt-3">Gerencie seus produtos, cupons, pedidos e estoque de forma prática.</p>
        <div class="btn-group-home d-flex justify-content-center mt-4 flex-wrap">
            <a href="{{ route('produtos.index') }}" class="btn btn-warning btn-lg">🛍 Produtos</a>
            <a href="{{ route('carrinho.index') }}" class="btn btn-success btn-lg">🛒 Carrinho</a>
            <a href="{{ route('produtos.create') }}" class="btn btn-primary btn-lg">📦 Cadastrar Produto</a>
        </div>
    </div>

    <footer class="text-center text-white-50 mt-5">
        <p>Desenvolvido por <strong>Leonardo Barreiro</strong> • Teste Técnico</p>
    </footer>
</body>
</html>
