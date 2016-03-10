<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDemoItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('DemoItems', function (Blueprint $table) {
            $table->increments('id');
            $table->string("name");
            $table->string("unit");
            $table->integer('category_id')->index();
            $table->integer('vendor_id')->index();
            $table->decimal("price")->nullable();
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
        Schema::drop('DemoItems');
    }
}
