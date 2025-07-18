<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card shadow rounded-4 border-0 mt-4">
                <div class="card-body p-4 bg-light">

                    <h4 class="text-center fw-bold mb-4">
                        🎟️ Cadastro de Cupom
                    </h4>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Código do Cupom</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-ticket-alt"></i></span>
                                <input type="text" name="codigo" class="form-control rounded-end"
                                    value="{{ old('codigo', $cupon->codigo ?? '') }}" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Desconto (R$)</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-percent"></i></span>
                                <input type="number" step="0.01" name="desconto" class="form-control rounded-end"
                                    value="{{ old('desconto', $cupon->desconto ?? '') }}" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Valor Mínimo para Uso</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-coins"></i></span>
                                <input type="number" step="0.01" name="valor_minimo" class="form-control rounded-end"
                                    value="{{ old('valor_minimo', $cupon->valor_minimo ?? 0) }}">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="validade" class="form-label fw-semibold">Validade</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                <input type="date" name="validade" id="validade" class="form-control rounded-end"
                                    value="{{ old('validade', isset($cupon) ? $cupon->validade->format('Y-m-d') : '') }}"
                                    min="{{ date('Y-m-d') }}" required>
                            </div>
                            @error('validade')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-center mt-4 gap-2">
                        <button type="submit" class="btn btn-success px-4 rounded-pill shadow-sm">
                            <i class="fas fa-save me-1"></i> Salvar
                        </button>
                        <a href="{{ route('cupons.index') }}" class="btn btn-secondary px-4 rounded-pill shadow-sm">
                            <i class="fas fa-arrow-left me-1"></i> Cancelar
                        </a>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
