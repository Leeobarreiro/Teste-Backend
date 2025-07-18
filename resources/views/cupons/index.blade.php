@extends('layouts.app')

@section('content')
<div class="container mt-5">

    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
            <h4 class="mb-0">🎟️ Gerenciar Cupons</h4>
            <a href="{{ route('cupons.create') }}" class="btn btn-light btn-sm">
                <i class="fas fa-plus"></i> Novo Cupom
            </a>
        </div>

        <div class="card-body">

            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-secondary">
                        <tr>
                            <th>Código</th>
                            <th>Desconto</th>
                            <th>Valor Mínimo</th>
                            <th>Validade</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($cupons as $cupon)
                        <tr>
                            <td><strong>{{ $cupon->codigo }}</strong></td>
                            <td>R$ {{ number_format($cupon->desconto, 2, ',', '.') }}</td>
                            <td>R$ {{ number_format($cupon->valor_minimo, 2, ',', '.') }}</td>
                            <td>
                                <span class="badge bg-{{ \Carbon\Carbon::parse($cupon->validade)->isPast() ? 'danger' : 'success' }}">
                                    {{ \Carbon\Carbon::parse($cupon->validade)->format('d/m/Y') }}
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('cupons.edit', $cupon) }}" class="btn btn-sm btn-outline-primary me-1">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('cupons.destroy', $cupon) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Excluir cupom?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">Nenhum cupom cadastrado.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $cupons->links() }}
            </div>

        </div>
    </div>
</div>
@endsection
