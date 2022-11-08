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
            $table->string('blood_group');
            $table->string('genotype');
            $table->string('martial_status');
            $table->text('medication');
            $table->text('family_medical_history');
            $table->text('health_condition');
            $table->text('peculiar_cases');
            $table->text('allergies');
            $table->text('Occupation');
            $table->text('past_medical_history');
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
