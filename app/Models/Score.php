<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'levelname',
        'username',
        'score',
    ];

    /**
     * @param string $levelName
     */
    public function setLevelName(string $levelName): void
    {
        $this->levelname = $levelName;
    }

    /**
     * @return string
     */
    public function getLevelName(): string
    {
        return $this->levelname;
    }

    /**
     * @return int
     */
    public function getScore(): int
    {
        return $this->score;
    }

    /**
     * @param int $score
     */
    public function setScore(int $score): void
    {
        $this->score = $score;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }


}
