<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('user_produits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('produit_id')->constrained()->onDelete('cascade');
            $table->string('custom_image')->nullable();
            $table->string('custom_code')->nullable();
            $table->string('custom_name')->nullable();
            $table->text('custom_description')->nullable();
            // Add any other custom fields here
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_produit');
    }
};
