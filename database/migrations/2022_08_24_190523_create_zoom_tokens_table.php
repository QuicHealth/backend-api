<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zoom_tokens', function (Blueprint $table) {
            $table->id();
            $table->text('access_token');
            $table->string('token_type');
            $table->text('refresh_token');
            $table->string('expires_in');
            $table->text('scope');
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
        Schema::dropIfExists('zoom_tokens');
    }
};

// $table->text('access_token')->default("eyJhbGciOiJIUzUxMiIsInYiOiIyLjAiLCJraWQiOiJhYjlkZWRiZC01MDRiLTQ2ZDAtYmFmNy05YzgwZmJhNGZmYmUifQ.eyJ2ZXIiOjcsImF1aWQiOiIyMDZjOTliZDg5MDAzNmE1YTI2OTU4MDI0ODU2ZTExOSIsImNvZGUiOiJWb3ZTS2ZPMENRYWJ6V3J2ZnFRU0hhandMcmpLVFdnTGciLCJpc3MiOiJ6bTpjaWQ6dTdCS2J6bkZSWmF0VTZUOF9KeEhBIiwiZ25vIjowLCJ0eXBlIjowLCJ0aWQiOjAsImF1ZCI6Imh0dHBzOi8vb2F1dGguem9vbS51cyIsInVpZCI6Ik5PNjJnY2ZrUlZPaHFfTzMzNE9BS2ciLCJuYmYiOjE2NjU2NzU3NjYsImV4cCI6MTY2NTY3OTM2NiwiaWF0IjoxNjY1Njc1NzY2LCJhaWQiOiIteS02RW1NZFJiR2c5Tk9xTG5NQkNRIiwianRpIjoiNGY3MzZhYjMtNzBlMC00NDdkLWIyMGUtNTJhMDJlMjQ0OWM2In0.oluCVoU833YZCANo7Db--mId0Q4YR6KAiS6kOitNcLWWfKd3OnygaiWcF7w-zkZ7xsmiwv8wBXfbSlEpmO0eTQ");
// $table->text('refresh_token')->default("eyJhbGciOiJIUzUxMiIsInYiOiIyLjAiLCJraWQiOiJkY2I4ZjRiMi1kOTgzLTQ0ZmUtYjIyZC05MGNiMDNlYWZmMDEifQ.eyJ2ZXIiOjcsImF1aWQiOiIyMDZjOTliZDg5MDAzNmE1YTI2OTU4MDI0ODU2ZTExOSIsImNvZGUiOiJWb3ZTS2ZPMENRYWJ6V3J2ZnFRU0hhandMcmpLVFdnTGciLCJpc3MiOiJ6bTpjaWQ6dTdCS2J6bkZSWmF0VTZUOF9KeEhBIiwiZ25vIjowLCJ0eXBlIjoxLCJ0aWQiOjAsImF1ZCI6Imh0dHBzOi8vb2F1dGguem9vbS51cyIsInVpZCI6Ik5PNjJnY2ZrUlZPaHFfTzMzNE9BS2ciLCJuYmYiOjE2NjU2NzU3NjYsImV4cCI6MjEzODcxNTc2NiwiaWF0IjoxNjY1Njc1NzY2LCJhaWQiOiIteS02RW1NZFJiR2c5Tk9xTG5NQkNRIiwianRpIjoiOTg0NDkxMGMtNTAwMi00YmI5LWJiNmEtN2JiNzUzYTUwZjVjIn0.Sk5WA2GRSRWFio7hU4O12aKHf0lS6crVqrudltaUGKS1I7qYWOz9_R3QUo1HUweALkkQMuZ3bBH43ju7fqiLLA");