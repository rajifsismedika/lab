<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBridgeMaterialRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bridge_material_requests', function (Blueprint $table) {
            $table->id();
            $table->string('external_id')->comment('HIS RequestID');
            $table->dateTime('requested_at');
            $table->date('received_at')->nullable();
            $table->string('healthcare_from_id')->comment('Healthcare yang meminta material');
            $table->string('healthcare_to_id')->comment('Healthcare yang dimintai material');
            $table->string('department_from_id')->comment('Departement yang meminta material');
            $table->string('department_to_id')->nullable()->comment('Departement yang dimintai material');
            $table->string('wh_from_id')->comment('Gudang yang meminta material');
            $table->string('wh_to_id')->nullable()->comment('Gudang yang dimintai material');
            $table->string('group');
            $table->boolean('is_direct_expense');
            $table->string('approved_by')->nullable();
            $table->string('approved_by_name')->nullable();
            $table->string('approved_at')->nullable();
            $table->string('status');
            $table->text('note')->nullable();

            $table->timestamps();
        });

        Schema::table('bridge_material_requests', function (Blueprint $table) {
            $table->index('external_id');
            $table->index('healthcare_from_id');
            $table->index('healthcare_to_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bridge_material_requests');
    }
}
