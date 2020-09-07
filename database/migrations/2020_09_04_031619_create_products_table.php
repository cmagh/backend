<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        // , siendo nombre, cantidad y precio campos obligatorios.
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('SKU')->unique();
            $table->string('name');
            $table->integer('stock');
            $table->double('price', 8, 2);
            $table->string('description')->nullable();
            $table->string('img')->nullable();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
