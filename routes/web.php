<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\CarrinhoController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\PedidoWebhookController;



Route::get('/', function () {
    return view('index');
});

// Rotas do Produto
Route::get('/produtos', [ProdutoController::class, 'index'])->name('produtos.index');
Route::get('/produtos/create', [ProdutoController::class, 'create'])->name('produtos.create');
Route::post('/produtos', [ProdutoController::class, 'store'])->name('produtos.store');
Route::get('/produtos/{id}/edit', [ProdutoController::class, 'edit'])->name('produtos.edit');
Route::put('/produtos/{id}', [ProdutoController::class, 'update'])->name('produtos.update');

// Rotas do Carrinho
Route::post('/carrinho/adicionar', [CarrinhoController::class, 'adicionar'])->name('carrinho.adicionar');
Route::get('/carrinho', [CarrinhoController::class, 'index'])->name('carrinho.index');
Route::post('/carrinho/remover', [CarrinhoController::class, 'remover'])->name('carrinho.remover');
Route::post('/carrinho/finalizar', [CarrinhoController::class, 'concluirPedido'])->name('carrinho.concluir');
Route::get('/carrinho/checkout', [CarrinhoController::class, 'checkout'])->name('carrinho.checkout');
Route::post('/carrinho/cupom', [CarrinhoController::class, 'aplicarCupom'])->name('carrinho.aplicar-cupom');
Route::post('/carrinho/aplicar-cupom-ajax', [CarrinhoController::class, 'aplicarCupomAjax'])->name('carrinho.aplicar-cupom-ajax');
Route::post('/carrinho/remover-cupom', [CarrinhoController::class, 'removerCupom'])->name('carrinho.remover-cupom');



// Rotas do Cupom
Route::resource('cupons', \App\Http\Controllers\CupomController::class);

// Webhook
Route::post('/webhook/pedido-status', [PedidoWebhookController::class, 'atualizarStatus']);
