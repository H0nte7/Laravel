<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAutosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('autos')) {
            Schema::create('autos', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->boolean('vip')->nullable();
                $table->boolean('admin_status')->nullable();
                $table->boolean('user_status')->nullable();
                $table->foreignId('region_id')->constrained('regions')->onDelete('cascade');
                $table->foreignId('city_id')->constrained('cities')->onDelete('cascade');
                $table->foreignId('mark_id')->constrained('marks')->onDelete('cascade');
                $table->foreignId('auto_model_id')->constrained('auto_models')->onDelete('cascade');
                $table->foreignId('transmission_id')->constrained('transmissions')->onDelete('cascade');
                $table->foreignId('fuel_id')->constrained('fuels')->onDelete('cascade');
                $table->integer('cost');
                $table->text('conditions');
                $table->time('delivery');
                $table->time('return');
                $table->foreignId('pledge_id')->constrained('pledges')->onDelete('cascade');
                $table->foreignId('doc_id')->constrained('docs')->onDelete('cascade');
                $table->integer('age');
                $table->integer('lease_term');
                $table->foreignId('pay_id')->constrained('pays')->onDelete('cascade');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('autos');
    }
}
