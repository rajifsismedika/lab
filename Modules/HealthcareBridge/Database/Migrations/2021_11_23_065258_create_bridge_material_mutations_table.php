<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBridgeMaterialMutationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bridge_material_mutations', function (Blueprint $table) {
            $table->id();
            $table->string('external_id')->comment('HIS MutasiID');
            $table->string('external_request_id')->comment('HIS RequestID');
            $table->dateTime('send_at')->nullable();
            $table->string('healthcare_from_id')->nullable()->comment('Healthcare yang mengirim material');
            $table->string('healthcare_to_id')->nullable()->comment('Healthcare yang menerima material');
            $table->string('department_from_id')->nullable()->comment('Departement yang mengirim material');
            $table->string('department_to_id')->nullable()->comment('Departement yang menerima material');
            $table->string('wh_from_id')->nullable()->comment('Gudang yang mengirim material');
            $table->string('wh_to_id')->nullable()->comment('Gudang yang menerima material');
            $table->string('group')->nullable();
            $table->string('mutation_type')->nullable();
            $table->string('approved_by')->nullable();
            $table->string('approved_by_name')->nullable();
            $table->dateTime('approved_at')->nullable();
            $table->string('status')->nullable();
            $table->text('note')->nullable();

            $table->timestamps();
        });

        Schema::table('bridge_material_mutations', function (Blueprint $table) {
            $table->index('external_id');
            $table->index('external_request_id');
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
        Schema::dropIfExists('bridge_material_mutations');
    }
}
