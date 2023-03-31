<?php

use App\Models\Score;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('scores', function (Blueprint $table) {
            $table->id();
            $table->string('levelname');
            $table->string('username');
            $table->integer('score');
        });

        $scores = [
            'default' => [
                'johndoe' => 10,
                'madmax'  => 50,
                'noob'    => 5,
                'foobar'  => 21,
            ],
            'helipad' => [
                'johndoe' => 25,
                'madmax'  => 30,
                'noob'    => 5,
                'foobar'  => 15,
            ],
        ];

        foreach ($scores as $levelName => $highScores) {
            foreach ($highScores as $player => $score) {
                $model = new Score();
                $model->setLevelName($levelName);
                $model->setUsername($player);
                $model->setScore($score);
                $model->save();
            }
        }

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scores');
    }
};
