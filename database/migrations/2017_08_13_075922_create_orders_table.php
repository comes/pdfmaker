<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('owner');
            $table->foreign('owner')
                ->references('id')
                ->on('users');

            $table->unsignedInteger('shipping_address_id');
            $table->unsignedInteger('billing_address_id');

            $table->foreign('shipping_address_id')
                ->references('id')
                ->on('addresses');

            $table->foreign('billing_address_id')
                ->references('id')
                ->on('addresses');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
