<?php

namespace App\Http\Controllers;

use App\Models\Gameboard;
use App\Models\Score;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class GameController extends Controller
{

    public function index(Request $request)
    {
        return Inertia::render('GameIndex', [
            'highScores'  => $this->getHiScoresGrouped(),
            'gameBoards' => $this->getGameBoards(),
        ]);
    }


    public function playGame(Request $request, string $level)
    {
        $gameBoard = Gameboard::where(['name' => $level])->firstOrFail();

        return Inertia::render('MainGame', [
            'highScores'  => $this->getHiScores($level)->toArray(),
            'gameBoard' => $gameBoard->toArray(),
        ]);

    }

    public function levelEditor(Request $request, $level = 'default')
    {
        $config = Gameboard::where(['name' => $level])->firstOrFail();
        $mayDelete = !in_array($level, ['default', 'face', 'helipad']);
        return Inertia::render('LevelEditor', [
            'boardConfig' => $config->toArray(),
            'mayDelete' => $mayDelete,
        ]);
    }

    public function saveLevel(Request $request) {
        $request->validate([
            'levelname' => ['regex:/\w+[\w\s]+/', 'required', 'max:20'],
            'matrix' => ['required', 'array'],
            'meta' => ['required', 'array']
        ]);

        $level = $request->request->get('levelname');
        if (in_array($level, ['default', 'face', 'helipad'])) {
            throw new \InvalidArgumentException('You may not alter default levels');
        }

        Gameboard::updateOrCreate(
            ['name' => $level],
            ['data->matrix' => $request->request->get('matrix'),'data->meta' => $request->request->get('meta'),'size' => 25]
        );

        return Redirect::to('/');
    }

    public function deleteLevel(Request $request, $level)
    {
        if (in_array($level, ['default', 'face', 'helipad'])) {
            throw new \InvalidArgumentException('You may not delete default levels');
        }

        $config = Gameboard::where(['name' => $level])->firstOrFail();
        $config->delete();

        return Redirect::to('/');
    }

    public function getGameBoards(): array
    {
        $res = [];
        Gameboard::all()->map(function ($v) use (&$res) {
            $res[$v['name']] = $v->toArray();
        });

        return $res;
    }

    public function getHiScores(string $levelName, int $limit = 10): Collection
    {
        return Score::where(['levelname' => $levelName])
            ->orderByDesc('score')
            ->limit($limit)
            ->get();
    }

    public function getHiScoresGrouped(int $limit = 10): Collection {
        return Score::orderByDesc('score')
            ->get()
            ->groupBy('levelname')
            ->map(fn(Collection $c) => $c->slice(0, $limit));
    }

    public function setHiScore(Request $request)
    {
        $request->validate([
            'levelname' => ['required', 'regex:/\w+[\w\s]+/', 'max:20'],
            'username' => ['required', 'alpha_num:ascii', 'max:20'],
            'score'    => ['required', 'integer'],
        ]);

        $levelName = $request->request->get('levelname');
        $username = $request->request->get('username');
        $score = $request->request->get('score');

        // custom updateOrCreate
        // check if score is actually better than before
        tap(Score::firstOrNew([
            'levelname' => $levelName,
            'username' => $username]
        ), function ($instance) use ($score) {
            if ($instance->score <= $score)
            $instance
                ->fill(['score' => $score])
                ->save();
        });

        return Redirect::back()->with([
            'highScores' => $this->getHiScores($levelName)->toArray(),
            'levelName' => $levelName,
        ]);
    }
}
