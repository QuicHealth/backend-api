<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHealthProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('health_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('blood_group')->nullable();
            $table->string('genotype')->nullable();
            $table->string('martial_status')->nullable();
            $table->text('medication')->nullable();
            $table->text('family_medical_history')->nullable();
            $table->text('health_condition')->nullable();
            $table->text('peculiar_cases')->nullable();
            $table->text('allergies')->nullable();
            $table->text('Occupation')->nullable();
            $table->text('past_medical_history')->nullable();
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
        Schema::dropIfExists('health_profiles');
    }
}
