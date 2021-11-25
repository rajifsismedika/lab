<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBridgeMaterialReceiveItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bridge_material_receive_items', function (Blueprint $table) {
            $table->id();
            $table->string('external_id')->comment('HIS Receive2ID');
            $table->string('external_receive_id');
            $table->string('external_mutation_item_id');
            $table->string('external_request_item_id');
            $table->string('external_item_id');
            $table->decimal('received_qty', 20, 2);
            $table->string('unit');
            $table->string('base_unit');
            $table->decimal('unit_conversion', 20, 2);
            $table->string('cogs', 33, 13);
            $table->text('note')->nullable();

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
        Schema::dropIfExists('bridge_material_receive_items');
    }
}
