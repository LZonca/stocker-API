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
        Schema::create('produits', function (Blueprint $table) {
            $table->id();
            $table->integer('stock_id')->nullable();
            $table->string('code')->nullable();
            $table->string('nom');
            $table->string('description')->nullable();
            $table->integer('prix')->nullable();
            $table->string('image')->nullable();
            $table->date('expiry_date')->nullable();
            $table->integer('quantite')->default(0);
            $table->timestamps();

            // Add a unique constraint on stock_id and nom
            $table->unique(['stock_id', 'nom']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produits');
    }
};
