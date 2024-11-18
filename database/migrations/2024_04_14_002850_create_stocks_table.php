<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->unsignedBigInteger('proprietaire_id')->nullable();
            $table->foreignId('groupe_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
            $table->foreign('proprietaire_id')->references('id')->on('users');
            $table->foreign('groupe_id')->references('id')->on('groupes');
        });

        DB::statement('ALTER TABLE stocks ADD CONSTRAINT chk_proprietaire_groupe CHECK (proprietaire_id IS NOT NULL OR groupe_id IS NOT NULL)');
    }

    public function down()
    {
        DB::statement('ALTER TABLE stocks DROP CONSTRAINT chk_proprietaire_groupe');

        Schema::dropIfExists('stocks');
    }
}
