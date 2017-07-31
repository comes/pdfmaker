<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Products extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('name');
            $table->longText('description')->nullable();

            $table->integer('parent_id')->nullable()->unsigned();
            $table->foreign('parent_id')
                ->references('id')
                ->on('products');

            $table->integer('stock')->default(0);
            $table->boolean('buyable')->default(0);

            $table->json('options')->nullable();
            /**
            {
                'step' : integer (default = 1),
                'minamount' : integer  (default = 1),
                'customizable' : boolean (default = false),
                'layout' : path/to/pdf/file (nullable),
            }
            **/

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
        Schema::dropIfExists('products');
    }
}
