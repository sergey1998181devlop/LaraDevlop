<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Emploee extends Model
{
    use HasFactory;

    protected $fillable = [
        'department',
        'position',
    ];
    /**
     * Таблица, связанная с моделью.
     *
     * @var string
     */
    protected $table = 'users';

    public function departments()
    {
        return $this->belongsToMany('App\Models\Department');
    }
}
