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
        Schema::create('product', function (Blueprint $table) {
            $table->id();
            $table->text("image");
            $table->string("title");
            $table->string("description");
            $table->double("price");
            $table->integer("stock_quantity");
            $table->boolean("is_available")->default(true);
            $table->boolean("is_featured")->default(false);
            $table->timestamps();
            $table->unsignedBigInteger("category_id");
            $table->foreign("category_id")->references("id")->on("category");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product');
    }
};
