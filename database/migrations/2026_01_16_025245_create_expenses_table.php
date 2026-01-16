<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {

        Schema::create('expenses', function (Blueprint $table) {

            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 12, 2);
            $table->date('spent_at');
            $table->string('description')->nullable();

            //Foráneas para categoría y métodos de pago. Timestamps e index para mejorar
            //la performance, por usuario y fecha

            // $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            // $table->foreignId('payment_method_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
            $table->index(['user_id', 'spent_at']);

        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
