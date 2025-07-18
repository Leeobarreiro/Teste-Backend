<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Mini ERP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Bootstrap 5 CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">


    <style>
        body {
            background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
            color: #fff;
            overflow-x: hidden;
        }

        nav.navbar {
            box-shadow: 0 4px 8px rgba(0,0,0,0.3);
            border-bottom: 2px solid rgba(255, 255, 255, 0.1);
        }

        .navbar-brand, .nav-link {
            font-weight: bold;
            letter-spacing: 1px;
        }

        .navbar-brand:hover, .nav-link:hover {
            color: #ffd700 !important;
            text-shadow: 0 0 8px #ffd700;
        }

        main {
            animation: fadeIn 0.7s ease-in-out;
        }

        .card-link {
            border: 2px solid #00cfff;
            border-radius: 8px;
            transition: 0.3s ease;
            background: rgba(255, 255, 255, 0.05);
            color: #fff;
        }

        .card-link:hover {
            background: #00cfff;
            color: #000;
            box-shadow: 0 0 20px #00cfff;
        }

        .btn-group-home a {
            min-width: 180px;
            margin: 10px;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* Partículas no fundo - leve */
        #particles-js {
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            z-index: -1;
        }
    </style>
</head>
<body>

    {{-- Partículas (efeito visual) --}}
    <div id="particles-js"></div>

    {{-- Navbar moderna --}}
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">Mini ERP</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('produtos.index') }}">Produtos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('produtos.create') }}">Cadastrar</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('carrinho.index') }}">Carrinho</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('cupons.index') }}">Cupons</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    {{-- Conteúdo das páginas --}}
    <main>
        @yield('content')
    </main>

    {{-- Bootstrap Bundle com JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Particles.js (gamificação leve) --}}
    <script src="https://cdn.jsdelivr.net/npm/particles.js@2.0.0/particles.min.js"></script>
    <script>
        particlesJS("particles-js", {
            "particles": {
                "number": { "value": 45 },
                "color": { "value": "#00cfff" },
                "shape": { "type": "circle" },
                "opacity": { "value": 0.4 },
                "size": { "value": 3 },
                "move": {
                    "enable": true,
                    "speed": 1
                }
            }
        });
    </script>

    {{-- Scripts personalizados --}}
    @yield('scripts')
</body>
</html>
