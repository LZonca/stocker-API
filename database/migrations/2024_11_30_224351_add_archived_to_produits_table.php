<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddArchivedToProduitsTable extends Migration
{
    public function up()
    {
        Schema::table('produits', function (Blueprint $table) {
            $table->boolean('archived')->default(false);
        });
    }

    public function down()
    {
        Schema::table('produits', function (Blueprint $table) {
            $table->dropColumn('archived');
        });
    }
}
