<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHousesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('houses')) {
            Schema::create('houses', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->boolean('vip')->nullable();
                $table->boolean('admin_status')->nullable();
                $table->boolean('user_status')->nullable();
                $table->foreignId('region_id')->constrained('regions')->onDelete('cascade');
                $table->foreignId('city_id')->constrained('cities')->onDelete('cascade');
                $table->foreignId('home_type_id')->constrained('homes_types')->onDelete('cascade');
                $table->foreignId('room_id')->constrained('rooms')->onDelete('cascade');
                $table->foreignId('guest_id')->constrained('guests')->onDelete('cascade');
                $table->string('street');
                $table->string('home_number');
                $table->string('total_area');
                $table->integer('cost');
                $table->text('conditions');
                $table->string('district');
                $table->string('micro_district')->nullable();
                $table->time('settling');
                $table->time('eviction');
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
        Schema::dropIfExists('houses');
    }
}
