<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBridgeMaterialRequestItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bridge_material_request_items', function (Blueprint $table) {
            $table->id();
            $table->string('external_id')->nullable()->comment('HIS Request2ID');
            $table->string('external_request_id')->nullable();
            $table->string('external_item_id')->nullable();
            $table->decimal('qty', 20, 2)->nullable();
            $table->decimal('received_qty', 20, 2)->nullable();
            $table->string('unit')->nullable();
            $table->string('base_unit')->nullable();
            $table->decimal('unit_conversion', 20, 2)->nullable();
            $table->text('note')->nullable();

            $table->timestamps();
        });


        Schema::table('bridge_material_request_items', function (Blueprint $table) {
            $table->index('external_request_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bridge_material_request_items');
    }
}
