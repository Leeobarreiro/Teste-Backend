<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('pedidos', function (Blueprint $table) {
        $table->id();
        $table->decimal('subtotal', 10, 2);
        $table->decimal('frete', 10, 2);
        $table->decimal('total', 10, 2);
        $table->string('status')->default('pendente');
        $table->string('cep')->nullable();
        $table->string('endereco')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};
