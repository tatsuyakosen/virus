<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

            Schema::create('products', function (Blueprint $table) {
                $table->Increments('id');
                $table->string('product_name');
                $table->integer('price');
                $table->integer('stock');
                $table->string('comment')->nullable();
                $table->string('image_path')->nullable();
                $table->timestamps();
                $table->integer('company_id')->nullable();
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
        $table->dropColumn('company_id');
    }
};
