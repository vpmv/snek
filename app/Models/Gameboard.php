<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gameboard extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'size',
        'data->matrix',
        'data->meta',
    ];

    protected $casts = [
        'data' => AsArrayObject::class,
    ];


    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * @param int $size
     */
    public function setSize(int $size): void
    {
        $this->size = $size;
    }

    /**
     * @return array
     */
    public function getMatrix(): array
    {
        return $this->data['matrix'];
    }

    /**
     * @param array $matrix
     */
    public function setMatrix(array $matrix): void
    {
        $this->data['matrix'] = $matrix;
    }

    /**
     * @return array
     */
    public function getMeta(): array
    {
        return $this->data['meta'];
    }

    /**
     * @param array $meta
     */
    public function setMeta(array $meta): void
    {
        $this->data['meta'] = $meta;
    }
}
