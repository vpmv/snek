<?php

use App\Models\Gameboard;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('gameboards', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name', '50');
            $table->integer('size');
            $table->json('data');
        });

        $levels = [
            'default' => [
                'board' => include(__DIR__ . '/../../config/gameboards/default.php'),
                'meta'  => [],
            ],
            'helipad' => [
                'board' => include(__DIR__ . '/../../config/gameboards/helipad.php'),
                'meta'  => [],
            ],
            'face' => [
                'board' => include(__DIR__ . '/../../config/gameboards/face.php'),
                'meta'  => ['snekDirection' => 'ArrowRight'],
            ],
        ];

        foreach ($levels as $name => $board) {
            Gameboard::create([
                'name'         => $name,
                'size'         => count($board['board']),
                'data->matrix' => $board['board'],
                'data->meta'   => $board['meta'],
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gameboards');
    }
};
