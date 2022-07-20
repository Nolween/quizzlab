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
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('game_rule_id')
                ->constrained('game_rules')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->tinyInteger('max_players')->default('10');
            $table->tinyInteger('response_time')->default('20');
            $table->tinyInteger('question_count')->default('20');
            $table->tinyInteger('question_step')->nullable();
            $table->boolean('has_begun');
            $table->boolean('is_finished');
            $table->string('game_code')->nullable();
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
        Schema::dropIfExists('games');
    }
};
