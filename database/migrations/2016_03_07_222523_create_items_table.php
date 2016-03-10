<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->index();
            $table->integer('receipt_id')->index();
            $table->integer('category_id')->index();
            $table->dateTime("time");

            $table->string('name');
            $table->string('unit');
            $table->decimal('quantity');
            $table->decimal('unit_price');
            $table->decimal('subtotal');


            $table->string("discount");
            /*->default('{"discount": true}');*/



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
        Schema::drop('items');
    }
}
